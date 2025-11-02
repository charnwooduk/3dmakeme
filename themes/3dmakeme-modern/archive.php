<?php
/**
 * The template for displaying archive pages (shop, categories, etc.)
 */

get_header();
?>

<div class="container" style="padding: 2rem 0;">
    <?php if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) : ?>
    
        <!-- WooCommerce Archive with Sidebar -->
        <div class="woocommerce-layout" style="display: flex; gap: 2rem; align-items: flex-start;">
            
            <!-- Sidebar with Filters -->
            <aside class="woocommerce-sidebar" style="width: 300px; flex-shrink: 0;">
                
                <!-- Product Search Widget -->
                <div class="widget widget-search">
                    <h3>Search Products</h3>
                    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="search" 
                               class="search-field" 
                               placeholder="Search products..." 
                               value="<?php echo get_search_query(); ?>" 
                               name="s" />
                        <input type="hidden" name="post_type" value="product" />
                        <button type="submit" class="search-submit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 17C13.4183 17 17 13.4183 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 4.58172 17 9 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19 19L14.65 14.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Price Filter Widget with Slider -->
                <div class="widget widget-price-filter">
                    <?php
                    // Get min and max prices
                    global $wpdb;
                    $min_price = floor( $wpdb->get_var( "SELECT MIN(CAST(meta_value AS DECIMAL)) FROM $wpdb->postmeta WHERE meta_key = '_price'" ) );
                    $max_price = ceil( $wpdb->get_var( "SELECT MAX(CAST(meta_value AS DECIMAL)) FROM $wpdb->postmeta WHERE meta_key = '_price'" ) );
                    
                    // Get current filter values
                    $current_min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : $min_price;
                    $current_max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $max_price;
                    ?>
                    <h3>Filter by Price</h3>
                    <form method="get" action="<?php echo esc_url( get_pagenum_link( 1 ) ); ?>" class="price-filter-form">
                        <div class="price-slider-wrapper">
                            <div class="price-slider-amount">
                                <span class="price-label">Price: </span>
                                <span class="price-range-display">
                                    £<span id="slider-min-value"><?php echo number_format($current_min_price, 2); ?></span> - 
                                    £<span id="slider-max-value"><?php echo number_format($current_max_price, 2); ?></span>
                                </span>
                            </div>
                            
                            <div class="price-slider-container">
                                <input type="range" 
                                       id="price-slider-min" 
                                       class="price-slider" 
                                       min="<?php echo $min_price; ?>" 
                                       max="<?php echo $max_price; ?>" 
                                       value="<?php echo $current_min_price; ?>" 
                                       step="0.5" />
                                <input type="range" 
                                       id="price-slider-max" 
                                       class="price-slider" 
                                       min="<?php echo $min_price; ?>" 
                                       max="<?php echo $max_price; ?>" 
                                       value="<?php echo $current_max_price; ?>" 
                                       step="0.5" />
                            </div>
                            
                            <input type="hidden" name="min_price" id="min_price" value="<?php echo $current_min_price; ?>" />
                            <input type="hidden" name="max_price" id="max_price" value="<?php echo $current_max_price; ?>" />
                        </div>
                        
                        <?php
                        // Preserve existing query parameters
                        if ( ! empty( $_GET ) ) {
                            foreach ( $_GET as $key => $value ) {
                                if ( ! in_array( $key, array( 'min_price', 'max_price', 'paged' ) ) ) {
                                    echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
                                }
                            }
                        }
                        ?>
                        
                        <button type="submit" class="price-filter-button">Apply Filter</button>
                        
                        <?php if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) : ?>
                            <a href="<?php echo esc_url( remove_query_arg( array( 'min_price', 'max_price' ) ) ); ?>" class="price-filter-reset">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <?php
                if ( is_active_sidebar( 'woocommerce-sidebar' ) ) {
                    dynamic_sidebar( 'woocommerce-sidebar' );
                } else {
                    // Default sidebar content
                    ?>
                    <div class="widget">
                        <h3>Categories</h3>
                        <?php
                        $product_categories = get_terms( array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'exclude'    => array( get_option( 'default_product_cat' ) ),
                        ) );
                        
                        if ( ! empty( $product_categories ) ) {
                            echo '<ul style="list-style: none; padding: 0; margin: 0;">';
                            foreach ( $product_categories as $category ) {
                                $category_link = get_term_link( $category );
                                $is_current = ( is_product_category() && get_queried_object_id() == $category->term_id );
                                echo '<li style="margin-bottom: 0.75rem;">';
                                echo '<a href="' . esc_url( $category_link ) . '" style="color: ' . ($is_current ? 'var(--primary-color)' : 'var(--text-dark)') . '; text-decoration: none; display: flex; justify-content: space-between; align-items: center; font-weight: ' . ($is_current ? '600' : '400') . ';">';
                                echo '<span>' . esc_html( $category->name ) . '</span>';
                                echo '<span style="color: var(--text-gray); font-size: 0.875rem;">(' . $category->count . ')</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </aside>

            <!-- Main Content -->
            <div class="woocommerce-main-content" style="flex: 1; min-width: 0;">
                <header class="page-header" style="margin-bottom: 2rem;">
                    <?php
                    if ( is_shop() ) {
                        echo '<h1 class="page-title" style="font-size: 2.5rem; font-weight: 700; color: var(--text-dark);">Shop</h1>';
                    } else {
                        the_archive_title( '<h1 class="page-title" style="font-size: 2.5rem; font-weight: 700; color: var(--text-dark);">', '</h1>' );
                        the_archive_description( '<div class="archive-description" style="color: var(--text-gray); margin-top: 0.5rem;">', '</div>' );
                    }
                    ?>
                </header>

                <?php if ( woocommerce_product_loop() ) : ?>

                    <div class="products" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
                        <?php
                        woocommerce_product_loop_start();

                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( have_posts() ) {
                                the_post();
                                wc_get_template_part( 'content', 'product' );
                            }
                        }

                        woocommerce_product_loop_end();
                        ?>
                    </div>

                    <?php woocommerce_pagination(); ?>

                <?php else : ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

        </div>

    <?php else : ?>

        <!-- Regular archive (blog posts, etc.) -->
        <header class="page-header" style="margin-bottom: 2rem;">
            <?php
            the_archive_title( '<h1 class="page-title" style="font-size: 2.5rem; font-weight: 700; color: var(--text-dark);">', '</h1>' );
            ?>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="products" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('product'); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="product-image" style="position: relative; padding-top: 100%; overflow: hidden; background: var(--bg-light); border-radius: 1rem 1rem 0 0;">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium', array( 'style' => 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="product-info" style="padding: 1.5rem; background: white; border-radius: 0 0 1rem 1rem;">
                            <h3 class="product-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="product-description" style="color: var(--text-gray); margin: 0.75rem 0;">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                Read More
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <?php the_posts_pagination(); ?>

        <?php else : ?>
            <p>No posts found.</p>
        <?php endif; ?>

    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const minSlider = document.getElementById('price-slider-min');
    const maxSlider = document.getElementById('price-slider-max');
    const minValue = document.getElementById('slider-min-value');
    const maxValue = document.getElementById('slider-max-value');
    const minInput = document.getElementById('min_price');
    const maxInput = document.getElementById('max_price');
    
    if (minSlider && maxSlider) {
        function updateValues() {
            let min = parseFloat(minSlider.value);
            let max = parseFloat(maxSlider.value);
            
            // Ensure min is never greater than max
            if (min > max) {
                minSlider.value = max;
                min = max;
            }
            
            minValue.textContent = min.toFixed(2);
            maxValue.textContent = max.toFixed(2);
            minInput.value = min;
            maxInput.value = max;
        }
        
        minSlider.addEventListener('input', updateValues);
        maxSlider.addEventListener('input', updateValues);
    }
});
</script>

<?php
get_footer();
