#!/bin/bash
# Initial setup script for 3D Make Me WordPress site

set -e

echo "================================"
echo "  3D Make Me - Initial Setup"
echo "================================"
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop and try again."
    exit 1
fi

echo "✅ Docker is running"

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from template..."
    cp .env.example .env
    echo "⚠️  Please edit .env file and update passwords before continuing!"
    echo ""
    read -p "Press Enter after you've updated .env file..."
fi

# Load environment variables
export $(cat .env | grep -v '^#' | xargs)

echo ""
echo "🚀 Starting Docker containers..."
docker-compose -f docker-compose.dev.yml up -d

echo ""
echo "⏳ Waiting for databases to initialize (this may take a minute)..."
sleep 30

echo ""
echo "================================"
echo "  Setup Complete! 🎉"
echo "================================"
echo ""
echo "📍 Access points:"
echo "   Development Site:    http://localhost:${DEV_WP_PORT:-8090}"
echo "   Dev phpMyAdmin:      http://localhost:${DEV_PMA_PORT:-8091}"
echo "   MailHog (email):     http://localhost:${DEV_MAILHOG_WEB:-8026}"
echo ""
echo "📊 Database Info:"
echo "   Dev Database:"
echo "     - Host: localhost"
echo "     - Port: ${DEV_DB_PORT:-3307}"
echo "     - Name: ${DEV_DB_NAME:-wordpress_dev}"
echo "     - User: ${DEV_DB_USER:-wordpress_dev}"
echo ""
echo "   Prod Database:"
echo "     - Host: localhost"
echo "     - Port: ${PROD_DB_PORT:-3308}"
echo "     - Name: ${PROD_DB_NAME:-wordpress_prod}"
echo "     - User: ${PROD_DB_USER:-wordpress_prod}"
echo ""
echo "🔧 Next steps:"
echo "   1. Visit http://localhost:${DEV_WP_PORT:-8090} to complete WordPress setup"
echo "   2. Install WooCommerce plugin"
echo "   3. Activate 3dmakeme-modern theme"
echo "   4. Import your database backup if you have one"
echo ""
echo "📚 For more info, see README.md"
echo "================================"
