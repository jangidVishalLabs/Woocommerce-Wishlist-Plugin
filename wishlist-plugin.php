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
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishlist-frontend.php';

register_activation_hook( __FILE__, 'wc_wishlist_plugin_activate' );

function wc_wishlist_plugin_activate() {
	WC_Wishlist_DB::create_table();
}

add_action( 'woocommerce_after_shop_loop_item', 'wc_wishlist_render_button', 20 );
function wc_wishlist_render_button() {
	global $product;

	WC_Wishlist_frontend::render_button( $product );
}

add_action('woocommerce_single_product_summary', 'wc_wishlist_single_button', 35);
function wc_wishlist_single_button() {
	global $product;

	WC_Wishlist_frontend::render_button( $product );
}