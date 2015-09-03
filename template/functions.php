<?php
/*
 * Setup WordPress
 */
	function custom_wordpress_setup() {

		// Enable tags for Pages (@see: https://wordpress.org/support/topic/enable-tags-screen-for-pages#post-29500520
		//register_taxonomy_for_object_type('post_tag', 'page');

	    // Enable excerpts for pages
	    add_post_type_support('page', 'excerpt');

	}
	add_action('init', 'custom_wordpress_setup');

/*
 * Setup theme
 */
	function custom_theme_setup() {

		// Enable post thumbnail support
		add_theme_support( 'post-thumbnails' );	
		//set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
		//add_image_size( 'banner-thumb', 566, 250, true ); // Small thumbnail size
	    add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used by sharethis and facebook

	    // Turn on menus
		add_theme_support('menus');

	}
	add_action( 'after_setup_theme', 'custom_theme_setup' );

/*
 * Disable Woo CSS
 */
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/*
 * Handle content width edge cases
 */
	function set_content_width() {
		global $content_width;
		if ( is_single() ) {
			$content_width = 960;		
		} else {
			$content_width = 960;
		}
	}
	add_action( 'template_redirect', 'set_content_width' );



/*
 * Enqueue Custom Scripts
 */
    function custom_scripts() {
        //wp_register_script('site', get_template_directory_uri() . '/js/site.js', 'jquery', '1.0');
        //wp_register_script('cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', 'jquery', '2.1.5');
		//wp_register_script('gallery', get_template_directory_uri() . '/js/gallery2012.js', 'jquery', '1.0');
		//wp_register_script('masonry', get_template_directory_uri() . '/js/jquery.masonry.min.js', 'jquery', '1.0');		
		//wp_register_script('carouFredSel', get_template_directory_uri() . '/js/jquery.carouFredSel-6.2.1-packed.js', 'jquery', '1.0');				
		//wp_register_script('vimeo-api', 'http://a.vimeocdn.com/js/froogaloop2.min.js', 'jquery', '1.0');
        //wp_register_script('vimeoplayer', get_template_directory_uri() . '/js/vimeoplayer2013.js', 'jquery', '1.0');		
        //wp_register_script('infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', 'jquery', '1.0');	
      
        //wp_enqueue_script('jquery');
        //wp_enqueue_script('carouFredSel', 'jquery');    	        
        //wp_enqueue_script('masonry', 'jquery');
        //wp_enqueue_script('cycle2', 'jquery');                
        //wp_enqueue_script('infinitescroll', 'jquery');          
        //wp_enqueue_script('vimeo-api', 'jquery');
        //wp_enqueue_script('vimeoplayer', 'jquery');
        //wp_enqueue_script('gallery', 'jquery');
        //wp_enqueue_script('site', 'jquery');         

        // Setup JS variables in scripts        
		/*
		wp_localize_script('site', 'siteVars', array(
    		'themeURL' => get_template_directory_uri(),
    		'homeURL'  => home_url()
        ));
		*/      
        
    }
    add_action('wp_enqueue_scripts', 'custom_scripts', 10);


/*
 * Enqueue Custom Styles
 */    
    function custom_styles() {
		//wp_register_style('site-mobile', get_template_directory_uri() . '/css/mobile.css');
		//wp_enqueue_style('site-mobile');
    }
	add_action('wp_enqueue_scripts', 'custom_styles', 10);


/*
 * Enqueue Custom Admin Scripts
 */
	function custom_admin_scripts() {
		//wp_register_script('site-admin', get_template_directory_uri() . '/js/admin.js', 'jquery', '1.0');
		//wp_enqueue_script('site-admin');
	}
	add_action( 'admin_enqueue_scripts', 'custom_admin_scripts' );


/*
 * Custom Background Classes
 */    
    // Add specific CSS class by filter
    function custom_class_names($classes) {

		// Add classes
		switch (true) {
		    case is_page('contact') :
				$classes[] = 'contact';
				break;
		}

		// Mobile Detects
		if( wp_is_mobile() ) {
			$classes[] = 'is-mobile';
		} else {
			$classes[] = 'not-mobile';
		}

    	return $classes;
    }
    add_filter('body_class','custom_class_names');    



/*
 * Style login page and dashboard
 */
	// Style the login page
	function custom_loginpage_logo_link($url)
	{
	     // Return a url; in this case the homepage url of wordpress
	     return get_bloginfo('url');
	}
	function custom_loginpage_logo_title($message)
	{
	     // Return title text for the logo to replace 'wordpress'; in this case, the blog name.
	     return get_bloginfo('name');
	}
	function custom_loginpage_styles()
	{
        wp_enqueue_style( 'login_css', get_template_directory_uri() . '/css/login.css' );        
	}
	function custom_admin_styles() {
        wp_enqueue_style('admin-stylesheet', get_template_directory_uri() . '/css/admin.css');
	}	
	// Hook in
	add_filter('login_headerurl','custom_loginpage_logo_link');
	add_filter('login_headertitle','custom_loginpage_logo_title');
	add_action('login_head','custom_loginpage_styles');
    add_action('admin_print_styles', 'custom_admin_styles');    



