<?php
	global $post;
	$state = get_conditional_state($post);

	switch (true) {
	    case $state == 'work' :
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

	    case $state == 'work-grid' :
	        get_template_part('template-work-grid');
	        break;

	    case $state == 'work-detail' :
	        get_template_part('template-work-detail');
	        break;

	    default:
	        get_template_part('index');
	        break;
	}
?>	