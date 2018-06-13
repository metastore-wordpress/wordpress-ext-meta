<?php

/**
 * Class WP_EXT_Meta_Author
 * ------------------------------------------------------------------------------------------------------------------ */

class WP_EXT_Meta_Author extends WP_EXT_Meta {

	/**
	 * Constructor.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function __construct() {
		parent::__construct();

		$this->run();
	}

	/**
	 * Plugin: `initialize`.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function run() {
		add_action( 'init', [ $this, 'author_link' ] );
	}

	/**
	 * Author page rewrite.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function author_link() {
		global $wp_rewrite;

		$wp_rewrite->author_base      = 'user';
		$wp_rewrite->author_structure = '/' . $wp_rewrite->author_base . '/%author%';
	}
}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta_Author
 * ------------------------------------------------------------------------------------------------------------------ */

function WP_EXT_Meta_Author() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta_Author;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 * ------------------------------------------------------------------------------------------------------------------ */

add_action( 'plugins_loaded', [ WP_EXT_Meta_Author(), 'run' ] );
