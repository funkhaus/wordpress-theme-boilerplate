<?php

/*
 * Enqueue Store-Related scripts
 */
	function custom_store_scripts() {
		wp_register_script('store-script', get_template_directory_uri() . '/js/store.js', 'jquery', '1.0');

		wp_localize_script('store-script', 'site_vars', 
			array(
				'ajaxURL' => admin_url('admin-ajax.php')
			)
		);

		wp_enqueue_script('store-script', 'jquery');
	}
	add_action('wp_enqueue_scripts', 'custom_store_scripts', 10);

/*
 * Add any store-related meta-boxes
 */
	function store_add_metaboxes(){
		add_meta_box('product_story_meta', 'Product Story', 'product_story_meta', 'product', 'normal', 'core');
	}
	add_action('add_meta_boxes', 'store_add_metaboxes');

	function product_story_meta(){
		global $post;
		$story = get_post_meta($post->ID, '_product_story', true);
		wp_editor( $story, '_product_story', array ('textarea_rows' => 5,'media_buttons' => false));
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

