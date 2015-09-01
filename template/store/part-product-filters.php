<?php if (is_woocommerce()): ?>

	<div class="filters">

		<div class="title">Filter +</div>

		<?php
			$menuArgs = array(
				'container'         => 'false',
				'menu'              => 'Filter Menu',
				'menu_id'           => 'filter-menu',
				'menu_class'        => 'filter-menu menu'
			);
			wp_nav_menu($menuArgs); 
		?>

	</div>

<?php endif; ?>