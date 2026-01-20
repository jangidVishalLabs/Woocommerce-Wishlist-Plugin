<?php
/**
 * Plugin Name: Wishlist Plugin
 * Description: Adds a wishlist feature to WooCommerce products.
 * Version: 1.0
 * Author: Vishal
 * License: GPL2
 * Text Domain: wishlist-plugin
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishlist-db.php';

register_activation_hook( __FILE__, 'wc_wishlist_plugin_activate' );

function wc_wishlist_plugin_activate() {
	WC_Wishlist_DB::create_table();
}