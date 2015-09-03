<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     99.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $woocommerce;
?>

<?php 
    do_action('woocommerce_before_mini_cart'); 
    $cart_items = WC()->cart->get_cart();
?>

<div id="sidecart" data-cart-count="<?php echo sizeof($cart_items); ?>">

    <?php if( sizeof( $cart_items ) > 0 ) : ?>

    	<div class="product-list">
    		<?php foreach ( $cart_items as $cart_item_key => $cart_item ) : ?>

    			<?php
    				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
    				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
    			?>

    			<div class="cart-product">

    				<button class="remove-from-cart" data-url="<?php echo $woocommerce->cart->get_remove_url($cart_item_key); ?>">
    					X
    				</button>

    				<?php if ( $_product->product_type == 'variation' ) : ?>
    
    					<?php $atts = $_product->get_attributes(); ?>
    					<?php foreach ( $atts as $label => $att ): ?>
    
    						<?php var_dump($_product->get_attribute($att['name'])); ?>
    
    					<?php endforeach; ?>
    
    				<?php endif; ?>

    				<a href="<?php echo get_permalink($product_id); ?>">
    					<?php echo get_the_post_thumbnail($product_id, 'thumbnail'); ?>
    				</a>

    				<h4>
    					<?php echo get_the_title($product_id); ?>
    				</h4>

    				<div class="cost">
    					<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
    				</div>
    				<div class="qty">
    					<?php echo apply_filters( 'woocommerce_cart_item_quantity', $cart_item['quantity'], $cart_item_key ); ?>
    				</div>
    				<div class="product-subtotal">
    					<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
    				</div>

    			</div>

    		<?php endforeach; ?>
    	</div>

    	<div class="totals">

    		<?php if( sizeof( $cart_items ) > 0 ) : ?>

    			<p class="total">
    				<strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?>
    			</p>

    			<p class="tax">
    				<?php $tax_array = WC()->cart->get_taxes(); ?>
    				<strong><?php _e( 'Tax', 'woocommerce' ); ?>:</strong> <?php echo number_format(array_sum($tax_array), 2); ?>
    			</p>

    			<?php if ( $shipping = WC()->cart->calculate_shipping() ): ?>
    				<p class="shipping">
    					<strong><?php _e( 'Shipping', 'woocommerce' ); ?>:</strong> 
    					<?php // wc_cart_totals_shipping_html(); ?>
    					<?php echo $shipping; ?>
    				</p>
    			<?php else: ?>
    				<p class="shipping blank">
    					Shipping calculated at checkout.
    				</p>
    			<?php endif; ?>

    			<p class="buttons">
    				<button class="button close">
    					X Close
    				</button>

    				<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="checkout">
    					<?php _e( 'Checkout', 'woocommerce' ); ?>
    				</a>
    			</p>

    		<?php endif; ?>

    	</div>

    <?php else : ?>

        <p class="empty-cart">
            Your cart is empty, fill it up!
        </p>

    <?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>