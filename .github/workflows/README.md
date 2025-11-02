# GitHub Actions Workflows

This directory contains GitHub Actions workflows for CI/CD automation.

## What is GitHub Actions?

GitHub Actions allows you to automate tasks like:
- Running tests on every commit
- Deploying to production on merge to main
- Checking code quality
- Creating backups

## Current Status

Currently, there are no workflows set up. This is intentional - you can add them as needed.

## Example Workflows

### 1. Deploy to Production on Push to Main

Create `.github/workflows/deploy-prod.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.PROD_HOST }}
          username: ${{ secrets.PROD_USER }}
          key: ${{ secrets.PROD_SSH_KEY }}
          script: |
            cd /var/www/3dmakeme
            git pull origin main
            docker-compose -f docker-compose.prod.yml up -d --build
```

### 2. Backup Database Weekly

Create `.github/workflows/backup-db.yml`:

```yaml
name: Weekly Database Backup

on:
  schedule:
    - cron: '0 2 * * 0'  # Every Sunday at 2 AM

jobs:
  backup:
    runs-on: ubuntu-latest
    steps:
      - name: SSH and backup
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.PROD_HOST }}
          username: ${{ secrets.PROD_USER }}
          key: ${{ secrets.PROD_SSH_KEY }}
          script: |
            cd /var/www/3dmakeme
            ./scripts/backup-db.sh
```

## Setting Up Secrets

For workflows that need credentials:

1. Go to your GitHub repository
2. Click Settings → Secrets and variables → Actions
3. Add secrets:
   - `PROD_HOST` - Production server IP
   - `PROD_USER` - SSH username
   - `PROD_SSH_KEY` - Private SSH key
   - `PROD_DB_PASSWORD` - Database password

## Learn More

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Workflow Syntax](https://docs.github.com/en/actions/reference/workflow-syntax-for-github-actions)
