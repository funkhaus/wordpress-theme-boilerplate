<?php
	global $post;
	$state = get_conditional_state($post);

	switch (true) {
        case $state == 'work' and $child_id = get_first_child_id() :
            wp_redirect( get_permalink($child_id), 301 );
            exit;

	    case $state == 'work-grid' :
	        get_template_part('templates/work-grid');
	        break;

	    case $state == 'work-detail' :
	        get_template_part('templates/work-detail');
	        break;

	    default :
	        get_template_part('index');
	        break;
	}
?>