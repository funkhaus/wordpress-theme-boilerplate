<?php get_header(); ?>
    
    <div id="content" class="work-detail">
	    
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>            
            	
            	<div class="video">
				    <?php 
				        // Show Vimeo video
				        $args = array(
				            'width' => 1280
				        );
				        echo wp_oembed_get($post->_custom_video_url, $args);
				    ?>	            	
            	</div>
            	
                <div class="entry">
                    <?php the_content(); ?>
					<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
                </div>
                
        	</div>
        
        <?php endwhile; ?>
        <?php endif; ?>
        
    </div>
        
<?php get_footer(); ?>