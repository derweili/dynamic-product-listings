<?php
/**
 * Plugin Name:     Dynamic Product Listing
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     dynamic-product-listing
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Dynamic_Product_Listing
 */

// Your code starts here.
namespace Derweili\DynamicProductListing;

//  Exit if accessed directly.
defined('ABSPATH') || exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ){
	require __DIR__ . '/vendor/autoload.php';
}

/**
 * Gets this plugin's absolute directory path.
 *
 * @since  2.1.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_directory() {
	return __DIR__;
}

/**
 * Gets this plugin's URL.
 *
 * @since  2.1.0
 * @ignore
 * @access private
 *
 * @return string
 */
function _get_plugin_url() {
	static $plugin_url;

	if ( empty( $plugin_url ) ) {
		$plugin_url = plugins_url( null, __FILE__ );
	}

	return $plugin_url;
}

( new Plugin() )->run();