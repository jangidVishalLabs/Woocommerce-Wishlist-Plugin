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
	exit; // Prevent direct access.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishlist-db.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishlist-frontend.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishlist-ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wishlist-myaccount.php';

register_activation_hook( __FILE__, 'wc_wishlist_plugin_activate' );

function wc_wishlist_plugin_activate() {
	WC_Wishlist_DB::create_table();
}
WC_Wishlist_Ajax::init();
WC_Wishlist_MyAccount::init();

add_action( 'wp_enqueue_scripts', 'wc_wishlist_enqueue_scripts' );
function wc_wishlist_enqueue_scripts() {
	if( ! is_woocommerce() && ! is_product()  && ! is_account_page() ) {
		return;
	}
	wp_enqueue_style( 'wc-wishlist-style', plugin_dir_url( __FILE__ ) . 'assets/css/wishlist.css' );
	wp_enqueue_script( 'wc-wishlist-script', plugin_dir_url( __FILE__ ) . 'assets/js/wishlist.js', array( 'jquery' ), 1.0, true );

	wp_localize_script( 'wc-wishlist-script',
		'wc_wishlist',
		array(
			'ajax_url' 		 => admin_url( 'admin-ajax.php' ),
			'nonce'    		 => wp_create_nonce( 'wc_wishlist_nonce' ),
			'my_account_url' => wc_get_page_permalink( 'myaccount' ),
	) );
}

add_action( 'woocommerce_after_shop_loop_item', 'wc_wishlist_render_button', 20 );
function wc_wishlist_render_button() {
	global $product;

	WC_Wishlist_frontend::render_button( $product );
}

add_action( 'woocommerce_single_product_summary', 'wc_wishlist_single_button', 35 );
function wc_wishlist_single_button() {
	global $product;

	WC_Wishlist_frontend::render_button( $product );
}
