<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     99.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields">
	<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>

		<?php
			if ( empty( $_POST ) ) {

				$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0;
				$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

			} else {

				$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

			}
		?>

		<div class="shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

				<?php $ship_fields = $checkout->checkout_fields['shipping']; ?>

				<div class="shipping-info">

					<?php woocommerce_form_field( 'shipping_first_name', $ship_fields['shipping_first_name'], $checkout->get_value( 'shipping_first_name' ) ); ?>
					<?php woocommerce_form_field( 'shipping_last_name', $ship_fields['shipping_last_name'], $checkout->get_value( 'shipping_last_name' ) ); ?>
					<?php woocommerce_form_field( 'shipping_country', $ship_fields['shipping_country'], $checkout->get_value( 'shipping_country' ) ); ?>
					<?php woocommerce_form_field( 'shipping_address_1', $ship_fields['shipping_address_1'], $checkout->get_value( 'shipping_address_1' ) ); ?>
					<?php woocommerce_form_field( 'shipping_address_2', $ship_fields['shipping_address_2'], $checkout->get_value( 'shipping_address_2' ) ); ?>
					<?php woocommerce_form_field( 'shipping_city', $ship_fields['shipping_city'], $checkout->get_value( 'shipping_city' ) ); ?>
					<?php woocommerce_form_field( 'shipping_state', $ship_fields['shipping_state'], $checkout->get_value( 'shipping_state' ) ); ?>
					<?php woocommerce_form_field( 'shipping_postcode', $ship_fields['shipping_postcode'], $checkout->get_value( 'shipping_postcode' ) ); ?>

				</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() ) : ?>

			<h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
