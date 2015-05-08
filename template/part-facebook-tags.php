<?php    
    /*
     * Build the image URL
     */    
        $fallbackImage = get_template_directory_uri() . "/screenshot.png";
        $thumbSize = 'social-preview';

        if( is_single() || is_page() ) {
            // If page or is single, set the shared image to the post thumbnail.
            $image_id = get_post_thumbnail_id();

            if( empty($image_id) ) {
                $sharedImage = $fallbackImage;
            } else {
                $image_url = wp_get_attachment_image_src($image_id, $thumbSize);
                $sharedImage = $image_url[0];
            }

        } else {
            $sharedImage = $fallbackImage;
        }

        // Image fallback
        if( empty($sharedImage) ) {
            $sharedImage = $fallbackImage;        
        }


    /*
     * This builds the summary text.
     */             
        if( is_single() || is_page() ) {
            // If page has no children or is single, set the summary to the excerpt.
            $summary = get_the_excerpt();
            if( empty($summary) ) {
                $summary = wp_trim_excerpt( strip_shortcodes($post->post_content) );
            }
            
            // Fallback
            if( empty($summary) ) {
                $summary = get_bloginfo('description');
            }
        } else {
            $summary = get_bloginfo('description');
        }
        // Remove any links or tags from summary
        $summary = strip_tags($summary);
        $summary = esc_attr($summary);
        // Remove line breaks
        $summary = preg_replace('!\s+!', ' ', $summary);
?>

<!-- Start Open Graph Meta Tags -->
    <meta property="og:title" content="<?php wp_title(''); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI']; ?>" />
    <meta property="og:image" content="<?php echo $sharedImage; ?>" />
    <meta property="og:description" content="<?php echo $summary; ?>" />
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
<!-- End Open Graph Meta Tags -->
