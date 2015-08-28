<?php get_header(); ?>
    
    <div id="content" class="category">
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
        
    	<div id="more-posts">
	    	<?php 
	    		//$nextLink = '<img class="svg" src="'.get_template_directory_uri().'/images/icon-more-posts.svg"/>';
	    		$nextLink = 'More posts';
	    		next_posts_link($nextLink); 
	    	?> 
    	</div>        
        
    </div>
        
<?php get_footer(); ?>