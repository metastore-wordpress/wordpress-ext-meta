<?php

/**
 * Class WP_EXT_Meta
 * ------------------------------------------------------------------------------------------------------------------ */

class WP_EXT_Meta {

	/**
	 * Constructor.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function __construct() {
		// Languages.
		self::languages();

		// Initialize.
		$this->run();
	}

	/**
	 * Plugin: `initialize`.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function run() {
	}

	/**
	 * Plugin: `languages`.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function languages() {
		load_plugin_textdomain(
			'wp-ext-meta',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}

}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta
 * ------------------------------------------------------------------------------------------------------------------ */

function WP_EXT_Meta() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 * ------------------------------------------------------------------------------------------------------------------ */

add_action( 'plugins_loaded', [ WP_EXT_Meta(), 'run' ] );
