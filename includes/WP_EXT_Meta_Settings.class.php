<?php

/**
 * Class WP_EXT_Meta_Settings
 */
class WP_EXT_Meta_Settings extends WP_EXT_Meta {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		$this->run();
	}

	/**
	 * Plugin: `initialize`.
	 */
	public function run() {
		// Register network theme directory.
		register_theme_directory( ABSPATH . 'wp-network/themes' );

		// Remove WP generator.
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'the_generator', '__return_empty_string' );

		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// Remove WP version.
		add_filter( 'style_loader_src', [ $this, 'remove_version' ], 9999 );
		add_filter( 'script_loader_src', [ $this, 'remove_version' ], 9999 );

		// Remove WP admin logo.
		add_action( 'admin_bar_menu', [ $this, 'remove_wp_logo' ], 9999 );

		// Disable auto-updates.
		if ( is_admin() ) {
			remove_action( 'admin_init', '_maybe_update_core' );
			remove_action( 'admin_init', '_maybe_update_plugins' );
			remove_action( 'admin_init', '_maybe_update_themes' );
			remove_action( 'load-plugins.php', 'wp_update_plugins' );
			remove_action( 'load-themes.php', 'wp_update_themes' );
		}
	}

	/**
	 * Remove WP version.
	 *
	 * @param $src
	 *
	 * @return string
	 */
	public function remove_version( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}

		return $src;
	}

	/**
	 * Remove WP admin logo.
	 *
	 * @param $wp_admin_bar
	 */
	public function remove_wp_logo( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}
}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta_Settings
 */
function WP_EXT_Meta_Settings() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta_Settings;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 */
add_action( 'plugins_loaded', [ WP_EXT_Meta_Settings(), 'run' ] );
