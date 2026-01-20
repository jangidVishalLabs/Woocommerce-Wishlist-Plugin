<?php
if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WC_Wishlist_frontend {

	public static function render_button( $product ){
		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$user_id = get_current_user_id();
		$product_id = $product->get_id();
		$variation_id = 0;

		$is_logged_in = is_user_logged_in();
		$in_washlist = false;
		if ( $user_id ) {
            $in_wishlist = WC_Wishlist_DB::is_in_wishlist(
                $user_id,
                $product_id,
                $variation_id
            );
        }

		$button_text = $in_wishlist ? 'Wishlisted' : 'Add to Wishlist';
		$button_class = $in_wishlist ? 'added' : '';

		?>
		<button class="wc-wishlist-button <?php echo esc_attr( $button_class ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-variation-id="<?php echo esc_attr( $variation_id ); ?>" data-logged-in="<?php echo  $is_logged_in ? "1" : "0"; ?>">❤️ <?php echo esc_html( $button_text ); ?></button>
		
		
		<?php
	}
}