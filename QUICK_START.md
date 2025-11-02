# 🚀 Quick Start Guide

Get your WordPress site running in under 5 minutes!

## Prerequisites Checklist

- [ ] Docker Desktop installed and running
- [ ] Git installed
- [ ] GitHub account created
- [ ] At least 4GB RAM available

## Method 1: Brand New Repository (Recommended)

### Step 1: Create GitHub Repository

1. Go to https://github.com/new
2. Repository name: `3dmakeme_newsite`
3. Visibility: **Private** (recommended)
4. **DO NOT** check "Initialize with README"
5. Click "Create repository"

### Step 2: Initialize Local Repository

```bash
# Navigate to newsitesetup folder
cd /home/rswindel/projects/3dmakeme_legacy/newsitesetup

# Initialize Git
git init

# Add all files
git add .

# Create first commit
git commit -m "Initial commit: WordPress dev/prod setup with custom theme"

# Connect to GitHub (replace with your URL)
git remote add origin https://github.com/YOUR_USERNAME/3dmakeme_newsite.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Step 3: Set Up Environment

```bash
# Create .env file from template
cp .env.example .env

# Edit .env with your passwords (IMPORTANT!)
nano .env

# Start development environment
docker-compose -f docker-compose.dev.yml up -d
```

### Step 4: Access Your Site

Wait 30 seconds for containers to start, then:
- **WordPress:** http://localhost:8090
- **phpMyAdmin:** http://localhost:8091
- **MailHog:** http://localhost:8026

### Step 5: Complete WordPress Setup

1. Go to http://localhost:8090
2. Select language
3. Create admin account
4. Install WooCommerce: `docker exec 3dmakeme_wordpress_dev wp plugin install woocommerce --activate --allow-root`
5. Activate 3dmakeme-modern theme in WordPress admin

## Method 2: Clone Existing Repository

If you've already pushed to GitHub:

```bash
# Clone your repository
git clone https://github.com/YOUR_USERNAME/3dmakeme_newsite.git
cd 3dmakeme_newsite

# Set up environment
cp .env.example .env
nano .env  # Edit with your passwords

# Start containers
docker-compose -f docker-compose.dev.yml up -d

# Wait 30 seconds, then visit http://localhost:8090
```

## 🔑 Environment Variables to Change

Edit `.env` and update these **REQUIRED** values:

```bash
# Development Database
DEV_DB_PASSWORD=change_this_dev_password      # ← CHANGE THIS!
DEV_DB_ROOT_PASSWORD=change_this_dev_root_password  # ← CHANGE THIS!

# Production Database
PROD_DB_PASSWORD=change_this_prod_password    # ← CHANGE THIS!
PROD_DB_ROOT_PASSWORD=change_this_prod_root_password  # ← CHANGE THIS!
```

## 🎯 Port Configuration

**Default Ports (edit .env to change):**

Development:
- WordPress: 8090
- MySQL: 3307
- phpMyAdmin: 8091
- MailHog SMTP: 1026
- MailHog Web: 8026

Production:
- WordPress: 8092
- MySQL: 3308
- phpMyAdmin: 8093

## 🛠️ Common Commands

### Start Development
```bash
docker-compose -f docker-compose.dev.yml up -d
```

### Stop Development
```bash
docker-compose -f docker-compose.dev.yml down
```

### View Logs
```bash
docker-compose -f docker-compose.dev.yml logs -f
```

### Backup Database
```bash
./scripts/backup-db.sh
```

### Sync Production to Development
```bash
./scripts/sync-prod-to-dev.sh
```

### Install Plugin
```bash
docker exec 3dmakeme_wordpress_dev wp plugin install PLUGIN_NAME --activate --allow-root
```

### Clear WordPress Cache
```bash
docker exec 3dmakeme_wordpress_dev wp cache flush --allow-root
```

## 🐛 Troubleshooting

### Port Already in Use

Edit `.env` and change port numbers:
```bash
DEV_WP_PORT=8090  # Change to 8095 or another free port
```

### Can't Connect to Database

```bash
# Restart MySQL container
docker restart 3dmakeme_mysql_dev

# Check if running
docker ps | grep mysql
```

### Theme Not Showing

```bash
# Copy theme from container
docker cp 3dmakeme_wordpress_dev:/var/www/html/wp-content/themes/3dmakeme-modern ./themes/

# Or check volume mount
docker inspect 3dmakeme_wordpress_dev | grep -A 10 Mounts
```

### Permission Errors

```bash
# Fix uploads directory
sudo chown -R www-data:www-data uploads/
sudo chmod -R 755 uploads/
```

## ✅ Verification Checklist

After setup, verify:

- [ ] http://localhost:8090 shows WordPress
- [ ] http://localhost:8091 shows phpMyAdmin
- [ ] Can log into WordPress admin
- [ ] 3dmakeme-modern theme is available
- [ ] WooCommerce is installed and activated
- [ ] Can upload images
- [ ] Emails appear in MailHog (http://localhost:8026)

## 📚 Next Steps

1. **Import existing data** (if you have it):
   ```bash
   docker exec -i 3dmakeme_mysql_dev mysql -u wordpress_dev -pYOUR_PASSWORD wordpress_dev < your_backup.sql
   ```

2. **Set up production environment**:
   ```bash
   docker-compose -f docker-compose.prod.yml up -d
   ```

3. **Configure WooCommerce** in WordPress admin

4. **Add products** to your shop

5. **Customize theme** by editing files in `themes/3dmakeme-modern/`

## 🆘 Getting Help

- **Docker Issues:** Check Docker logs with `docker-compose logs`
- **WordPress Issues:** Check `wp-content/debug.log` in container
- **Database Issues:** Use phpMyAdmin to inspect database
- **Theme Issues:** Check browser console and WordPress debug log

For full documentation, see:
- `README.md` - Complete project documentation
- `GITHUB_SETUP_CHECKLIST.md` - Detailed GitHub setup
- `REPOSITORY_STRUCTURE.md` - File structure explained

## 🎉 You're Done!

Your WordPress development environment should now be running!

Visit http://localhost:8090 and start building! 🚀
