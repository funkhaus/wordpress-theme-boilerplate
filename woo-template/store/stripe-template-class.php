<?php
/**
 *
 * This is a special file that overrides some defaults
 * in the stripe plugin to allow customization.
 *
 */
	function init_custom_stripe_template(){
		include_once ABSPATH . 'wp-content/plugins/woocommerce-gateway-stripe/includes/class-wc-gateway-stripe.php';

		if ( ! class_exists( 'WC_Gateway_Stripe' ) ){
			return;
		}

		class Custom_Stripe_Template extends WC_Gateway_Stripe {
	
			// call the parent constructor, doesn't happen by default
			public function __construct(){
				parent::__construct();
			}

			public function payment_fields(){
				$checked = 1;
				?>
				<div class="card-payment">
					<?php
						$allowed = array(
						    'a' => array(
						        'href' => array(),
						        'title' => array()
						    ),
						    'br' => array(),
						    'em' => array(),
						    'strong' => array(),
						    'span'	=> array(
						    	'class' => array(),
						    ),
						);
						if ( $this->description ) {
							echo apply_filters( 'wc_stripe_description', wpautop( wp_kses( $this->description, $allowed ) ) );
						}
						if ( $this->saved_cards && is_user_logged_in() && ( $customer_id = get_user_meta( get_current_user_id(), '_stripe_customer_id', true ) ) && is_string( $customer_id ) && ( $cards = $this->get_saved_cards( $customer_id ) ) ) {
							?>
							<p class="form-row form-row-wide">
								<a class="<?php echo apply_filters( 'wc_stripe_manage_saved_cards_class', 'button' ); ?>" style="float:right;" href="<?php echo apply_filters( 'wc_stripe_manage_saved_cards_url', get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>#saved-cards"><?php _e( 'Manage cards', 'woocommerce-gateway-stripe' ); ?></a>
								<?php if ( $cards ) : ?>
									<?php foreach ( (array) $cards as $card ) :
										if ( 'card' !== $card->object ) {
											continue;
										}
										?>
										<label for="stripe_card_<?php echo $card->id; ?>">
											<input type="radio" id="stripe_card_<?php echo $card->id; ?>" name="stripe_card_id" value="<?php echo $card->id; ?>" <?php checked( $checked, 1 ) ?> />
											<?php printf( __( '%s card ending in %s (Expires %s/%s)', 'woocommerce-gateway-stripe' ), $card->brand, $card->last4, $card->exp_month, $card->exp_year ); ?>
										</label>
										<?php $checked = 0; endforeach; ?>
								<?php endif; ?>
								<label for="new">
									<input type="radio" id="new" name="stripe_card_id" <?php checked( $checked, 1 ) ?> value="new" />
									<?php _e( 'Use a new credit card', 'woocommerce-gateway-stripe' ); ?>
								</label>
							</p>
							<?php
						}
					?>
					<div class="stripe_new_card" <?php if ( $checked === 0 ) : ?>style="display:none;"<?php endif; ?>
						data-description=""
						data-amount="<?php echo esc_attr( $this->get_stripe_amount( WC()->cart->total ) ); ?>"
						data-name="<?php echo esc_attr( sprintf( __( '%s', 'woocommerce-gateway-stripe' ), get_bloginfo( 'name' ) ) ); ?>"
						data-label="<?php esc_attr_e( 'Confirm and Pay', 'woocommerce-gateway-stripe' ); ?>"
						data-currency="<?php echo esc_attr( strtolower( get_woocommerce_currency() ) ); ?>"
						data-image="<?php echo esc_attr( $this->stripe_checkout_image ); ?>"
						data-bitcoin="<?php echo esc_attr( $this->bitcoin ? 'true' : 'false' ); ?>"
						>
						<?php if ( ! $this->stripe_checkout ) : ?>
							<?php $this->credit_card_form( array( 'fields_have_names' => false ) ); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php

			}

			public function get_icon() {

				return '';
			}
		}

	/*
	 * Add the Gateway to WooCommerce
	 */
		function add_custom_stripe_gateway( $methods ) {
			$methods['WC_Gateway_Stripe'] = 'Custom_Stripe_Template';
			return $methods;
		}
		add_filter( 'woocommerce_payment_gateways', 'add_custom_stripe_gateway' );

	}

add_action( 'after_setup_theme', 'init_custom_stripe_template');