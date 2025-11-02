# GitHub Repository Setup Checklist

## 📋 Step-by-Step Setup Guide

### 1. Create GitHub Repository

1. Go to GitHub.com
2. Click "New Repository"
3. Name it: `3dmakeme_newsite` (or your preferred name)
4. Make it **Private** (recommended for production sites)
5. **Do NOT** initialize with README (we have our own)
6. Click "Create Repository"

### 2. Prepare Your Local Directory

```bash
# Navigate to your newsite directory
cd /home/rswindel/projects/3dmakeme_legacy/newsite

# Initialize git if not already done
git init

# Add files to track
git add themes/ plugins/ mysql-init/

# Copy the essential config files from /tmp
cp /tmp/.gitignore .
cp /tmp/.env.example .
cp /tmp/docker-compose.dev.yml .
cp /tmp/docker-compose.prod.yml .
cp /tmp/README.md .
cp /tmp/01-create-databases.sql mysql-init/
cp /tmp/backup-db.sh scripts/backup-db.sh
cp /tmp/sync-prod-to-dev.sh scripts/sync-prod-to-dev.sh
cp /tmp/setup.sh scripts/setup.sh

# Make scripts executable
chmod +x scripts/*.sh
```

### 3. Create Your .env File (DO NOT COMMIT THIS!)

```bash
# Copy the example and edit it
cp .env.example .env

# Edit .env with your actual passwords
nano .env  # or use your preferred editor
```

**Important:** Make sure to change all passwords in `.env`!

### 4. Update .gitignore

The `.gitignore` file should already exclude:
- ✅ `.env` files
- ✅ `uploads/` directory
- ✅ Database backups
- ✅ WordPress core files
- ✅ Node modules

### 5. Prepare Database Initialization

```bash
# Create mysql-init directory if it doesn't exist
mkdir -p mysql-init

# If you have an existing database backup, add it
# Make sure it's named with a number prefix for proper ordering:
# mysql-init/
#   ├── 01-create-databases.sql  (already created)
#   └── 02-import-data.sql       (your backup - optional)
```

### 6. Create Scripts Directory

```bash
mkdir -p scripts
# Scripts have been created in step 2
```

### 7. Initial Commit

```bash
# Check what will be committed
git status

# Add all files (respecting .gitignore)
git add .

# Create initial commit
git commit -m "Initial commit: WordPress theme, plugins, and Docker setup"

# Add remote (replace with your actual repo URL)
git remote add origin https://github.com/yourusername/3dmakeme_newsite.git

# Push to GitHub
git branch -M main
git push -u origin main
```

## 📦 What Gets Committed to GitHub

### ✅ INCLUDE (Add to Git):
- `themes/3dmakeme-modern/` - Your custom theme
- `plugins/` - Custom/modified plugins only
- `mysql-init/01-create-databases.sql` - DB initialization
- `docker-compose.dev.yml` - Development config
- `docker-compose.prod.yml` - Production config
- `.env.example` - Template (no real passwords!)
- `.gitignore` - Exclusion rules
- `README.md` - Documentation
- `scripts/` - Helper scripts

### ❌ EXCLUDE (In .gitignore):
- `.env` - Real passwords (NEVER commit this!)
- `uploads/` - Media files
- `*.sql` backups (except init scripts)
- `node_modules/`
- `.vscode/`, `.idea/`
- WordPress core files

## 🔒 Security Checklist

Before pushing to GitHub:
- [ ] `.env` is in `.gitignore` and NOT committed
- [ ] No real passwords in any committed files
- [ ] `.env.example` has placeholder values only
- [ ] Database backups are in `.gitignore`
- [ ] Repository is set to Private

## 🚀 Testing Your Setup

After pushing to GitHub, test by cloning on a different machine:

```bash
# Clone the repo
git clone https://github.com/yourusername/3dmakeme_newsite.git
cd 3dmakeme_newsite

# Run setup script
chmod +x scripts/setup.sh
./scripts/setup.sh

# Or manually:
cp .env.example .env
# Edit .env with your values
docker-compose -f docker-compose.dev.yml up -d
```

## 📊 Repository Structure After Setup

```
3dmakeme_newsite/
├── .git/                           # Git metadata
├── .github/                        # Optional: CI/CD workflows
├── themes/
│   └── 3dmakeme-modern/           # Your custom theme ✅
├── plugins/                        # Custom plugins ✅
├── uploads/                        # Media (NOT in git) ❌
├── mysql-init/
│   └── 01-create-databases.sql    # DB init script ✅
├── scripts/
│   ├── backup-db.sh               # Backup script ✅
│   ├── sync-prod-to-dev.sh        # Sync script ✅
│   └── setup.sh                   # Setup script ✅
├── docker-compose.dev.yml         # Dev environment ✅
├── docker-compose.prod.yml        # Prod environment ✅
├── .env                           # Real passwords (NOT in git!) ❌
├── .env.example                   # Template ✅
├── .gitignore                     # Exclusions ✅
└── README.md                      # Documentation ✅
```

## 🔄 Daily Workflow

### Making Changes
```bash
# Make changes to theme files
nano themes/3dmakeme-modern/style.css

# Commit changes
git add themes/
git commit -m "Update theme styles"
git push
```

### Updating Another Environment
```bash
# On another machine or server
git pull
docker-compose -f docker-compose.dev.yml restart wordpress_dev
```

## 💾 Database Management

### Backup Before Major Changes
```bash
./scripts/backup-db.sh
# Select option 3 (Backup Both DBs)
```

### Sync Prod to Dev (for testing)
```bash
./scripts/sync-prod-to-dev.sh
```

## 🎯 Port Configuration Summary

**Development Environment:**
- WordPress Dev: http://localhost:8090
- MySQL Dev: localhost:3307
- phpMyAdmin Dev: http://localhost:8091
- MailHog Web: http://localhost:8026
- MailHog SMTP: localhost:1026

**Production Environment (same server):**
- WordPress Prod: http://localhost:8092
- MySQL Prod: localhost:3308
- phpMyAdmin Prod: http://localhost:8093

Both environments can run simultaneously with 2 separate databases!

## 🆘 Troubleshooting

**"Permission denied" when pushing to GitHub**
```bash
# Set up SSH key or use personal access token
# See: https://docs.github.com/en/authentication
```

**"Port already in use"**
```bash
# Edit .env file and change the port numbers
# Then restart containers
docker-compose -f docker-compose.dev.yml down
docker-compose -f docker-compose.dev.yml up -d
```

**Accidentally committed .env file**
```bash
# Remove from git (but keep locally)
git rm --cached .env
git commit -m "Remove .env from tracking"
git push

# Rotate all passwords in the file!
```

## ✨ Next Steps

1. ✅ Set up GitHub repository
2. ✅ Push initial code
3. Set up CI/CD (optional)
4. Configure domain for production
5. Set up SSL certificates (Let's Encrypt)
6. Configure automated backups
7. Set up monitoring/alerts
