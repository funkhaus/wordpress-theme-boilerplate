<?php
    $state = get_conditional_state();

    switch (true) {
    	case is_front_page() :
            $title = false;
            break;

        case $state == 'work-grid' :
    	    $title = get_the_title();
    	    $link_url = get_the_permalink();
            break;

        default :
    	    $title = get_the_title($post->post_parent);
    	    $link_url = get_the_permalink($post->post_parent);
            break;
    }
?>
<?php if($title) : ?>
    <a class="breadcrumb" href="<?php echo $link_url; ?>">
        <?php echo $title; ?>
    </a>
<?php endif; ?>