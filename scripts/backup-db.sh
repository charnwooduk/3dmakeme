#!/bin/bash
# Database backup script for 3D Make Me WordPress site

set -e

# Load environment variables safely
if [ -f .env ]; then
    set -a
    source .env
    set +a
fi

# Configuration
BACKUP_DIR="./backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

# Function to backup a database
backup_database() {
    local ENV=$1
    local CONTAINER=$2
    local DB_NAME=$3
    local DB_USER=$4
    local DB_PASS=$5

    echo "📦 Backing up $ENV database..."

    docker exec "$CONTAINER" mysqldump \
        -u "$DB_USER" \
        -p"$DB_PASS" \
        "$DB_NAME" \
        > "$BACKUP_DIR/${ENV}_${TIMESTAMP}.sql"

    # Compress the backup
    gzip "$BACKUP_DIR/${ENV}_${TIMESTAMP}.sql"

    echo "✅ Backup saved: $BACKUP_DIR/${ENV}_${TIMESTAMP}.sql.gz"
}

# Menu
echo "================================"
echo "  Database Backup Tool"
echo "================================"
echo "1) Backup Development DB"
echo "2) Backup Production DB"
echo "3) Backup Both DBs"
echo "4) Exit"
echo "================================"
read -p "Select option (1-4): " option

case $option in
    1)
        backup_database "dev" "3dmakeme_mysql_dev" \
            "${DEV_DB_NAME:-wordpress_dev}" \
            "${DEV_DB_USER:-wordpress_dev}" \
            "${DEV_DB_PASSWORD:-wordpress_password}"
        ;;
    2)
        backup_database "prod" "3dmakeme_mysql_prod" \
            "${PROD_DB_NAME:-wordpress_prod}" \
            "${PROD_DB_USER:-wordpress_prod}" \
            "${PROD_DB_PASSWORD:-wordpress_password}"
        ;;
    3)
        backup_database "dev" "3dmakeme_mysql_dev" \
            "${DEV_DB_NAME:-wordpress_dev}" \
            "${DEV_DB_USER:-wordpress_dev}" \
            "${DEV_DB_PASSWORD:-wordpress_password}"

        backup_database "prod" "3dmakeme_mysql_prod" \
            "${PROD_DB_NAME:-wordpress_prod}" \
            "${PROD_DB_USER:-wordpress_prod}" \
            "${PROD_DB_PASSWORD:-wordpress_password}"
        ;;
    4)
        echo "Exiting..."
        exit 0
        ;;
    *)
        echo "❌ Invalid option"
        exit 1
        ;;
esac

# Clean up old backups (keep last 7 days)
echo "🧹 Cleaning up old backups..."
find "$BACKUP_DIR" -name "*.sql.gz" -mtime +7 -delete
echo "✅ Done!"
