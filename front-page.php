<?php get_header(); ?>

	<div id="content" class="home">

		<?php
			// Get all child pages of this page
			$args = array(
				'post_type'        => 'page',
				'orderby'          => 'menu_order',
				'posts_per_page'   => -1,
				'post_parent'      => 0,
				'order'            => 'ASC'
			);
			$slides = get_posts($args);
        ?>
        <div class="slideshow">

			

		</div>

	</div>

<?php get_footer(); ?>