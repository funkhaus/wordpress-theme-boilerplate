<?php
    // Defaults to site generic info
    $shared_image = get_template_directory_uri() . '/screenshot.png';
    $summary = get_bloginfo('description');
    $url = get_bloginfo('url');
    $title = wp_title('', false);
    $type = 'website';

    switch (true) {
        case is_home() :
            $title = get_bloginfo('name');
            break;

    	case is_front_page() :
            $title = get_bloginfo('name');
            break;

        case is_single() :
            $type = 'article';

    	case is_single() or is_page() :
            $url = get_permalink($post->ID);

            // Generate an excerpt
            $summary = get_the_excerpt();
            if( empty($summary) ) {
                $summary = wp_trim_excerpt( strip_shortcodes($post->post_content) );
            }

            // Set image to post thumbnail
            $image_id = get_post_thumbnail_id();
            if( !empty($image_id) ) {
                $image_url = wp_get_attachment_image_src($image_id, 'social-preview');
                $shared_image = $image_url[0];
            }

            break;
    }

    // Remove any links, tags or line breaks from summary
    $summary = strip_tags($summary);
    $summary = esc_attr($summary);
    $summary = preg_replace('!\s+!', ' ', $summary);
?>

<!-- Start Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo trim($title); ?>"/>
    <meta property="og:type" content="<?php echo $type; ?>"/>
    <meta property="og:url" content="<?php echo $url; ?>"/>
    <meta property="og:image" content="<?php echo $shared_image; ?>"/>
    <meta property="og:description" content="<?php echo $summary; ?>"/>
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
<!-- End Open Graph Meta Tags -->

