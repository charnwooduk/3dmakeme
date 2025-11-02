#!/bin/bash
# Sync production database to development
# WARNING: This will OVERWRITE your development database!

set -e

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

echo "⚠️  WARNING: This will OVERWRITE your development database!"
echo "Production DB → Development DB"
echo ""
read -p "Are you sure you want to continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "❌ Cancelled"
    exit 0
fi

TEMP_FILE="/tmp/prod_to_dev_$(date +%Y%m%d_%H%M%S).sql"

echo "1️⃣  Dumping production database..."
docker exec 3dmakeme_mysql_prod mysqldump \
    -u "${PROD_DB_USER:-wordpress_prod}" \
    -p"${PROD_DB_PASSWORD:-wordpress_password}" \
    "${PROD_DB_NAME:-wordpress_prod}" \
    > "$TEMP_FILE"

echo "2️⃣  Dropping development database..."
docker exec 3dmakeme_mysql_dev mysql \
    -u root \
    -p"${DEV_DB_ROOT_PASSWORD:-root_password}" \
    -e "DROP DATABASE IF EXISTS ${DEV_DB_NAME:-wordpress_dev}; CREATE DATABASE ${DEV_DB_NAME:-wordpress_dev} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "3️⃣  Importing to development database..."
docker exec -i 3dmakeme_mysql_dev mysql \
    -u "${DEV_DB_USER:-wordpress_dev}" \
    -p"${DEV_DB_PASSWORD:-wordpress_password}" \
    "${DEV_DB_NAME:-wordpress_dev}" \
    < "$TEMP_FILE"

echo "4️⃣  Updating site URLs for development..."
docker exec 3dmakeme_mysql_dev mysql \
    -u "${DEV_DB_USER:-wordpress_dev}" \
    -p"${DEV_DB_PASSWORD:-wordpress_password}" \
    "${DEV_DB_NAME:-wordpress_dev}" \
    -e "UPDATE ${DEV_WP_TABLE_PREFIX:-lmf_}options SET option_value='${DEV_SITE_URL:-http://localhost:8090}' WHERE option_name IN ('siteurl', 'home');"

echo "5️⃣  Cleaning up temporary file..."
rm "$TEMP_FILE"

echo "✅ Production database successfully synced to development!"
echo "🌐 Development site: ${DEV_SITE_URL:-http://localhost:8090}"
echo "🔑 You may need to log in again"
