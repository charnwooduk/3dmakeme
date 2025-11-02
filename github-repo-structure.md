# GitHub Repo Structure for 3dmakeme_newsite

```
3dmakeme_newsite/
├── .github/
│   └── workflows/
│       └── deploy.yml                 # Optional: CI/CD workflow
├── docker/
│   ├── docker-compose.dev.yml         # Development environment
│   ├── docker-compose.prod.yml        # Production environment
│   └── nginx/                         # Optional: Nginx config for prod
│       └── default.conf
├── mysql-init/
│   ├── 01-create-databases.sql        # Create dev and prod databases
│   └── 02-import-data.sql             # Optional: Initial data
├── themes/
│   └── 3dmakeme-modern/               # Your custom theme
│       ├── style.css
│       ├── functions.php
│       ├── header.php
│       ├── footer.php
│       ├── archive.php
│       ├── woocommerce.php
│       └── ... (all theme files)
├── plugins/
│   └── custom-plugins/                # Only custom/modified plugins
│       └── your-custom-plugin/
├── scripts/
│   ├── backup-db.sh                   # Database backup script
│   ├── restore-db.sh                  # Database restore script
│   └── deploy.sh                      # Deployment script
├── .env.example                       # Environment variables template
├── .gitignore                         # Git ignore rules
├── README.md                          # Setup instructions
└── wp-config-docker.php               # Optional: Custom wp-config
```

## What to INCLUDE in Git:
✅ Custom themes
✅ Custom plugins
✅ Docker configuration files
✅ Database initialization scripts
✅ Environment variable templates (.env.example)
✅ Documentation (README.md)
✅ Deployment scripts

## What to EXCLUDE from Git:
❌ uploads/ directory (use .gitignore)
❌ .env files with real credentials
❌ WordPress core files (handled by Docker)
❌ node_modules/
❌ Database backups (*.sql except init scripts)
❌ vendor/ directories
❌ .DS_Store, Thumbs.db
❌ wp-config.php with hardcoded credentials
