<?php
/*
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<div id="content" class="shop">

		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
	
			<?php wc_get_template_part( 'content', 'product' ); ?>
	
		<?php endwhile; ?>
		<?php endif; ?>

	</div>

<?php get_footer( 'shop' ); ?>