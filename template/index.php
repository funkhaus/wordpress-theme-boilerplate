<?php get_header(); ?>

    <main class="fallback">
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="entry">
                    <?php the_content(); ?>
                    <?php edit_post_link('+ Edit', '<p>', '</p>'); ?>
                </div>

        	</div>

        <?php endwhile; ?>
        <?php endif; ?>
    </main>

<?php get_footer(); ?>
