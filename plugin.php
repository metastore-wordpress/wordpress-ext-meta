<?php
/**
 * Plugin Name:     (WP-EXT) Meta
 * Plugin URI:      https://metastore.pro/
 *
 * Description:     Register metadata.
 *
 * Author:          Kitsune Solar
 * Author URI:      https://kitsune.solar/
 *
 * Version:         1.0.0
 *
 * Text Domain:     wp-ext-meta
 * Domain Path:     /languages
 *
 * License:         GPLv3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Loading `WP_EXT_Meta`.
 */

function run_wp_ext_system_meta() {
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_Settings.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_Attachment.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_Author.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_FileUploader.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_ImageEditor.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_Login.class.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/WP_EXT_Meta_TransCache.class.php' );
}

run_wp_ext_system_meta();
