<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Wishlist_MyAccount {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'add_endpoints' ) );
        add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_menu_items' ) );
        add_action( 'woocommerce_account_wishlist_endpoint', array( __CLASS__, 'render_content' ) );
    }

    public static function add_endpoints() {
        add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    }

    public static function add_menu_items( $items ) {
        $new_items = array();

        foreach ( $items as $key => $value ) {
            $new_items[ $key ] = $value;

            if ( 'orders' === $key ) {
                $new_items['wishlist'] = __( 'Wishlist', 'wishlist-plugin' );
            }
        }

        return $new_items;
    }

    public static function render_content() {
		error_log( 'Rendering wishlist content' );
        $user_id = get_current_user_id();

        if ( ! $user_id ) {
            echo '<p>' . esc_html__( 'Please login to view your wishlist.', 'wishlist-plugin' ) . '</p>';
            return;
        }

        $wishlist_items = WC_Wishlist_DB::get_user_wishlist( $user_id );

        if ( empty( $wishlist_items ) ) {
            echo '<p>' . esc_html__( 'Your wishlist is empty.', 'wishlist-plugin' ) . '</p>';
            return;
        }

        echo '<div class="wc-wishlist-grid">';

        foreach ( $wishlist_items as $item ) {

            $product = wc_get_product( $item->product_id );
            if ( ! $product ) {
                continue;
            }
            ?>
            <div class="wc-wishlist-card">

                <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                    <?php echo $product->get_image( 'medium' ); ?>
                </a>

                <h3><?php echo esc_html( $product->get_name() ); ?></h3>

                <span class="price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </span>

                <div class="wishlist-actions">

                    <button
                        class="wc-remove-wishlist"
                        data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                    >
                        ❌ Remove
                    </button>

                    <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button buy-now">
                            Buy Now
                        </a>
                    <?php else : ?>
                        <span class="out-of-stock">Out of stock</span>
                    <?php endif; ?>

                </div>
            </div>
            <?php
        }

        echo '</div>';
    }
}
