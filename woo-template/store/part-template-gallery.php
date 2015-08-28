<?php global $product; ?>

<div class="featured-image">
	<?php the_post_thumbnail('large'); ?>
</div>

<div class="gallery">
	<?php $gallery_ids = $product->get_gallery_attachment_ids(); ?>
	<?php foreach ($gallery_ids as $gallery_id): ?>

		<?php echo wp_get_attachment_image($gallery_id, 'thumbnail'); ?>

	<?php endforeach; ?>
</div>