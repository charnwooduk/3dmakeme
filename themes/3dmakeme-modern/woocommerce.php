<?php
/**
 * The template for displaying WooCommerce pages
 */

get_header();
?>

<div class="container" style="padding: 2rem 0;">

    <?php if ( is_product_category() ) : ?>
        <?php
        $term = get_queried_object();
        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
        $image_url = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : '';
        ?>

        <!-- Category Banner -->
        <div class="category-banner" style="
            position: relative;
            margin-bottom: 3rem;
            border-radius: 1rem;
            overflow: hidden;
            min-height: 175px;
            display: flex;
            align-items: center;
        ">
            <?php if ( $image_url ) : ?>
                <!-- Background image on right 60% -->
                <div style="
                    position: absolute;
                    top: 0;
                    right: 0;
                    width: 75%;
                    height: 100%;
                    background-image: url('<?php echo esc_url( $image_url ); ?>');
                    background-size: cover;
                    background-position: center;
                    z-index: 1;
                "></div>
            <?php endif; ?>

            <!-- Full width gradient overlay -->
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to right, rgba(99, 102, 241, 0.95) 0%, rgba(159, 87, 197, 0.5) 50%, rgba(236, 72, 153, 0.2) 100%);
                z-index: 2;
            "></div>

            <div class="category-banner-content" style="
                position: relative;
                z-index: 3;
                padding: 2rem;
                color: white;
                width: 100%;
            ">
                <h1 style="
                    font-size: 2.25rem;
                    font-weight: 700;
                    color: white;
                    margin-bottom: 1rem;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                "><?php echo esc_html( $term->name ); ?></h1>

                <?php if ( $term->description ) : ?>
                    <div style="
                        font-size: 1.1rem;
                        color: rgba(255,255,255,0.95);
                        max-width: 600px;
                        line-height: 1.6;
                        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
                    "><?php echo wpautop( $term->description ); ?></div>
                <?php endif; ?>

                <div style="
                    margin-top: 1.5rem;
                    font-size: 1rem;
                    color: rgba(255,255,255,0.9);
                    font-weight: 500;
                ">
                    <?php echo $term->count; ?> <?php echo ( $term->count === 1 ) ? 'Product' : 'Products'; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="woocommerce-layout" style="display: flex; gap: 2rem; align-items: flex-start;">

        <!-- Sidebar on Left with Filters -->
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

            <!-- Price Filter Widget with Auto-Apply Slider -->
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
                <form method="get" action="<?php echo esc_url( get_pagenum_link( 1 ) ); ?>" class="price-filter-form" id="price-filter-form">
                    <div class="price-slider-wrapper">
                        <div class="price-slider-header">
                            <span class="price-range-display">
                                £<span id="slider-min-value"><?php echo number_format($current_min_price, 2); ?></span> -
                                £<span id="slider-max-value"><?php echo number_format($current_max_price, 2); ?></span>
                            </span>
                            <?php if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( array( 'min_price', 'max_price' ) ) ); ?>" class="price-filter-reset-inline">Reset</a>
                            <?php endif; ?>
                        </div>

                        <div class="price-slider-container">
                            <div class="price-slider-track"></div>
                            <div class="price-slider-range" id="price-slider-range"></div>
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
                </form>
            </div>

            <?php
            if ( is_active_sidebar( 'woocommerce-sidebar' ) ) {
                dynamic_sidebar( 'woocommerce-sidebar' );
            } else {
                // Default sidebar content if no widgets
                ?>
                <div class="widget">
                    <h3>Categories</h3>
                    <?php
                    if ( class_exists( 'WooCommerce' ) ) {
                        $product_categories = get_terms( array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'exclude'    => array( get_option( 'default_product_cat' ) ),
                        ) );

                        if ( ! empty( $product_categories ) ) {
                            echo '<ul style="list-style: none; padding: 0; margin: 0;">';
                            foreach ( $product_categories as $category ) {
                                $category_link = get_term_link( $category );
                                $is_current = ( is_product_category() && get_queried_object_id() === $category->term_id );
                                echo '<li style="margin-bottom: 0.75rem;">';
                                echo '<a href="' . esc_url( $category_link ) . '" style="color: ' . ( $is_current ? 'var(--primary-color)' : 'var(--text-dark)' ) . '; text-decoration: none; display: flex; justify-content: space-between; align-items: center; font-weight: ' . ( $is_current ? '600' : '400' ) . ';">';
                                echo '<span>' . esc_html( $category->name ) . '</span>';
                                echo '<span style="color: var(--text-gray); font-size: 0.875rem;">(' . $category->count . ')</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </aside>

        <!-- Main Content Area on Right -->
        <div class="woocommerce-main-content" style="flex: 1; min-width: 0;">
            <?php woocommerce_content(); ?>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const minSlider = document.getElementById('price-slider-min');
    const maxSlider = document.getElementById('price-slider-max');
    const minValue = document.getElementById('slider-min-value');
    const maxValue = document.getElementById('slider-max-value');
    const minInput = document.getElementById('min_price');
    const maxInput = document.getElementById('max_price');
    const sliderRange = document.getElementById('price-slider-range');
    const form = document.getElementById('price-filter-form');

    let autoFilterTimeout;

    if (minSlider && maxSlider) {
        function updateSliderRange() {
            const min = parseFloat(minSlider.value);
            const max = parseFloat(maxSlider.value);
            const minPercent = ((min - minSlider.min) / (minSlider.max - minSlider.min)) * 100;
            const maxPercent = ((max - minSlider.min) / (minSlider.max - minSlider.min)) * 100;

            if (sliderRange) {
                sliderRange.style.left = minPercent + '%';
                sliderRange.style.width = (maxPercent - minPercent) + '%';
            }
        }

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

            updateSliderRange();

            // Auto-submit with debounce
            clearTimeout(autoFilterTimeout);
            autoFilterTimeout = setTimeout(function() {
                form.submit();
            }, 800); // Wait 800ms after user stops dragging
        }

        minSlider.addEventListener('input', updateValues);
        maxSlider.addEventListener('input', updateValues);

        // Initialize range display
        updateSliderRange();
    }
});
</script>

<?php
get_footer();
