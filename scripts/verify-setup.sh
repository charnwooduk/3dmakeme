#!/bin/bash
# Verify that the repository is correctly set up before pushing to GitHub

set -e

echo "================================"
echo "  Repository Setup Verification"
echo "================================"
echo ""

ERRORS=0
WARNINGS=0

# Color codes for output
RED='\033[0;31m'
YELLOW='\033[1;33m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

error() {
    echo -e "${RED}❌ ERROR: $1${NC}"
    ERRORS=$((ERRORS + 1))
}

warning() {
    echo -e "${YELLOW}⚠️  WARNING: $1${NC}"
    WARNINGS=$((WARNINGS + 1))
}

success() {
    echo -e "${GREEN}✅ $1${NC}"
}

info() {
    echo "ℹ️  $1"
}

# Check 1: Git initialized
echo "📋 Checking Git setup..."
if [ -d .git ]; then
    success "Git repository initialized"
else
    warning "Git not initialized. Run: git init"
fi

# Check 2: Essential files exist
echo ""
echo "📋 Checking essential files..."
ESSENTIAL_FILES=(
    ".gitignore"
    ".env.example"
    "README.md"
    "docker-compose.dev.yml"
    "docker-compose.prod.yml"
)

for file in "${ESSENTIAL_FILES[@]}"; do
    if [ -f "$file" ]; then
        success "$file exists"
    else
        error "$file is missing!"
    fi
done

# Check 3: .env file should NOT be committed
echo ""
echo "📋 Checking .env file..."
if [ -f .env ]; then
    if git ls-files --error-unmatch .env 2>/dev/null; then
        error ".env file is tracked by Git! This contains passwords!"
        echo "   Run: git rm --cached .env"
    else
        success ".env exists but is not tracked by Git"
    fi
else
    warning ".env file doesn't exist yet. Create it from .env.example"
fi

# Check 4: Custom theme exists
echo ""
echo "📋 Checking custom theme..."
if [ -d "themes/3dmakeme-modern" ]; then
    success "Custom theme exists"

    # Check theme files
    THEME_FILES=("style.css" "functions.php" "woocommerce.php" "archive.php")
    for file in "${THEME_FILES[@]}"; do
        if [ -f "themes/3dmakeme-modern/$file" ]; then
            success "  themes/3dmakeme-modern/$file exists"
        else
            warning "  themes/3dmakeme-modern/$file is missing"
        fi
    done
else
    error "Custom theme not found at themes/3dmakeme-modern/"
fi

# Check 5: Directory structure
echo ""
echo "📋 Checking directory structure..."
DIRECTORIES=(
    "themes"
    "plugins"
    "uploads"
    "mysql-init"
    "scripts"
    "backups"
)

for dir in "${DIRECTORIES[@]}"; do
    if [ -d "$dir" ]; then
        success "$dir/ directory exists"
    else
        warning "$dir/ directory is missing"
    fi
done

# Check 6: Scripts are executable
echo ""
echo "📋 Checking scripts..."
if [ -d scripts ]; then
    for script in scripts/*.sh; do
        if [ -x "$script" ]; then
            success "$(basename $script) is executable"
        else
            warning "$(basename $script) is not executable. Run: chmod +x $script"
        fi
    done
fi

# Check 7: .gitkeep files exist
echo ""
echo "📋 Checking .gitkeep files..."
if [ -f "uploads/.gitkeep" ]; then
    success "uploads/.gitkeep exists"
else
    warning "uploads/.gitkeep is missing. Empty directories won't be tracked!"
fi

if [ -f "backups/.gitkeep" ]; then
    success "backups/.gitkeep exists"
else
    warning "backups/.gitkeep is missing"
fi

# Check 8: Database init script
echo ""
echo "📋 Checking database initialization..."
if [ -f "mysql-init/01-create-databases.sql" ]; then
    success "Database init script exists"
else
    error "mysql-init/01-create-databases.sql is missing!"
fi

# Check 9: Verify .gitignore is working
echo ""
echo "📋 Checking .gitignore effectiveness..."
if git check-ignore uploads/test.jpg 2>/dev/null; then
    success "uploads/ directory contents are ignored"
else
    if [ -d .git ]; then
        warning "uploads/ might not be properly ignored"
    fi
fi

if git check-ignore backups/test.sql 2>/dev/null; then
    success "backups/ directory contents are ignored"
else
    if [ -d .git ]; then
        warning "backups/ might not be properly ignored"
    fi
fi

# Check 10: Git status
echo ""
echo "📋 Checking what will be committed..."
if [ -d .git ]; then
    info "Files staged for commit:"
    git status --short

    # Check for large files
    if git ls-files | xargs du -sh 2>/dev/null | grep -E '[0-9]+M'; then
        warning "Large files detected in repository!"
        echo "   Consider excluding them in .gitignore"
    fi
fi

# Summary
echo ""
echo "================================"
echo "  Verification Summary"
echo "================================"
echo ""

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}🎉 Perfect! Repository is ready for GitHub!${NC}"
    echo ""
    echo "Next steps:"
    echo "  1. git add ."
    echo "  2. git commit -m \"Initial commit\""
    echo "  3. git remote add origin https://github.com/YOUR_USERNAME/3dmakeme_newsite.git"
    echo "  4. git push -u origin main"
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠️  $WARNINGS warning(s) found${NC}"
    echo "Review warnings above. You can still proceed, but fix warnings when possible."
else
    echo -e "${RED}❌ $ERRORS error(s) and $WARNINGS warning(s) found${NC}"
    echo "Fix errors before pushing to GitHub!"
    exit 1
fi

echo ""
echo "================================"
