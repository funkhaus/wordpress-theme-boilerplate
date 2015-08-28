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

		

	</div>

</div>