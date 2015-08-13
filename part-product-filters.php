<?php if (is_woocommerce()): ?>

	<div class="filters">

		<div class="title">Filter +</div>

		<div class="clothing-filter">

			<div class="label">Clothing</div>

			<ul>
				<?php $clothing_cat = get_category(9);
	
					$args = array(
						'type'                     => 'product',
						'parent'                   => 9,
						'orderby'                  => 'name',
						'taxonomy'                 => 'product_cat'
					);
	
					$cats = get_categories( $args );
	
					foreach ( $cats as $cat ) :
				?>

					<li><?php echo $cat->name; ?></li>

				<?php endforeach; ?>

			</ul>

		</div>

		<div class="brand-filter">

			<div class="label">Brands</div>

			<?php $brand_cat = get_category(16); ?>

		</div>

	</div>

<?php endif; ?>