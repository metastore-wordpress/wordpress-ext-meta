<?php

/**
 * Class WP_EXT_Meta_FileUploader
 * ------------------------------------------------------------------------------------------------------------------ */

class WP_EXT_Meta_FileUploader extends WP_EXT_Meta {

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
		add_filter( 'wp_handle_upload_prefilter', [ $this, 'file' ], 1, 1 );
	}

	/**
	 * Rename uploaded files.
	 *
	 * @param $file
	 *
	 * @return mixed
	 * -------------------------------------------------------------------------------------------------------------- */

	public function file( $file ) {
		$file_info = pathinfo( $file['name'] );
		$file_ext  = empty( $file_info['extension'] ) ? '' : '.' . $file_info['extension'];
		$date      = date( 'Y-m-d' );
		$hash      = self::hash();

		$file['name'] = 'FILE.' . $date . '.' . $hash . $file_ext;

		return $file;
	}

	/**
	 * Hash generator.
	 *
	 * @return string
	 * -------------------------------------------------------------------------------------------------------------- */

	public function hash() {
		$hash = hash( 'crc32b', date( 'Y-m-d.H-i-s' ) . md5( uniqid( mt_rand(), true ) ) );

		return $hash;
	}
}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta_FileUploader
 * ------------------------------------------------------------------------------------------------------------------ */

function WP_EXT_Meta_FileUploader() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta_FileUploader;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 * ------------------------------------------------------------------------------------------------------------------ */

add_action( 'plugins_loaded', [ WP_EXT_Meta_FileUploader(), 'run' ] );
