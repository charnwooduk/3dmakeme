# WordPress Themes

This directory contains WordPress themes for the 3D Make Me site.

## Custom Theme

- **3dmakeme-modern/** - Custom theme for the site (✅ commit to Git)

## Default WordPress Themes

Default WordPress themes (twentytwenty*, storefront) are **NOT** included in the repository because:
- They're part of WordPress core
- They're automatically installed by WordPress Docker image
- Including them would bloat the repository unnecessarily

## Adding New Themes

To add a new custom theme:
1. Create a new directory in this folder
2. Develop your theme
3. Commit it to Git

## Theme Structure

WordPress themes should follow the standard WordPress theme structure:
```
theme-name/
├── style.css           (required - theme metadata)
├── functions.php       (theme functions)
├── index.php          (main template)
├── header.php         (header template)
├── footer.php         (footer template)
├── single.php         (single post template)
├── archive.php        (archive template)
└── ... (other templates)
```

For more info: https://developer.wordpress.org/themes/
