#!/bin/bash

# Complete Deployment Script for Al Takaful Al Sehi System
# This script should be run on the server (185.201.8.42)

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="new.altakafulalsehi.com"
PROJECT_PATH="/root/public_html/$DOMAIN"
ZIP_FILE="/root/altakafulalsehi_production.zip"
DB_NAME="altakafulalsehi_new"
DB_USER="altakafulalsehi_new"
DB_PASS="altakafulalsehi_new"

echo -e "${BLUE}=== Al Takaful Al Sehi Complete Deployment Script ===${NC}"
echo -e "${BLUE}Domain: $DOMAIN${NC}"
echo -e "${BLUE}Project Path: $PROJECT_PATH${NC}"
echo ""

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to print step
print_step() {
    echo -e "${YELLOW}=== Step $1: $2 ===${NC}"
}

# Step 1: Check prerequisites
print_step "1" "Checking prerequisites"
if ! command_exists php; then
    echo -e "${RED}PHP is not installed!${NC}"
    exit 1
fi

if ! command_exists composer; then
    echo -e "${RED}Composer is not installed!${NC}"
    exit 1
fi

if ! command_exists mysql; then
    echo -e "${RED}MySQL is not installed!${NC}"
    exit 1
fi

echo -e "${GREEN}All prerequisites are installed.${NC}"
echo ""

# Step 2: Create project directory
print_step "2" "Creating project directory"
mkdir -p "$PROJECT_PATH"
cd "$PROJECT_PATH"
echo -e "${GREEN}Project directory created: $PROJECT_PATH${NC}"
echo ""

# Step 3: Extract ZIP file
print_step "3" "Extracting application files"
if [ -f "$ZIP_FILE" ]; then
    unzip -o "$ZIP_FILE" -d "$PROJECT_PATH"
    echo -e "${GREEN}Files extracted successfully.${NC}"
else
    echo -e "${RED}ZIP file not found: $ZIP_FILE${NC}"
    echo "Please upload the ZIP file first."
    exit 1
fi
echo ""

# Step 4: Set permissions
print_step "4" "Setting file permissions"
chown -R www-data:www-data "$PROJECT_PATH"
chmod -R 755 "$PROJECT_PATH"
chmod -R 775 "$PROJECT_PATH/storage"
chmod -R 775 "$PROJECT_PATH/bootstrap/cache"
echo -e "${GREEN}Permissions set successfully.${NC}"
echo ""

# Step 5: Install Composer dependencies
print_step "5" "Installing Composer dependencies"
cd "$PROJECT_PATH"
composer install --optimize-autoloader --no-dev --no-interaction
echo -e "${GREEN}Composer dependencies installed.${NC}"
echo ""

# Step 6: Create .env file
print_step "6" "Creating .env configuration file"
cat > "$PROJECT_PATH/.env" << 'EOF'
APP_NAME="Al Takaful Al Sehi"
APP_ENV=production
APP_KEY=base64:zB84TuVZU65gqoPjkEDMH5k559rC6KEAby/wNJXPsmE=
APP_DEBUG=false
APP_URL=https://new.altakafulalsehi.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=altakafulalsehi_new
DB_USERNAME=altakafulalsehi_new
DB_PASSWORD=altakafulalsehi_new

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@altakafulalsehi.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
EOF
echo -e "${GREEN}.env file created successfully.${NC}"
echo ""

# Step 7: Setup database
print_step "7" "Setting up database"
mysql -u root -p << EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'%';
FLUSH PRIVILEGES;
EOF
echo -e "${GREEN}Database setup completed.${NC}"
echo ""

# Step 8: Run Laravel commands
print_step "8" "Running Laravel optimization commands"
cd "$PROJECT_PATH"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
echo -e "${GREEN}Laravel optimization completed.${NC}"
echo ""

# Step 9: Run migrations
print_step "9" "Running database migrations"
php artisan migrate --force
echo -e "${GREEN}Database migrations completed.${NC}"
echo ""

# Step 10: Seed database
print_step "10" "Seeding database"
php artisan db:seed --force
echo -e "${GREEN}Database seeding completed.${NC}"
echo ""

# Step 11: Final permissions
print_step "11" "Setting final permissions"
chown -R www-data:www-data "$PROJECT_PATH"
chmod -R 755 "$PROJECT_PATH"
chmod -R 775 "$PROJECT_PATH/storage"
chmod -R 775 "$PROJECT_PATH/bootstrap/cache"
echo -e "${GREEN}Final permissions set.${NC}"
echo ""

# Step 12: Test application
print_step "12" "Testing application"
echo "Testing application accessibility..."
if curl -s -o /dev/null -w "%{http_code}" "https://$DOMAIN" | grep -q "200\|301\|302"; then
    echo -e "${GREEN}Application is accessible!${NC}"
else
    echo -e "${YELLOW}Application may not be fully accessible yet. Check Apache/Nginx configuration.${NC}"
fi
echo ""

echo -e "${BLUE}=== Deployment Complete! ===${NC}"
echo -e "${GREEN}Website URL: https://$DOMAIN${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Configure Apache/Nginx virtual host"
echo "2. Setup SSL certificate"
echo "3. Test all application features"
echo "4. Monitor error logs"
echo ""
echo -e "${BLUE}Log files location:${NC}"
echo "- Application logs: $PROJECT_PATH/storage/logs/"
echo "- Apache logs: /var/log/apache2/"
echo ""