/*
 * Add post thumbnail into RSS feed
 */
    function rss_post_thumbnail($content) {
        global $post;
        
        if(has_post_thumbnail($post->ID)) {
            $content = '<p><a href='.get_permalink($post->ID).'>'.get_the_post_thumbnail($post->ID).'</a></p>'.$content;
            
        } else {
        
            // get first attached image
            $args = array(
        		'post_type' 		=> 'attachment',
        		'posts_per_page' 	=> 1,
        		'post_status' 		=> 'inherit',
        		'order'				=> 'ASC',
        		'orderby' 			=> 'menu_order',
        		'post_mime_type' 	=> 'image',
        		'post_parent' 		=> $post->ID
        	);
            $attachments = get_posts($args);
            
            if ($attachments) {
            	foreach ($attachments as $attachment) {
                    $firstImage = wp_get_attachment_image_src($attachment->ID, array(400,225));
                    $content = '<p><a href='.get_permalink($post->ID).'><img src="'.$firstImage[0].'"/></a></p>'.$content;
				}
			}

		}

		return $content;
	}
	add_filter('the_excerpt_rss', 'rss_post_thumbnail');

/*
 * Custom conditional function. Used to get the parent and all it's child.
 */
    function is_tree($post_id) {
    	global $post;
    	
    	$ancestors = get_post_ancestors($post);
    	
    	if( is_page() && (is_page($post_id) || $post->post_parent == $post_id || in_array($post_id, $ancestors)) ) {
    		return true;
    	} else {
    		return false;
    	}
    }



/*
 * Custom conditional function. Used to test if current page has children.
 */
    function has_children($post_id = false, $post_type = 'page') {
    	// Defaults
    	if( !$post_id ) {
	    	global $post;
	    	$post_id = $post->ID;
    	}

    	// Check if the post/page has a child
        $args = array(
        	'post_parent' 		=> $post_id,
        	'post_type'			=> $post_type,
        	'posts_per_page'	=> 1
        );
        $children = get_posts($args);
		
        if( count( $children ) !== 0 ) { 
			// Has Children
	        return true; 
	    } else { 
		    // No children
		    return false; 
		}
    }




/*
 * Split and wrap title
 */
    function get_split_title($postID = false) {
    	if( !$postID ) {
	    	global $post;
	    	$postID = $post->ID;
    	}
    	
        $title = get_the_title($postID);
        $lines = explode(' &#8211; ', $title);
        $output = false;
        $count = 0;

        foreach( $lines as $line ) {
            $count++;
            $output .= '<span class="line line-'.$count.'">'.$line.'</span> ';
        }

        return $output;
    }


/*
 * Add custom metabox to the new/edit page
 */
    function custom2015_add_metaboxes(){

		// add_meta_box('custom_media_meta', 'Media Meta', 'custom_media_meta', 'page', 'normal', 'low');
		// add_meta_box("custom_second_featured_image", "Second Featured Image", "custom_second_featured_image", "page", "side", "low");

    }
	add_action('add_meta_boxes', 'custom2015_add_metaboxes');

	// Build media meta box
	function custom_media_meta() {
		global $post;

		?>
        	<div class="custom-meta">
				<label for="video-url">Enter the video URL for this page:</label>
				<input id="video-url" class="short" title="This is needed for all video pages" name="_custom_video_url" type="text" value="<?php echo $post->_custom_video_url; ?>">
				<br/>

        	</div>

		<?php
	}

    // Second featured image uploader (requires changes to admin.js too).
    // @see: https://codex.wordpress.org/Javascript_Reference/wp.media
    function custom_second_featured_image(){
        global $post;
        
        // Meta key (need to update the save_metabox function below to reflect this too!)
        $meta_key = '_second_post_thumbnail';
        
        // Get WordPress' media upload URL
        $upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
        
        // See if there's a media id already saved as post meta
        $image_id = get_post_meta( $post->ID, $meta_key, true );
        
        // Get the image src
        $image_src = wp_get_attachment_image_src( $image_id, 'post-thumbnail' );
        
        // For convenience, see if the array is valid
        $has_image = is_array( $image_src );
        
        ?>
        
        <div class="custom-meta custom-image-uploader">

            <!-- A hidden input to set and post the chosen image id -->
            <input class="custom-image-id" name="<?php echo $meta_key; ?>" type="hidden" value="<?php echo $image_id; ?>" />        
        
            <!-- Image container, which is manipulated with js -->
            <div class="custom-image-container">
                <?php if ( $has_image ) : ?>
                    <img src="<?php echo $image_src[0] ?>"/>
                <?php endif; ?>
            </div>
            
            <!-- Add & remove image links -->
            <p class="hide-if-no-js">
                <a class="upload-custom-image <?php if ( $has_image  ) { echo 'hidden'; } ?>" href="<?php echo $upload_link ?>">
                    <?php _e('Set banner ad') ?>
                </a>
                <a class="delete-custom-image <?php if ( ! $has_image  ) { echo 'hidden'; } ?>" href="#">
                    <?php _e('Remove banner ad') ?>
                </a>
            </p>

        </div>

        <?php        
    }

