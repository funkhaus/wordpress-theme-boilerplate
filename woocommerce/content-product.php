<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>

<div <?php post_class( 'shop-block' ); ?>>

	<a href="<?php the_permalink(); ?>">

		<div class="image-wrap"><?php echo $product->get_image('medium'); ?></div>

		<h3><?php the_title(); ?></h3>

		<div class="price">
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="regular-price">
					<?php echo $product->get_regular_price(); ?>
				</span>
			<?php endif; ?>

			<span><?php echo $product->get_price(); ?></span>
		</div>

	</a>

	<button class="add-to-cart" data-url="<?php echo $product->add_to_cart_url(); ?>">
		Add To Cart
	</button>

</div>