<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     99.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @global WC_Checkout $checkout */
?>
<div class="woocommerce-billing-fields">

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<?php $bill_fields = $checkout->checkout_fields['billing']; ?>

	<div class="billing-info">

		<?php woocommerce_form_field( 'billing_email', $bill_fields['billing_email'], $checkout->get_value( 'billing_email' ) ); ?>
		<?php woocommerce_form_field( 'billing_first_name', $bill_fields['billing_first_name'], $checkout->get_value( 'billing_first_name' ) ); ?>
		<?php woocommerce_form_field( 'billing_last_name', $bill_fields['billing_last_name'], $checkout->get_value( 'billing_last_name' ) ); ?>
		<?php woocommerce_form_field( 'billing_country', $bill_fields['billing_country'], $checkout->get_value( 'billing_country' ) ); ?>
		<?php woocommerce_form_field( 'billing_address_1', $bill_fields['billing_address_1'], $checkout->get_value( 'billing_address_1' ) ); ?>
		<?php woocommerce_form_field( 'billing_address_2', $bill_fields['billing_address_2'], $checkout->get_value( 'billing_address_2' ) ); ?>
		<?php woocommerce_form_field( 'billing_city', $bill_fields['billing_city'], $checkout->get_value( 'billing_city' ) ); ?>
		<?php woocommerce_form_field( 'billing_state', $bill_fields['billing_state'], $checkout->get_value( 'billing_state' ) ); ?>
		<?php woocommerce_form_field( 'billing_postcode', $bill_fields['billing_postcode'], $checkout->get_value( 'billing_postcode' ) ); ?>

		<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>

	</div>

	<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

		<?php if ( $checkout->enable_guest_checkout ) : ?>

			<p class="form-row form-row-wide create-account">
				<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', 'woocommerce' ); ?></label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

			<div class="create-account">

				<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'woocommerce' ); ?></p>

				<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

				<?php endforeach; ?>

				<div class="clear"></div>

			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

	<?php endif; ?>
</div>
