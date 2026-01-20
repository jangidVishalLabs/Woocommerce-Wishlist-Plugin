<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WC_Wishlist_Ajax {

	public static function init() {
		add_action( 'wp_ajax_wc_add_to_wishlist', array( __CLASS__, 'add_to_wishlist' ) );
		add_action( 'wp_ajax_wc_remove_from_wishlist', array( __CLASS__, 'remove_from_wishlist' ) );
	}

	public static function add_to_wishlist() {

		check_ajax_referer( 'wc_wishlist_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'User not logged in.' ) );
		}
		$product_id = absint( $_POST['product_id'] ?? 0 );
		$variation_id = absint( $_POST['variation_id'] ?? 0 );
		$user_id = get_current_user_id();

		if ( ! $product_id || ! wc_get_product( $product_id ) ) {
			wp_send_json_error( array( 'message' => 'Invalid product.' ) );
		}

		WC_Wishlist_DB::add_item( $user_id, $product_id, $variation_id );

		wp_send_json_success( array( 'message' => 'Product added to wishlist.' ) );
	}

	public static function remove_from_wishlist() {

		check_ajax_referer( 'wc_wishlist_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'User not logged in.' ) );
		}
		$product_id = absint( $_POST['product_id'] ?? 0 );
		$variation_id = absint( $_POST['variation_id'] ?? 0 );
		$user_id = get_current_user_id();

		if ( ! $product_id || ! wc__get_product( $product_id ) ) {
			wp_send_json_error( array( 'message' => 'Invalid product.' ) );
		}

		WC_Wishlist_DB::remove_item( $user_id, $product_id, $variation_id );

		wp_send_json_success( array( 'message' => 'Product removed from wishlist.' ) );
	}
}
