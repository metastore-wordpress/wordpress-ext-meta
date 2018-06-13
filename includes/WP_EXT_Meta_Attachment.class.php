<?php

/**
 * Class WP_EXT_Meta_Attachment
 * ------------------------------------------------------------------------------------------------------------------ */

class WP_EXT_Meta_Attachment extends WP_EXT_Meta {

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
		add_action( 'template_redirect', [ $this, 'attachment_redirect' ] );
	}

	/**
	 * Attachment pages redirect.
	 *
	 * @param int $id
	 * -------------------------------------------------------------------------------------------------------------- */

	public function attachment_redirect( $id = 0 ) {
		if ( ! defined( 'ATTACHMENT_REDIRECT_CODE' ) ) {
			define( 'ATTACHMENT_REDIRECT_CODE', '301' );
		}

		if ( ! defined( 'ORPHAN_ATTACHMENT_REDIRECT_CODE' ) ) {
			define( 'ORPHAN_ATTACHMENT_REDIRECT_CODE', '302' );
		}

		$post = get_post( $id );

		if ( is_attachment() && isset( $post->post_parent ) && is_numeric( $post->post_parent ) && ( $post->post_parent != 0 ) ) {
			$parent_post_in_trash = get_post_status( $post->post_parent ) === 'trash' ? true : false;

			if ( $parent_post_in_trash ) {
				wp_die( 'Page not found.', '404 - Page not found', 404 );
			}

			wp_safe_redirect( get_permalink( $post->post_parent ), ATTACHMENT_REDIRECT_CODE );

			exit;
		} else if ( is_attachment() && isset( $post->post_parent ) && is_numeric( $post->post_parent ) && ( $post->post_parent < 1 ) ) {
			wp_safe_redirect( get_bloginfo( 'wpurl' ), ORPHAN_ATTACHMENT_REDIRECT_CODE );

			exit;
		}
	}
}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta_Attachment
 * ------------------------------------------------------------------------------------------------------------------ */

function WP_EXT_Meta_Attachment() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta_Attachment;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 * ------------------------------------------------------------------------------------------------------------------ */

add_action( 'plugins_loaded', [ WP_EXT_Meta_Attachment(), 'run' ] );
