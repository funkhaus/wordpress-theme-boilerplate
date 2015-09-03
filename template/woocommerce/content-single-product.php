<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     99.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php get_template_part('store/part-product-gallery'); ?>

	<div class="summary entry-summary">

		<h2>
			<?php the_title(); ?>
		</h2>

		<div class="price">
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="regular-price">
					<?php echo $product->get_regular_price(); ?>
				</span>
			<?php endif; ?>

			<span>
				<?php echo $product->get_price(); ?>
			</span>
		</div>

	</div>

	<?php if ( $product->product_type == 'variable' ) : ?>

		<div class="variations">

			<?php woocommerce_variable_add_to_cart(); ?>

		</div>

	<?php else : ?>

		<button class="add-to-cart" data-url="<?php echo $product->add_to_cart_url(); ?>">
			Add To Cart
		</button>

	<?php endif; ?>

	<div class="description">
		<?php the_content(); ?>
	</div>

	<?php $story = get_post_meta($product->id, '_product_story', true); ?>
	<?php if ( ! empty($story) ): ?>

		<div class="story">
			<?php echo apply_filters('the_content', $story); ?>
		</div>

	<?php endif; ?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div>
