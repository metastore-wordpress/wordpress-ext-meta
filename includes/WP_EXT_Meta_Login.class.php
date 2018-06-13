<?php

/**
 * Class WP_EXT_Meta_Login
 * ------------------------------------------------------------------------------------------------------------------ */

class WP_EXT_Meta_Login extends WP_EXT_Meta {

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
		add_filter( 'login_headerurl', [ $this, 'login_logo_url' ] );
		add_filter( 'login_headertitle', [ $this, 'login_logo_title' ] );
		add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );
//      add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
	}

	/**
	 * Login logo URL.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function login_logo_url( $url ) {
		$out = home_url();

		return $out;
	}

	/**
	 * Login logo title.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function login_logo_title() {
		$out = esc_attr( get_bloginfo( 'name' ) );

		return $out;
	}

	/**
	 * Login styles.
	 * -------------------------------------------------------------------------------------------------------------- */

	public function login_styles() {
		wp_enqueue_style( 'ext-plugin-meta', plugins_url( 'themes/styles/theme.css', __DIR__ ), [], '' );
	}
}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @return WP_EXT_Meta_Login
 * ------------------------------------------------------------------------------------------------------------------ */

function WP_EXT_Meta_Login() {
	static $object;

	if ( null == $object ) {
		$object = new WP_EXT_Meta_Login;
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 * ------------------------------------------------------------------------------------------------------------------ */

add_action( 'plugins_loaded', [ WP_EXT_Meta_Login(), 'run' ] );
