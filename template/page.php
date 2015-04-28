<?php
	global $post;
	
	switch (true) {
	    case is_page('work') :
	        // Redirect to first child page.
	        $pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
	        $firstchild = $pagekids[0];
	        if( $firstchild ) {
	            wp_redirect(get_permalink($firstchild->ID), 301);        
	            exit;
	        } else {
	            get_template_part('index');
	        }
	        break;
	
	    case has_children($post->ID) :
	        // If has child pages
	        get_template_part('template-work-grid');
	        break;
	
	    case is_tree(5) :
	        // Is in the "About" tree.
	        get_template_part('template-work-grid');
	        break;
	
	    case !empty($post->_custom_video_url) : 
	        // Page has a video URL metabox value saved, so use the work detail template. 
	        get_template_part('template-work-detail');
	        break;          
	
	    default:
	        get_template_part('index');
	        break;
	}
?>	