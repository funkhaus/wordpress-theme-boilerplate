<?php

/*
 * Enqueue Store-Related scripts
 */
	function custom_store_scripts() {
		wp_register_script('store-script', get_template_directory_uri() . '/js/store.js', 'jquery', '1.0');
		wp_register_script('store-checkout', get_template_directory_uri() . '/js/store-checkout.js', 'jquery', '1.0');

		wp_localize_script('store-script', 'site_vars', 
			array(
				'ajaxURL' => admin_url('admin-ajax.php')
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
		wp_register_script('store-admin', get_template_directory_uri() . '/js/store-admin.js', 'jquery', '1.0');
		wp_enqueue_script('store-admin');
	}
	add_action( 'admin_enqueue_scripts', 'store_admin_scripts' );

/*
 * Add any store-related meta-boxes
 */
	function store_add_metaboxes(){
		add_meta_box('product_story_meta', 'Product Story', 'product_story_meta', 'product', 'normal', 'core');
		add_meta_box('store_second_featured_image', 'Second Image', 'store_second_featured_image', 'product', 'side', 'core');
	}
	add_action('add_meta_boxes', 'store_add_metaboxes');

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
	add_action('save_post', 'store_save_metaboxes');

/*
 * Easy wrapper function for listing available variations
 */
	function show_variations_in_stock($product, $param){

		$output = '';
		if( $product->product_type == 'variable' ) {
			$variations = $product->get_available_variations();

			$filtered = array();
			foreach ( $variations as $variation ){

				if ( array_key_exists('attribute_pa_' . $param, $variation["attributes"]) ){
					$filtered[$variation["attributes"]['attribute_pa_' . $param]] = $variation;
				}

			}

			if ( !empty($filtered) ){

				$output .= '<ul>';

					foreach ( $filtered as $filtered_key => $filtered_var ){

						$class = '';
						if ( ! $filtered_var['is_purchasable'] ) $class = 'unavailable';

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
 * AJAX Endpoint Functions
 */
	function ajax_get_mini_cart() {
		woocommerce_mini_cart();
		exit;
	}

	add_action( 'wp_ajax_get_minicart', 'ajax_get_mini_cart' );
	add_action( 'wp_ajax_nopriv_get_minicart', 'ajax_get_mini_cart' );

/*
 * Change required status of certain
 */
	function fh_change_required_fields( $fields ) {

		$fields['phone']['required'] = false;
		return $fields;

	}
	add_filter( 'woocommerce_default_address_fields', 'fh_change_required_fields' );

/*
 * Function to check if user has elected to be a guest
 */
	if ( !function_exists('is_store_guest') ){

		function is_store_guest(){

			$out = false;

			if ( isset($_REQUEST['guest']) ){
				$out = $_REQUEST['guest'];
			}

			if(isset($_COOKIE['fh_guest_checkout'])){
				$out = $_COOKIE['fh_guest_checkout'];
			}

			return $out;
		}

	}

/*
 * If guest parameter is set, add a cookie
 */
	function store_set_guest_cookie(){

		if ( isset($_REQUEST['guest']) && $_REQUEST['guest'] ){

			// set for 10 days
			setcookie('fh_guest_checkout', 1, time() + 60 * 60 * 24 * 10);

		}

	}
	add_action('init', 'store_set_guest_cookie');

	// remove sidebar from site
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);