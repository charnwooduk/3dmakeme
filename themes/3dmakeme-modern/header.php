<?php
/**
 * Custom header template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    <header class="site-header">
        <div class="header-container">
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
                    </h1>
                <?php endif; ?>
            </div>

            <nav class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'menu'        => 47,
                    'menu_class'  => 'nav-menu',
                    'container'   => false,
                    'fallback_cb' => false,
                ) );
                ?>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link">
                        <span class="cart-icon">🛒</span>
                        <?php $cart_count = WC()->cart->get_cart_contents_count(); ?>
                        <?php if ( $cart_count > 0 ) : ?>
                            <span class="cart-count"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="site-content">
