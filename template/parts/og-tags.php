<?php
    // Defaults
    $shared_image = get_template_directory_uri() . "/screenshot.png";
    $summary = get_bloginfo('description');
    $url = get_permalink($post->ID);

    // Build the image URL
    if( is_single() or is_page() ) {
        // If page or is single, set the shared image to the post thumbnail.
        $image_id = get_post_thumbnail_id();

        if( !empty($image_id) ) {
            $image_url = wp_get_attachment_image_src($image_id, 'social-preview');
            $shared_image = $image_url[0];
        }
    }


    // Builds the summary text.
    if( is_single() or is_page() ) {
        // If page has no children or is single, set the summary to the excerpt.
        $summary = get_the_excerpt();
        if( empty($summary) ) {
            $summary = wp_trim_excerpt( strip_shortcodes($post->post_content) );
        }
    }

    // Remove any links, tags or line breaks from summary
    $summary = strip_tags($summary);
    $summary = esc_attr($summary);
    $summary = preg_replace('!\s+!', ' ', $summary);

    // Build permalink URL
    if( is_front_page() or is_home() ) {
        $url = get_bloginfo('url');
    }
?>

<!-- Start Open Graph Meta Tags -->
    <meta property="og:title" content="<?php wp_title(''); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $url; ?>" />
    <meta property="og:image" content="<?php echo $shared_image; ?>" />
    <meta property="og:description" content="<?php echo $summary; ?>" />
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
<!-- End Open Graph Meta Tags -->
