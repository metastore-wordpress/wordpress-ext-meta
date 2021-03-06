<?php

/**
 * Class WP_EXT_Meta_TransCache
 *
 * @see https://github.com/pressjitsu/pomodoro
 */
class WP_EXT_Meta_TransCache {

	/**
	 * Private state.
	 */
	private $domain = null;
	private $cache = array();
	private $busted = false;
	private $override = null;
	private $upstream = null;
	private $mofile = null;

	/**
	 * WP_EXT_Meta_TransCache constructor.
	 *
	 * @param $mofile
	 * @param $domain
	 * @param $override
	 */
	public function __construct( $mofile, $domain, $override ) {
		$this->mofile   = apply_filters( 'load_textdomain_mofile', $mofile, $domain );
		$this->domain   = $domain;
		$this->override = $override;

		$filename   = md5( serialize( array( $this->domain, $this->mofile ) ) );
		$cache_file = sprintf( '%s/%s.mocache', untrailingslashit( sys_get_temp_dir() ), $filename );

		$mtime = filemtime( $this->mofile );

		if ( file_exists( $cache_file ) ) {

			/**
			 * Load cache.
			 *
			 * OPcache will grab the values from memory.
			 * ------------------------------------------------------------------------------------------------------ */
			include $cache_file;
			$this->cache = &$_cache;

			/**
			 * Mofile has been modified, invalidate it all.
			 * ------------------------------------------------------------------------------------------------------ */
			if ( ! isset( $_mtime ) || ( isset( $_mtime ) && $_mtime < $mtime ) ) {
				$this->cache = array();
			}
		}

		$_this = &$this;

		register_shutdown_function( function () use ( $cache_file, $_this, $mtime ) {

			/**
			 * New values have been found. Dump everything into a valid PHP script.
			 */
			if ( $this->busted ) {
				file_put_contents( $cache_file, sprintf( '<?php $_mtime = %d; $_cache = %s;', $mtime, var_export( $_this->cache, true ) ), LOCK_EX );
			}
		} );
	}

	/**
	 * Get translation.
	 *
	 * @param $cache_key
	 * @param $text
	 * @param $args
	 *
	 * @return mixed
	 */
	private function get_translation( $cache_key, $text, $args ) {

		/**
		 * Check cache first.
		 */
		if ( isset( $this->cache[ $cache_key ] ) ) {
			return $this->cache[ $cache_key ];
		}

		$translate_function = count( $args ) == 4 ? 'translate_plural' : 'translate';

		/**
		 * Merge overrides.
		 */
		if ( $this->override ) {
			if ( ( $translation = call_user_func_array( array(
					$this->override,
					$translate_function
				), $args ) ) != $text ) {
				$this->busted = true;

				return $this->cache[ $cache_key ] = $translation;
			}
		}

		/**
		 * Default Mo upstream.
		 */
		if ( ! $this->upstream ) {
			$this->upstream = new Mo();
			do_action( 'load_textdomain', $this->domain, $this->mofile );
			$this->upstream->import_from_file( $this->mofile );
		}

		if ( ( $translation = call_user_func_array( array(
				$this->upstream,
				$translate_function
			), $args ) ) != $text ) {
			$this->busted = true;

			return $this->cache[ $cache_key ] = $translation;
		}

		$translation  = call_user_func_array( array( $this->upstream, $translate_function ), $args );
		$this->busted = true;

		return $this->cache[ $cache_key ] = $translation;
	}

	/**
	 * The translate() function implementation that WordPress calls.
	 *
	 * @param $text
	 * @param null $context
	 *
	 * @return mixed
	 */
	public function translate( $text, $context = null ) {
		return $this->get_translation( $this->cache_key( func_get_args() ), $text, func_get_args() );
	}

	/**
	 * The translate_plural() function implementation that WordPress calls.
	 *
	 * @param $singular
	 * @param $plural
	 * @param $count
	 * @param null $context
	 *
	 * @return mixed
	 */
	public function translate_plural( $singular, $plural, $count, $context = null ) {
		$text = ( abs( $count ) == 1 ) ? $singular : $plural;

		return $this->get_translation( $this->cache_key( array( $text, $context ) ), $text, func_get_args() );
	}

	/**
	 * Cache key calculator.
	 *
	 * @param $args
	 *
	 * @return string
	 */
	private function cache_key( $args ) {
		return md5( serialize( array( $args, $this->domain ) ) );
	}
}

/**
 * Override `textdomain`.
 */
add_filter( 'override_load_textdomain', function ( $plugin_override, $domain, $mofile ) {
	if ( ! is_readable( $mofile ) ) {
		return false;
	}

	global $l10n;

	$mo              = new WP_EXT_Meta_TransCache( $mofile, $domain, $upstream = empty( $l10n[ $domain ] ) ? null : $l10n[ $domain ] );
	$l10n[ $domain ] = $mo;

	return true;
}, 999, 3 );
