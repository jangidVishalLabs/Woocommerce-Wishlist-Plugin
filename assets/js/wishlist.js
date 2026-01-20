jQuery(function ($) {

    // Use event delegation for dynamic content
    $(document).on('click', '.wc-wishlist-button', function (e) {
        e.preventDefault();

        let btn = $(this);
        let productId = btn.data('product-id');
        let variationId = btn.data('variation-id') || 0;
        let loggedIn = btn.data('logged-in') || false;

        // Redirect guest users
        if (!loggedIn) {
            window.location.href = wc_wishlist.my_account_url;
            return;
        }

        let action = btn.hasClass('added')
            ? 'wc_remove_from_wishlist'
            : 'wc_add_to_wishlist';

        $.post(wc_wishlist.ajax_url, {
            action: action,
            product_id: productId,
            variation_id: variationId,
            nonce: wc_wishlist.nonce
        }, function (response) {

            if (response.success) {
                btn.toggleClass('added');
                btn.text(
                    btn.hasClass('added')
                        ? '❤️ Wishlisted'
                        : '❤️ Add to Wishlist'
                );
            }

        });
    });

$(document).on('click', '.wc-remove-wishlist', function (e) {
    e.preventDefault();

    let btn = $(this);
    let productId = btn.data('product-id');

    $.post(wc_wishlist.ajax_url, {
        action: 'wc_remove_from_wishlist',
        product_id: productId,
        variation_id: 0,
        nonce: wc_wishlist.nonce
    }, function (response) {

        if (response.success) {
            btn.closest('.wc-wishlist-card').fadeOut(300, function () {
                $(this).remove();
            });
        }
    });
});


});
