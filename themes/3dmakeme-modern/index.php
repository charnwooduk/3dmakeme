<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Discover Amazing 3D Printed Creations</h1>
            <p>Custom designs, quality prints, endless possibilities</p>
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn-primary">
                    Shop Now
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Product Categories Section -->
<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<section class="categories-section" style="padding: 4rem 0; background: var(--bg-light);">
    <div class="container">
        <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Shop by Category</h2>
            <p style="color: var(--text-gray); font-size: 1.125rem;">Browse our collection of unique 3D printed items</p>
        </div>

        <div class="product-categories" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
            <?php
            $product_categories = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'exclude'    => array( get_option( 'default_product_cat' ) ), // Exclude 'Uncategorized'
            ) );

            foreach ( $product_categories as $category ) :
                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                $image_url = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : 'https://placehold.co/400x400/6366f1/ffffff?text=' . urlencode( $category->name );
                $category_link = get_term_link( $category );
                ?>
                <div class="product-category-card" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <a href="<?php echo esc_url( $category_link ); ?>" style="text-decoration: none; color: inherit;">
                        <div class="category-image" style="position: relative; padding-top: 75%; overflow: hidden; background: var(--bg-light);">
                            <img src="<?php echo esc_url( $image_url ); ?>" 
                                 alt="<?php echo esc_attr( $category->name ); ?>"
                                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="category-info" style="padding: 1.5rem; text-align: center;">
                            <h3 style="font-size: 1.5rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem;">
                                <?php echo esc_html( $category->name ); ?>
                            </h3>
                            <p style="color: var(--text-gray); margin-bottom: 1rem;">
                                <?php echo $category->count; ?> <?php echo ( $category->count === 1 ) ? 'product' : 'products'; ?>
                            </p>
                            <span class="btn btn-outline" style="display: inline-block; padding: 0.75rem 1.5rem; border: 2px solid var(--primary-color); color: var(--primary-color); border-radius: 0.5rem; font-weight: 500; transition: all 0.3s ease;">
                                View Category
                            </span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<section class="products-section" style="padding: 4rem 0;">
    <div class="container">
        <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Featured Products</h2>
            <p style="color: var(--text-gray); font-size: 1.125rem;">Check out our most popular 3D printed items</p>
        </div>

        <div class="products" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $products = new WP_Query( $args );
            
            if ( $products->have_posts() ) :
                while ( $products->have_posts() ) : $products->the_post();
                    global $product;
                    ?>
                    <div class="product" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="product-image" style="position: relative; padding-top: 100%; overflow: hidden; background: var(--bg-light);">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', array( 'style' => 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                                <?php else : ?>
                                    <img src="https://placehold.co/400x400/6366f1/ffffff?text=3D+Print" alt="<?php the_title(); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="product-info" style="padding: 1.5rem;">
                            <h3 class="product-title" style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.75rem;">
                                <a href="<?php the_permalink(); ?>" style="color: var(--text-dark); text-decoration: none;"><?php the_title(); ?></a>
                            </h3>
                            <div class="product-price" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color); margin-bottom: 1rem;">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn add-to-cart-button" style="width: 100%; text-align: center; display: block; padding: 0.75rem; background: var(--primary-color); color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: background 0.3s ease;">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn-primary">
                View All Products
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
