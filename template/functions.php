<?php
/*
 * Enable post thumbnail support
 */
	add_theme_support( 'post-thumbnails' );

	//set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
	//add_image_size( 'banner-thumb', 566, 250, true ); // Small thumbnail size
    add_image_size( 'social-preview', 600, 315, true ); // Square thumbnail used by sharethis and facebook	


/*
 * Enable Wordpress features
 */
 	
 	// Enable styling of Admin
	//add_editor_style('css/editor-style.css');	
	 
    // Turn on menus
    register_nav_menus(
    	array(
    	  'main_menu' => 'Main Menu',
    	)
	);

    // Set WordPress theme varibles
	if ( ! isset( $content_width ) ) {
		$content_width = 720;
	}
	function set_content_width() {
		global $content_width;
		if ( is_single() ) {
			$content_width = 720;		
		} else {
			$content_width = 720;
		}
	}
	add_action( 'template_redirect', 'set_content_width' );
    
    // Excerpts for pages
    add_post_type_support( 'page', 'excerpt' );    	



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
		wp_localize_script('site', 'site_vars', 
			array(
				'themeURL' => get_template_directory_uri(),
				'homeURL'  => home_url()
			)
		);  
		*/      
        
    }
    add_action('wp_enqueue_scripts', 'custom_scripts', 10);


/*
 * Enqueue Custom Styles
 */    
    function custom_styles() {
	
		if( wp_is_mobile() ) {
			//wp_register_style('site-mobile', get_template_directory_uri() . '/css/mobile.css');			
	    	//wp_enqueue_style('site-mobile');			
		}

    }
	add_action('wp_enqueue_scripts', 'custom_styles', 10);	


/*
 * Enqueue Custom Admin Scripts
 */
	function custom_admin_scripts() {
		//wp_register_script('site.admin', get_template_directory_uri() . '/js/admin.js', 'jquery', '1.0');
		//wp_enqueue_script('site.admin');
	}
	add_action( 'admin_enqueue_scripts', 'custom_admin_scripts' ); 


/*
 * Custom Background Classes
 */    
    // Add specific CSS class by filter
    function custom_class_names($classes) {

		// Mobile Detects
		if( wp_is_mobile() ) {
			$classes[] = 'is-mobile';
		} else {
			$classes[] = 'not-mobile';
		}
		
		// Always
		$classes[] = 'site-by-funkhaus';

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
            $output .= '<span class="line-'.$count.'">'.$line.'</span> ';
        }

        return $output;
    }


/*
 * Add custom metabox to the new/edit page
 *
 * By using an underscore before the 'key' (eg: _customkey), WordPress will hide that custom field from the user in the default Custom Field meta boxes.
 *
 */
    //add_action("add_meta_boxes", "custom2015_add_metaboxes");
    function custom2015_add_metaboxes(){
        add_meta_box("custom_media_meta", "Media Meta", "custom_media_meta", "page", "normal", "low");     
    }

    // Media meta box
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

    }
    //add_action('save_post', 'custom2015_save_metabox');
    


/*
 * Next Project Link
 */
    function next_project_link($html, $exclude = null) {
        global $post;

        $current_project_id = $post->ID;
        $cache_key = 'all_pages_parent_'.$current_project_id;

        // Check for cached $pages
        $pages = get_transient( $cache_key );
        if ( empty( $pages ) ){
            $args = array(
                'post_type'         => 'page',
                'order'             => 'ASC',
                'orderby'           => 'menu_order',
                'post_parent'       => $post->post_parent,
                'fields'            => 'ids',
                'posts_per_page'    => -1,
				'post__not_in' 		=> $exclude
            );
            $pages = get_posts($args);   
            set_transient($cache_key, $pages, 30 );
        }       

        $current_key = array_search($current_project_id, $pages);

        if( isset($pages[$current_key+1]) ) {
            // Next page exists
            return '<a class="next-project" href="'.get_permalink($pages[$current_key+1]).'">'.$html.'</a>';
        }

    } 


/*
 * Previous Project Link
 */
    function previous_project_link($html, $exclude = null) {
        global $post;

        $current_project_id = $post->ID;
        $cache_key = 'all_pages_parent_'.$current_project_id;        

        // Check for cached $pages
        $pages = get_transient( $cache_key );
        if ( empty( $pages ) ){
            $args = array(
                'post_type'         => 'page',
                'order'             => 'ASC',
                'orderby'           => 'menu_order',
                'post_parent'       => $post->post_parent,
                'fields'            => 'ids',
                'posts_per_page'    => -1,
				'post__not_in' 		=> $exclude
            );
            $pages = get_posts($args);   
            set_transient($cache_key, $pages, 30 );
        }       

        $current_key = array_search($current_project_id, $pages);

        if( isset($pages[$current_key-1]) ) {
            // Previous page exists
            return '<a class="previous-project" href="'.get_permalink($pages[$current_key-1]).'">'.$html.'</a>';
        }

    }
    
/*
 * Remove <p> tags from around images
 */
	function filter_ptags_on_images($content){
	   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
	//add_filter('the_content', 'filter_ptags_on_images');
	


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

?>