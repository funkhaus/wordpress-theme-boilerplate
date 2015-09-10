<?php

/*
 * Declare WooCommerce Support
 */
	add_action( 'after_setup_theme', 'woocommerce_support' );
	function woocommerce_support() {
	    add_theme_support( 'woocommerce' );
	}


/*
 * Remove WooCommerce sidebar from site
 */
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);


/*
 * Enqueue Store-Related scripts
 */
	function custom_store_scripts() {
		wp_register_script('store-script', get_template_directory_uri() . '/store/js/main.js', 'jquery', '1.0');
		wp_register_script('store-checkout', get_template_directory_uri() . '/store/js/checkout.js', 'jquery', '1.0');

		wp_localize_script('store-script', 'siteVars',
			array(
				'ajaxURL'           => admin_url('admin-ajax.php'),
				'categoryBaseURL'   => trailingslashit( get_bloginfo('url') ) . trailingslashit ( store_get_product_category_base() )
			)
		);

		wp_enqueue_script('store-script', 'jquery');
		wp_enqueue_script('store-checkout', 'jquery');
	}
	add_action('wp_enqueue_scripts', 'custom_store_scripts', 10);


/*
 * Enqueue store Admin Script
 */
	function store_admin_scripts() {
		wp_register_script('store-admin', get_template_directory_uri() . '/store/js/admin.js', 'jquery', '1.0');
		wp_enqueue_script('store-admin');
	}
	add_action( 'admin_enqueue_scripts', 'store_admin_scripts' );


/*
 * Store custom styles
 */
    function custom_store_styles() {
		wp_register_style('store-styles', get_template_directory_uri() . '/store/css/store.css');
		wp_enqueue_style('store-styles');
    }
	add_action('wp_enqueue_scripts', 'custom_store_styles', 10);


/*
 * Add any store-related meta-boxes
 */
	function store_add_metaboxes(){
		add_meta_box('product_story_meta', 'Product Story', 'product_story_meta', 'product', 'normal', 'core');
		add_meta_box('store_second_featured_image', 'Second Image', 'store_second_featured_image', 'product', 'side', 'core');
	}
	//add_action('add_meta_boxes', 'store_add_metaboxes');

	function product_story_meta(){
		global $post;
		$story = get_post_meta($post->ID, '_product_story', true);
		wp_editor( $story, '_product_story', array ('textarea_rows' => 5,'media_buttons' => false));
	}

	// Second featured image uploader (requires changes to admin.js too).
	// @see: https://codex.wordpress.org/Javascript_Reference/wp.media
	function store_second_featured_image(){
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
				    <?php _e('Set Image') ?>
				</a>
				<a class="delete-custom-image <?php if ( ! $has_image  ) { echo 'hidden'; } ?>" href="#">
				    <?php _e('Remove Image') ?>
				</a>
			</p>

		</div>

		<?php
	}


/*
 * Save metabox values
 */
	function store_save_metaboxes($post_id){

		// check autosave
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if( isset($_POST["_product_story"]) ) {
			update_post_meta($post_id, "_product_story", $_POST["_product_story"]);
		}
		if( isset($_POST["_product_story"]) ) {
			update_post_meta($post_id, "_product_story", $_POST["_product_story"]);
		}

	}
	//add_action('save_post', 'store_save_metaboxes');


/**
 * Easy wrapper function for listing available variations
 * @param mixed $product A product ID or object
 * @param string $param The slug of the attribute you want to dispaly (size, color, etc).
 */
	function show_variations_in_stock($product = false, $param){

		// init
		$output = '';
		$product = wc_get_product($product);

		// only do something for variable type products
		if( $product->product_type == 'variable' ) {

			// load variations
			$variations = $product->get_available_variations();

			// init blank array, loop
			$filtered = array();
			foreach ( $variations as $variation ){

				// if any variants have $param variations, add them to output array
				if ( array_key_exists('attribute_pa_' . $param, $variation["attributes"]) ){
					$filtered[$variation["attributes"]['attribute_pa_' . $param]] = $variation;
				}

			}

			// if any variants came back...
			if ( !empty($filtered) ){

				$output .= '<ul>';

					// loop variants
					foreach ( $filtered as $filtered_key => $filtered_var ){

						// set availability class
						$class = '';
						if ( ! $filtered_var['is_purchasable'] ) {
                            $class = 'unavailable';
						}

						// build html
						$output .= '<li class="' . $class . '">';

							$output .= $filtered_key;

						$output .= '</li>';

					}

				$output .= '</ul>';

			}

		}

		return $output;
	}


/*
 * Change required status of certain fileds
 */
	function fh_change_required_fields( $fields ) {

		// phone number should not be required
		$fields['phone']['required'] = false;
		return $fields;

	}
	add_filter( 'woocommerce_default_address_fields', 'fh_change_required_fields' );



/*
 * Function to check if user has elected to checkout as guest
 */
	if ( !function_exists('is_store_guest') ){

		function is_store_guest(){

			// init
			$out = false;

			// check request
			if ( isset($_REQUEST['guest']) ){
				$out = $_REQUEST['guest'];
			}

			// check cookies
			if(isset($_COOKIE['fh_guest_checkout'])){
				$out = $_COOKIE['fh_guest_checkout'];
			}

			return $out;
		}

	}


/*
 * If guest parameter is set add a cookie
 */
	function store_set_guest_cookie(){

		// check for parameter
		if ( isset($_REQUEST['guest']) && $_REQUEST['guest'] ){

			// set for 10 days
			setcookie('fh_guest_checkout', 1, time() + 60 * 60 * 24 * 10);

		}

	}
	add_action('init', 'store_set_guest_cookie');



/*
 * Check for custom Stripe templating file and if it exists include it
 */
	if ( $stripe_template = locate_template('store/stripe-template-class.php') ){
		include( $stripe_template );
	}



/*
 * AJAX Endpoint Functions.
 * @see: https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
 */
	function ajax_get_mini_cart() {
		woocommerce_mini_cart();
		exit;
	}
	add_action( 'wp_ajax_get_minicart', 'ajax_get_mini_cart' );
	add_action( 'wp_ajax_nopriv_get_minicart', 'ajax_get_mini_cart' );


/*
 * Return the product category slug
 */
	function store_get_product_category_base() {
        $product_category_slug = get_option('woocommerce_product_category_slug');
        if(empty($product_category_slug)) {
            $product_category_slug = 'product-category';
        }
        return $product_category_slug;
	}	
