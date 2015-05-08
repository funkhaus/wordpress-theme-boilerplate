<?php get_header(); ?>
    
    <div id="content" class="home">

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
	
			    <?php 
			        // Get image background form featured thumb
			        $attachmentData = wp_get_attachment_image_src( get_post_thumbnail_id(), 'fullscreen');
			        $imageURL = $attachmentData[0];
			    ?>
				    
				<div id="post-<?php the_ID(); ?>" <?php post_class('slide fullbleed'); ?> style="background-image: url(<?php echo $imageURL; ?>);">   

	                <div class="entry">
	                    <?php the_content(); ?>
						<?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
	                </div>
	                
	        	</div>
	        
			<?php endforeach; ?>
		</div>
				
    </div>
        
<?php get_footer(); ?>