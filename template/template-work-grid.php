<?php get_header(); ?>
    
    <div id="content" class="work-grid">
    
        <?php
            /*
             * Get all children of this page
             */
            $args = array(
                'post_type'        => 'page',
            	'orderby'          => 'menu_order',
            	'posts_per_page'   => -1,
            	'post_parent'      => $post->ID,
            	'order'            => 'ASC'
            );
            query_posts( $args );
        ?>     	
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class('work-block'); ?>>            
            
                <?php the_post_thumbnail('video-thumb'); ?>
                
                <div class="entry">
                    <?php the_title(); ?>
					<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
                </div>
                
        	</div>
        
        <?php endwhile; ?>
        <?php endif; ?>
    </div>
        
<?php get_footer(); ?>