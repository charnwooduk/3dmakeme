/**
 * 3D Make Me - Modern Theme JavaScript
 */

(function() {
    'use strict';

    // Mobile menu toggle (if needed in future)
    const mobileMenuToggle = () => {
        // Add mobile menu functionality here if needed
    };

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add to cart animations
    document.addEventListener('DOMContentLoaded', () => {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-button');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.textContent;
                this.textContent = 'Adding...';
                this.disabled = true;

                setTimeout(() => {
                    this.textContent = '✓ Added!';
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.disabled = false;
                    }, 1500);
                }, 500);
            });
        });
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', mobileMenuToggle);
})();
