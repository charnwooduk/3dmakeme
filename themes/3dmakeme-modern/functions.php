<?php
/**
 * 3D Make Me - Modern Theme Functions
 */

// Theme setup
function tdmakeme_theme_setup() {
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 800, 800, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', '3dmakeme-modern' ),
        'footer'  => __( 'Footer Menu', '3dmakeme-modern' ),
    ) );

    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'tdmakeme_theme_setup' );

// Enqueue scripts and styles
function tdmakeme_scripts() {
    wp_enqueue_style( '3dmakeme-style', get_stylesheet_uri(), array(), '1.0.1762084500' );
    wp_enqueue_script( '3dmakeme-script', get_template_directory_uri() . '/js/main.js', array(), '1.0.1762084003', true );
}
add_action( 'wp_enqueue_scripts', 'tdmakeme_scripts' );

// WooCommerce customizations
add_filter( 'loop_shop_per_page', function() { return 12; }, 20 );
add_filter( 'loop_shop_columns', function() { return 4; }, 20 );

// Register sidebar
function tdmakeme_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'WooCommerce Sidebar', '3dmakeme-modern' ),
        'id'            => 'woocommerce-sidebar',
        'description'   => __( 'Sidebar for WooCommerce pages', '3dmakeme-modern' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'tdmakeme_widgets_init' );

// Force grid layout for related products (override WooCommerce flexbox)
function tdmakeme_related_products_grid_css() {
    ?>
    <style id="tdmakeme-related-products-grid">
        /* Override WooCommerce flex layout for related products with grid */
        /* First, prevent .related.products from being a grid itself */
        .related.products {
            display: block !important;
        }

        .single-product .related.products ul.products,
        body.single-product .related.products ul.products,
        .woocommerce.single-product .related.products ul.products {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)) !important;
            gap: 1.5rem !important;
            width: 100% !important;
            max-width: none !important;
            justify-content: initial !important;
            align-items: initial !important;
        }

        .single-product .related.products ul.products li.product,
        body.single-product .related.products ul.products li.product {
            display: block !important;
            flex-direction: initial !important;
            justify-content: initial !important;
            align-items: initial !important;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'tdmakeme_related_products_grid_css', 999 );

// Add price filter functionality
add_action( 'pre_get_posts', 'tdmm_filter_products_by_price' );
function tdmm_filter_products_by_price( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        
        $min_price = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : '';
        $max_price = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : '';
        
        if ( $min_price !== '' || $max_price !== '' ) {
            $meta_query = $query->get( 'meta_query' ) ? $query->get( 'meta_query' ) : array();
            
            if ( $min_price !== '' && $max_price !== '' ) {
                $meta_query[] = array(
                    'key'     => '_price',
                    'value'   => array( $min_price, $max_price ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DECIMAL(10,2)'
                );
            } elseif ( $min_price !== '' ) {
                $meta_query[] = array(
                    'key'     => '_price',
                    'value'   => $min_price,
                    'compare' => '>=',
                    'type'    => 'DECIMAL(10,2)'
                );
            } elseif ( $max_price !== '' ) {
                $meta_query[] = array(
                    'key'     => '_price',
                    'value'   => $max_price,
                    'compare' => '<=',
                    'type'    => 'DECIMAL(10,2)'
                );
            }
            
            $query->set( 'meta_query', $meta_query );
        }
    }
}
