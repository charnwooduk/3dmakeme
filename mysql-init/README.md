# Database Initialization Scripts

This directory contains SQL scripts that run automatically when MySQL containers are first created.

## How It Works

When you run `docker-compose up` for the first time, Docker automatically executes all `.sql` files in this directory in alphabetical order.

## Current Scripts

- **01-create-databases.sql** - Creates dev and prod databases with proper users

## Adding Data Import

If you want to import existing data on first startup:

1. Export your database:
   ```bash
   docker exec 3dmakeme_mysql_dev mysqldump -u wordpress_dev -pwordpress_password wordpress_dev > 02-import-data.sql
   ```

2. Add it to this directory with a numeric prefix:
   ```
   mysql-init/
   ├── 01-create-databases.sql
   └── 02-import-data.sql
   ```

3. The next time you create fresh containers, both scripts will run automatically

## ⚠️ Important Notes

- Scripts only run on **first initialization** (when data volume is empty)
- To re-run scripts, you must delete the MySQL data volume:
  ```bash
  docker-compose down -v
  docker-compose up -d
  ```
- Scripts run as the MySQL root user
- Use numeric prefixes (01-, 02-, etc.) to control execution order

## Database Backups

For regular backups, **DO NOT** add them here. Instead:
- Use the `scripts/backup-db.sh` script
- Store backups in the `backups/` directory (excluded from Git)
- The backups/ directory is in .gitignore for a reason (they can be very large)
