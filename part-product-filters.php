<?php if (is_woocommerce()): ?>

	<div class="filters">

		<div class="title">Filter +</div>

		<ul class="clothing-filter product-cat-filter">

			<?php

				$args = array(
					'show_option_all'    => '',
					'orderby'            => 'name',
					'style'              => 'list',
					'hide_empty'         => 0,
					'use_desc_for_title' => 1,
					'child_of'           => 9,
					'title_li'           => __( 'Clothing' ),
					'echo'               => 1,
					'taxonomy'           => 'product_cat'
				);
				wp_list_categories( $args );

			?>

		</ul>

		<ul class="brand-filter product-cat-filter">
		
			<?php

				$args = array(
					'show_option_all'    => '',
					'orderby'            => 'name',
					'style'              => 'list',
					'hide_empty'         => 0,
					'use_desc_for_title' => 1,
					'child_of'           => 16,
					'title_li'           => __( 'Brands' ),
					'echo'               => 1,
					'taxonomy'           => 'product_cat'
				);
				wp_list_categories( $args );

			?>

		</ul>

	</div>

<?php endif; ?>