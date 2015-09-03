<?php
    /*
     * Build out gallery
     */
        global $post;

        // This turns the atts into $vars
        extract(shortcode_atts(array(
            'order'   => 'ASC',
            'ids'     => '',
        ), $atts));

        // Set default gallery args
        $args = array(
            'post_type'       => 'attachment',
            'post_mime_type'  => 'image',       
            'posts_per_page'  => -1,
            'post_status'     => 'inherit',
            'orderby'         => 'menu_order',
            'order'           => 'ASC',     
            'post_parent'     => $post->post_parent,
            'post__in'        => null
        );

        // Should we get all images attached to the page, or just those specified?
        if( !empty($ids) ) {
            $args['post__in']       = explode(',', $ids);
            $args['post_parent']    = null;
            $args['orderby']        = 'post__in';
        }

        // Get images
        $attachments = get_posts($args);
        $total = count($attachments);   

        // Return false if no images attached
        if($total == 0) {
            return false;
        }

        // Start building the output. Use output buffering for speed!
        ob_start();
?>

    <div class="gallery">

        <div class="gallery-controls">
            <div class="browse prev">
                <img src="<?php echo get_template_directory_uri(); ?>/images/arrow-left.svg" class="svg arrow arrow-left" />
            </div>

            <div class="browse next">
                <img src="<?php echo get_template_directory_uri(); ?>/images/arrow-right.svg" class="svg arrow arrow-right" />
            </div>
        </div>

        <?php foreach ($attachments as $attachment) : ?>
			
		    <?php 
		        // Get image background URL
		        $attachmentData = wp_get_attachment_image_src( $attachment->ID, 'gallery-stage');
		        $imageURL = $attachmentData[0];
		    ?>
			
            <div class="gallery-item gallery-image cover" style="background-image: url('<?php echo $imageURL; ?>');">
                
                <?php echo wp_get_attachment_image( $attachment->ID, 'gallery-stage'); ?>
                
                <div class="caption">
                    <?php echo $attachment->post_excerpt; ?>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

<?php
    $output = ob_get_clean();
    return $output;
?>