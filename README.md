# 3D Make Me - WordPress Site

Modern WordPress e-commerce site for 3D printed products built with WooCommerce.

## 🚀 Quick Start

### Prerequisites
- Docker Desktop installed
- Git installed
- At least 4GB RAM available

### Initial Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/3dmakeme_newsite.git
   cd 3dmakeme_newsite
   ```

2. **Create environment file**
   ```bash
   cp .env.example .env
   # Edit .env and update passwords and configuration
   ```

3. **Start development environment**
   ```bash
   docker-compose -f docker-compose.dev.yml up -d
   ```

4. **Access the site**
   - Development Site: http://localhost:8090
   - Dev phpMyAdmin: http://localhost:8091
   - MailHog (email testing): http://localhost:8026

## 📁 Project Structure

```
├── themes/
│   └── 3dmakeme-modern/     # Custom theme
├── plugins/                  # Custom plugins only
├── uploads/                  # Media files (not in git)
├── mysql-init/              # Database initialization scripts
├── docker-compose.dev.yml   # Development environment
├── docker-compose.prod.yml  # Production environment
└── .env                     # Environment variables (not in git)
```

## 🔧 Development Workflow

### Starting the Development Environment
```bash
# Start dev environment
docker-compose -f docker-compose.dev.yml up -d

# View logs
docker-compose -f docker-compose.dev.yml logs -f wordpress_dev

# Stop dev environment
docker-compose -f docker-compose.dev.yml down
```

### Starting the Production Environment (Local)
```bash
# Start prod environment
docker-compose -f docker-compose.prod.yml up -d

# Stop prod environment
docker-compose -f docker-compose.prod.yml down
```

### Accessing Different Databases

**Development Database:**
- Host: localhost
- Port: 3307
- Database: wordpress_dev
- User: wordpress_dev
- Password: (from .env)

**Production Database:**
- Host: localhost
- Port: 3308
- Database: wordpress_prod
- User: wordpress_prod
- Password: (from .env)

## 💾 Database Management

### Backup Development Database
```bash
docker exec 3dmakeme_mysql_dev mysqldump \
  -u wordpress_dev -pwordpress_password \
  wordpress_dev > backups/dev_$(date +%Y%m%d_%H%M%S).sql
```

### Backup Production Database
```bash
docker exec 3dmakeme_mysql_prod mysqldump \
  -u wordpress_prod -pwordpress_password \
  wordpress_prod > backups/prod_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Development Database
```bash
docker exec -i 3dmakeme_mysql_dev mysql \
  -u wordpress_dev -pwordpress_password \
  wordpress_dev < backups/your_backup.sql
```

### Copy Production to Development
```bash
# Backup prod
docker exec 3dmakeme_mysql_prod mysqldump \
  -u wordpress_prod -pwordpress_password \
  wordpress_prod > /tmp/prod_to_dev.sql

# Restore to dev
docker exec -i 3dmakeme_mysql_dev mysql \
  -u wordpress_dev -pwordpress_password \
  wordpress_dev < /tmp/prod_to_dev.sql

# Update URLs in dev database
docker exec 3dmakeme_mysql_dev mysql \
  -u wordpress_dev -pwordpress_password \
  wordpress_dev \
  -e "UPDATE lmf_options SET option_value='http://localhost:8090' WHERE option_name IN ('siteurl', 'home');"
```

## 🎨 Theme Development

The custom theme is located in `themes/3dmakeme-modern/`.

Key files:
- `style.css` - Main stylesheet
- `functions.php` - Theme functions and hooks
- `woocommerce.php` - WooCommerce template
- `archive.php` - Archive/category pages

### Editing Theme Files
```bash
# Copy theme from container (if needed)
docker cp 3dmakeme_wordpress_dev:/var/www/html/wp-content/themes/3dmakeme-modern ./themes/

# Edit files locally, changes sync automatically via volume mounts

# Clear WordPress cache
docker exec 3dmakeme_wordpress_dev wp cache flush --allow-root
```

## 📧 Email Testing (Development)

All emails in development are caught by MailHog:
- Web Interface: http://localhost:8026
- SMTP Server: localhost:1026

No emails are actually sent, allowing safe testing.

## 🔒 Security Best Practices

1. **Never commit sensitive data:**
   - `.env` file
   - Database backups
   - `wp-config.php` with real credentials

2. **Use strong passwords** in production .env file

3. **Production checklist:**
   - Set `WP_DEBUG=false`
   - Set `DISALLOW_FILE_EDIT=true`
   - Use HTTPS
   - Regular backups
   - Update WordPress core, themes, plugins

## 🚢 Deployment

### To Production Server

1. **Push code to GitHub**
   ```bash
   git add .
   git commit -m "Your changes"
   git push origin main
   ```

2. **On production server, pull changes**
   ```bash
   git pull origin main
   ```

3. **Update production environment**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d --build
   ```

4. **Backup before updates!**

## 🐛 Troubleshooting

### Container won't start
```bash
# Check logs
docker-compose -f docker-compose.dev.yml logs

# Remove containers and start fresh
docker-compose -f docker-compose.dev.yml down -v
docker-compose -f docker-compose.dev.yml up -d
```

### Database connection error
```bash
# Restart MySQL container
docker restart 3dmakeme_mysql_dev

# Check MySQL is running
docker ps | grep mysql
```

### Theme changes not showing
```bash
# Clear WordPress cache
docker exec 3dmakeme_wordpress_dev wp cache flush --allow-root

# Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)
```

### Permission issues
```bash
# Fix uploads directory permissions
sudo chown -R www-data:www-data uploads/
sudo chmod -R 755 uploads/
```

## 📞 Support

For issues or questions:
- Check Docker logs: `docker-compose logs`
- Review WordPress debug log: `wp-content/debug.log`
- Check database connectivity via phpMyAdmin

## 📝 License

Proprietary - All Rights Reserved
