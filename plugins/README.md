# WordPress Plugins

This directory contains **custom or modified** WordPress plugins only.

## What to Include Here

✅ **DO include:**
- Custom plugins you've developed
- Modified versions of third-party plugins
- Plugins not available in WordPress.org repository

❌ **DON'T include:**
- Standard WordPress plugins (like Akismet, Hello Dolly)
- WooCommerce (installed separately)
- Any plugin available from WordPress.org

## Why?

Standard plugins should be:
1. Listed in a `plugins.txt` file (recommended)
2. Installed via WP-CLI or WordPress admin
3. Not tracked in Git to avoid:
   - Repository bloat
   - Merge conflicts on updates
   - Security issues from outdated plugins

## Installing Standard Plugins

### Via Docker/WP-CLI
```bash
docker exec 3dmakeme_wordpress_dev wp plugin install woocommerce --activate --allow-root
```

### Via WordPress Admin
1. Log into WordPress admin
2. Go to Plugins → Add New
3. Search and install desired plugins

## Recommended Plugins List

Create a `plugins.txt` file listing required plugins:
```
woocommerce
contact-form-7
wordpress-seo
```

Then install them with:
```bash
cat plugins.txt | xargs -I {} docker exec 3dmakeme_wordpress_dev wp plugin install {} --activate --allow-root
```
