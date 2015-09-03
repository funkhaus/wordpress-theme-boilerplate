<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     99.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() && !is_store_guest() ) {
	wp_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );
	exit;
}

wc_print_notices();

// remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
do_action( 'woocommerce_before_checkout_form', $checkout );

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

<div class="checkout-controls">

	<div class="switch" data-phase="shipping">Shipping</div>
	<div class="switch" data-phase="billing">Billing</div>
	<div class="switch" data-phase="confirmation">Confirmation</div>

</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="shipping view" data-phase="shipping">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
		
		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>


		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>		
		
		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="billing view" data-phase="billing">

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10); ?>
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php $ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0; ?>

				<h3 id="bill-to-different-address">
					<label for="bill-to-different-address-checkbox" class="checkbox">Billing address different from shipping address</label>
					<input id="bill-to-different-address-checkbox" class="input-checkbox" <?php checked( $ship_to_different_address, 1 ); ?> type="checkbox" name="bill_to_different_address" value="1" />
				</h3>

				<div class="bill-fields">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>
			</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<div class="confirmation view" data-phase="confirmation">
			<input type="submit" value="Submit" />
		</div>

		<div class="subtotals">
			<?php woocommerce_order_review(); ?>
		</div>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
