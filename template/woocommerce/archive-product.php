<?php
/*
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * @version     99.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<div id="content" class="shop">

        <?php get_template_part('store/part-product-filters'); ?>

		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
	
			<?php wc_get_template_part( 'content', 'product' ); ?>
	
		<?php endwhile; ?>

		<?php else: ?>

			<div class="fallback">
				<p>There are no products within the current filter.</p>
			</div>

		<?php endif; ?>

	</div>

<?php get_footer( 'shop' ); ?>