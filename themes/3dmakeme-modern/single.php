<?php get_header(); ?>

<div class="container" style="padding: 2rem 0;">
    <?php
    while ( have_posts() ) {
        the_post();
        
        if ( class_exists( 'WooCommerce' ) && is_product() ) {
            // WooCommerce product
            woocommerce_content();
        } else {
            // Regular post
            ?>
            <article <?php post_class(); ?>>
                <h1 style="font-size: 2.5rem; margin-bottom: 1rem;"><?php the_title(); ?></h1>
                <div style="max-width: 800px; margin: 0 auto;">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        }
    }
    ?>
</div>

<?php get_footer(); ?>
