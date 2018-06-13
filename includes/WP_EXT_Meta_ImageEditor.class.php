<?php

/**
 * Class WP_EXT_Meta_ImageEditor
 * ------------------------------------------------------------------------------------------------------------------ */

class WP_EXT_Meta_ImageEditor extends WP_EXT_Meta {

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
		add_filter( 'wp_image_editors', [ $this, 'image_library' ] );
	}

	/**
	 * Force use of ImageMagick image library.
	 *
	 * @return array
	 * -------------------------------------------------------------------------------------------------------------- */

	public function image_library() {
		return array( 'WP_Image_Editor_Imagick' );
	}
}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta_ImageEditor
 * ------------------------------------------------------------------------------------------------------------------ */

function WP_EXT_Meta_ImageEditor() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta_ImageEditor;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 * ------------------------------------------------------------------------------------------------------------------ */

add_action( 'plugins_loaded', [ WP_EXT_Meta_ImageEditor(), 'run' ] );
