<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
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

	<div class="featured-image">
		<?php the_post_thumbnail('large'); ?>
	</div>

	<div class="summary entry-summary">

		<h2><?php the_title(); ?></h2>

		<div class="price">
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="regular-price">
					<?php echo $product->get_regular_price(); ?>
				</span>
			<?php endif; ?>

			<span><?php echo $product->get_price(); ?></span>
		</div>

		<div class="excerpt"><?php the_excerpt(); ?></div>

		<?php if ( $variations = $product->get_variation_attributes() ) : ?>

			<div class="variations">

				<?php woocommerce_variable_add_to_cart(); ?>

			</div>

		<?php endif; ?>

		<?php
			/*
			 * - categories/brands?
			 */
		?>

	</div><!-- .summary -->

	<div class="description">
		<?php the_content(); ?>
	</div>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->
