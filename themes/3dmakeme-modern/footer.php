<?php
/**
 * Custom footer template
 */
?>
    </main><!-- .site-content -->
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php bloginfo( 'name' ); ?></h3>
                    <p>Your source for amazing 3D printed creations.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Have questions? Get in touch!</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div><!-- .site-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
