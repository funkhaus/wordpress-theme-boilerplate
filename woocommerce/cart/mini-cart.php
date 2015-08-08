<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<div id="sidecart">

	<div class="product-list">
		<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ):
	
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		?>
	
			<div class="cart-product">
	
				<?php echo get_the_post_thumbnail($product_id); ?>
	
				<h4>
					<?php echo get_the_title($product_id); ?>
				</h4>
	
				<div class="cost">
					<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
				</div>
				<div class="qty">
					<?php
						echo apply_filters( 'woocommerce_cart_item_quantity', $cart_item['quantity'], $cart_item_key );
					?>
				</div>
				<div class="product-subtotal">
					<?php
						echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
					?>
				</div>
	
			</div>
	
		<?php endforeach; ?>
	</div>
	<div class="totals">

		<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : 
			$tax_array = WC()->cart->get_taxes();
		?>

			<p class="total"><strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>
			<p class="tax"><strong><?php _e( 'Tax', 'woocommerce' ); ?>:</strong> <?php echo number_format(array_sum($tax_array), 2); ?></p>
			<p class="shipping"><strong><?php _e( 'Shipping', 'woocommerce' ); ?>:</strong> <?php wc_cart_totals_shipping_html(); ?></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<p class="buttons">
				<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="button wc-forward"><?php _e( 'View Cart', 'woocommerce' ); ?></a>
				<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button checkout wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_mini_cart' ); ?>

	</div>

</div>
