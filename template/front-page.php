<?php get_header(); ?>

    <main class="home">

        <?php
            // Get all child pages of this page
            $args = array(
                'post_type'        => 'page',
            	'orderby'          => 'menu_order',
            	'posts_per_page'   => -1,
            	'post_parent'      => $post->ID,
            	'order'            => 'ASC'
            );
            $slides = get_posts($args);
        ?>
        <div class="slideshow">
			<?php foreach($slides as $post) : setup_postdata($post); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class('slide fullbleed'); ?> style="background-image: url(<?php echo get_featured_image_url(); ?>);">

	                <div class="entry">
	                    <?php the_content(); ?>
						<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
	                </div>

	        	</div>

			<?php endforeach; ?>
		</div>

    </main>

<?php get_footer(); ?>
