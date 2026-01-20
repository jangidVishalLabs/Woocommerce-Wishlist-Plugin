<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

class WC_Wishlist_DB {

	public static function create_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wc_wishlists';
		$charset_collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = "CREATE TABLE $table_name (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id bigint(20) UNSIGNED NOT NULL,
			product_id bigint(20) UNSIGNED NOT NULL,
			variation_id bigint(20) UNSIGNED DEFAULT NULL DEFAULT 0,
			data_added DATETIME NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY user_product (user_id, product_id, variation_id),
			KEY user_id (user_id),
			KEY product_id (product_id)
		) $charset_collate;";
		dbDelta( $sql );
	}
	/**
	 * Get wishlist table name
 	*/
	private static function table_name() {
    	global $wpdb;
    	return $wpdb->prefix . 'wc_wishlists';
	}


	/**
	 * CREATE: Add item to wishlist
	 */
	public static function add_item( $user_id, $product_id, $variation_id = 0 ) {
		global $wpdb;
		$table = self::table_name();

		//Prevent Duplicates
		if ( self::is_in_wishlist( $user_id, $product_id, $variation_id ) ) {
			return false;
		}

		return $wpdb->insert(
			$table,
			array(
				'user_id'    => (int) $user_id,
				'product_id' => (int) $product_id,
				'variation_id' => (int) $variation_id,
				'data_added' => current_time( 'mysql' ),
			),
			array(
				'%d',
				'%d',
				'%d',
				'%s',
			)
		);
	}

	/**
	 * READ: Check if item is in wishlist
	 */
	public static function is_in_wishlist( $user_id, $product_id, $variation_id = 0 ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT id FROM " . self::table_name() . " WHERE user_id = %d AND product_id = %d AND variation_id = %d LIMIT 1",
			$user_id, $product_id, $variation_id
		);

		return (bool) $wpdb->get_var( $query );
	}
	/**
	 * DELETE: Remove item from wishlist
	 */
	public static function remove_item( $user_id, $product_id, $variation_id = 0 ) {
		global $wpdb;

		return $wpdb->delete(
			self::table_name(),
			array(
				'user_id'    => (int) $user_id,
				'product_id' => (int) $product_id,
				'variation_id' => (int) $variation_id,
			),
			array(
				'%d',
				'%d',
				'%d',
			)
		);
	}
	/**
	 * READ: Get all wishlist items for a user
	 */
	public static function get_user_wishlist( $user_id ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT * FROM " . self::table_name() . " WHERE user_id = %d ORDER BY data_added DESC",
			$user_id
		);

		return $wpdb->get_results( $query );
	}

	/**
	 * READ: Count wishlist items for a user
	 */
	public static function get_user_wishlist_count( $user_id ) {
		global $wpdb;
		$query = $wpdb->prepare(
			"SELECT COUNT(*) FROM " . self::table_name() . " WHERE user_id = %d",
			$user_id
		);
		return (int) $wpdb->get_var( $query );
	}
}
