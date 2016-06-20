<?php
	global $post;
	$state = get_conditional_state($post);

	switch (true) {
	    case $state == 'work' :
	        if ( ! redirect_to_first_child($post) ) {
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