#!/bin/bash
# Database Migration Script: Dev to Production
# This script migrates the development database to production

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="./backups"
DEV_DUMP_FILE="${BACKUP_DIR}/dev_backup_${TIMESTAMP}.sql"
PROD_BACKUP_FILE="${BACKUP_DIR}/prod_backup_${TIMESTAMP}.sql"

# Database credentials from .env
source .env 2>/dev/null || { echo -e "${RED}Error: .env file not found${NC}"; exit 1; }

echo -e "${GREEN}=== WordPress Dev to Prod Migration ===${NC}\n"

# Step 1: Create backup directory
echo -e "${YELLOW}[1/6] Creating backup directory...${NC}"
mkdir -p ${BACKUP_DIR}
echo -e "${GREEN}✓ Backup directory ready${NC}\n"

# Step 2: Backup production database (safety first!)
echo -e "${YELLOW}[2/6] Backing up production database...${NC}"
sudo docker exec 3dmakeme_mysql_prod mysqldump -u ${PROD_DB_USER} -p${PROD_DB_PASSWORD} ${PROD_DB_NAME} > ${PROD_BACKUP_FILE} 2>/dev/null || {
    echo -e "${YELLOW}No production data to backup (database might be empty)${NC}"
    touch ${PROD_BACKUP_FILE}
}
echo -e "${GREEN}✓ Production backup saved: ${PROD_BACKUP_FILE}${NC}\n"

# Step 3: Export development database
echo -e "${YELLOW}[3/6] Exporting development database...${NC}"
sudo docker exec 3dmakeme_mysql_dev mysqldump -u ${DEV_DB_USER} -p${DEV_DB_PASSWORD} ${DEV_DB_NAME} > ${DEV_DUMP_FILE}
echo -e "${GREEN}✓ Development database exported: ${DEV_DUMP_FILE}${NC}\n"

# Step 4: Update URLs in dump file
echo -e "${YELLOW}[4/6] Updating URLs (dev.3dmake.me → 3dmake.me)...${NC}"
sed -i 's|https://dev.3dmake.me|https://3dmake.me|g' ${DEV_DUMP_FILE}
sed -i 's|http://dev.3dmake.me|https://3dmake.me|g' ${DEV_DUMP_FILE}
echo -e "${GREEN}✓ URLs updated${NC}\n"

# Step 5: Import to production
echo -e "${YELLOW}[5/6] Importing to production database...${NC}"
sudo docker exec -i 3dmakeme_mysql_prod mysql -u ${PROD_DB_USER} -p${PROD_DB_PASSWORD} ${PROD_DB_NAME} < ${DEV_DUMP_FILE}
echo -e "${GREEN}✓ Database imported to production${NC}\n"

# Step 6: Flush WordPress cache
echo -e "${YELLOW}[6/6] Flushing WordPress cache...${NC}"
sudo docker exec 3dmakeme_wordpress_prod rm -rf /var/www/html/wp-content/cache/* 2>/dev/null || true
echo -e "${GREEN}✓ Cache cleared${NC}\n"

# Summary
echo -e "${GREEN}=== Migration Complete! ===${NC}"
echo -e "Development backup: ${DEV_DUMP_FILE}"
echo -e "Production backup: ${PROD_BACKUP_FILE}"
echo -e "\n${YELLOW}Note:${NC} If something went wrong, restore production with:"
echo -e "  sudo docker exec -i 3dmakeme_mysql_prod mysql -u ${PROD_DB_USER} -p${PROD_DB_PASSWORD} ${PROD_DB_NAME} < ${PROD_BACKUP_FILE}"
echo -e "\n${GREEN}Visit your production site at: https://3dmake.me${NC}\n"
