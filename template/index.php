<?php get_header(); ?>
    
    <div id="content" class="fallback">
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>            
            
                <div class="entry">
                    <?php the_content(); ?>
					<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
                </div>
                
        	</div>
        
        <?php endwhile; ?>
        <?php endif; ?>
    </div>
        
<?php get_footer(); ?>