/*
 * Save the metabox vaule
 */
    function custom2015_save_metabox($post_id){

        // check autosave
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if( isset($_POST["_custom_video_url"]) ) {
	        update_post_meta($post_id, "_custom_video_url", $_POST["_custom_video_url"]);
        }
        if( isset($_POST["_second_post_thumbnail"]) ) {
	        //update_post_meta($post_id, "_second_post_thumbnail", $_POST["_second_post_thumbnail"]);
        }

    }
    add_action('save_post', 'custom2015_save_metabox');


/*
 * Next Project
 */
	function get_next_page($exclude = null, $loop = true) {
		global $post;

		// set current post type
		$post_type = get_post_type( $post );

		// Set vars
		$current_project_id = $post->ID;
		$cache_key = 'all_pages_parent_'.$current_project_id;

		// Check for cached $pages
		$pages = get_transient( $cache_key );
		if ( empty( $pages ) ){
			$args = array(
				'post_type'         => $post_type,
				'order'             => 'ASC',
				'orderby'           => 'menu_order',
				'post_parent'       => $post->post_parent,
				'fields'            => 'ids',
				'posts_per_page'    => -1,
				'post__not_in' 		=> $exclude
			);
			$pages = get_posts($args);

			// Save cache
			set_transient($cache_key, $pages, 30 );
        }

		$current_key = array_search($current_project_id, $pages);

		$output = false;
		if( isset($pages[$current_key+1]) ) {

			// Next page exists
			$output = $pages[$current_key+1];

		// No next page, should we loop to first?
		} elseif ( $loop ) {

			// Get first page
			$output = $pages[0];
		}

		return $output;
	}


/*
 * Previous Project
 */
    function get_previous_page($exclude = null, $loop = true) {
		global $post;

		// set current post type
		$post_type = get_post_type( $post );

		// Set vars
        $current_project_id = $post->ID;
        $cache_key = 'all_pages_parent_'.$current_project_id;        

        // Check for cached $pages
        $pages = get_transient( $cache_key );
        if ( empty( $pages ) ){
			$args = array(
				'post_type'         => $post_type,
				'order'             => 'ASC',
				'orderby'           => 'menu_order',
				'post_parent'       => $post->post_parent,
				'fields'            => 'ids',
				'posts_per_page'    => -1,
				'post__not_in' 		=> $exclude
			);
			$pages = get_posts($args);

			// Save cache
			set_transient($cache_key, $pages, 30 );
        }       

        $current_key = array_search($current_project_id, $pages);
		$output = false;

        if( isset($pages[$current_key-1]) ) {
            // Previous page exists
            $output = $pages[$current_key-1];

		// No previous page, should we loop to last?
        } elseif ( $loop ) {

			// Get last page
			$output = $pages[count($pages)-1];
        }

		return $output;
    }


/*
 * Allow subscriber to see Private posts/pages
 */    
	function add_theme_caps() {
	    // Gets the author role
	    $role = get_role('subscriber');
	
	    // Add capabilities  
	    $role->add_cap( 'read_private_posts' );
		$role->add_cap( 'read_private_pages' );
	}
	//add_action( 'switch_theme', 'add_theme_caps');



/*
 * Disable Rich Editor on certain pages
 */
	function disabled_rich_editor($allow_rich_editor) {
		global $post;

		if($post->post_name == 'contact') {
			return false;		    
		}
		return $allow_rich_editor;
	}
	//add_filter( 'user_can_richedit', 'disabled_rich_editor');

/*
 * Enqueue Custom Gallery
 */
	function custom_gallery($atts) {
		if ( !is_admin() ) {
			include('part-gallery.php');
		}
		return $output;
	}
	//add_shortcode('gallery', 'custom_gallery');

/*
 * Check if functions-store file exists, if so include it
 */
	if ( $store_funcs = locate_template('store/functions-store.php') ) {
		include( $store_funcs );
	}

?>