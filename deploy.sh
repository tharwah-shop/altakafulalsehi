#!/bin/bash

# Deploy script for Al Takaful Al Sehi System
# Server: 185.201.8.42
# User: root
# Password: 8hHHf7Ld'FwHbI8r1hm
# Database: altakafulalsehi_new
# DB User: altakafulalsehi_new
# DB Password: altakafulalsehi_new

echo "=== Al Takaful Al Sehi Deployment Script ==="
echo "Target Server: 185.201.8.42"
echo "Target Path: /root/public_html/new.altakafulalsehi.com"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Step 1: Uploading ZIP file to server...${NC}"
echo "Command: scp altakafulalsehi_production.zip root@185.201.8.42:/root/"
echo "Password: 8hHHf7Ld'FwHbI8r1hm"
echo ""

echo -e "${YELLOW}Step 2: Connect to server and extract files...${NC}"
echo "Command: ssh root@185.201.8.42"
echo "Password: 8hHHf7Ld'FwHbI8r1hm"
echo ""

echo "=== Commands to run on server ==="
echo ""

echo -e "${GREEN}1. Navigate to target directory:${NC}"
echo "cd /root/public_html/new.altakafulalsehi.com"
echo ""

echo -e "${GREEN}2. Backup existing files (if any):${NC}"
echo "if [ -d \"backup_$(date +%Y%m%d_%H%M%S)\" ]; then"
echo "  mv * backup_$(date +%Y%m%d_%H%M%S)/"
echo "fi"
echo ""

echo -e "${GREEN}3. Extract the uploaded ZIP file:${NC}"
echo "unzip /root/altakafulalsehi_production.zip -d /root/public_html/new.altakafulalsehi.com/"
echo ""

echo -e "${GREEN}4. Set proper permissions:${NC}"
echo "chown -R www-data:www-data /root/public_html/new.altakafulalsehi.com/"
echo "chmod -R 755 /root/public_html/new.altakafulalsehi.com/"
echo "chmod -R 775 /root/public_html/new.altakafulalsehi.com/storage/"
echo "chmod -R 775 /root/public_html/new.altakafulalsehi.com/bootstrap/cache/"
echo ""

echo -e "${GREEN}5. Install Composer dependencies:${NC}"
echo "cd /root/public_html/new.altakafulalsehi.com/"
echo "composer install --optimize-autoloader --no-dev"
echo ""

echo -e "${GREEN}6. Create .env file:${NC}"
echo "cat > .env << 'EOF'"
echo "APP_NAME=\"Al Takaful Al Sehi\""
echo "APP_ENV=production"
echo "APP_KEY=base64:zB84TuVZU65gqoPjkEDMH5k559rC6KEAby/wNJXPsmE="
echo "APP_DEBUG=false"
echo "APP_URL=https://new.altakafulalsehi.com"
echo ""
echo "APP_LOCALE=en"
echo "APP_FALLBACK_LOCALE=en"
echo "APP_FAKER_LOCALE=en_US"
echo ""
echo "APP_MAINTENANCE_DRIVER=file"
echo "PHP_CLI_SERVER_WORKERS=4"
echo "BCRYPT_ROUNDS=12"
echo ""
echo "LOG_CHANNEL=stack"
echo "LOG_STACK=single"
echo "LOG_DEPRECATIONS_CHANNEL=null"
echo "LOG_LEVEL=error"
echo ""
echo "DB_CONNECTION=mysql"
echo "DB_HOST=127.0.0.1"
echo "DB_PORT=3306"
echo "DB_DATABASE=altakafulalsehi_new"
echo "DB_USERNAME=altakafulalsehi_new"
echo "DB_PASSWORD=altakafulalsehi_new"
echo ""
echo "SESSION_DRIVER=database"
echo "SESSION_LIFETIME=120"
echo "SESSION_ENCRYPT=false"
echo "SESSION_PATH=/"
echo "SESSION_DOMAIN=null"
echo ""
echo "BROADCAST_CONNECTION=log"
echo "FILESYSTEM_DISK=local"
echo "QUEUE_CONNECTION=database"
echo ""
echo "CACHE_STORE=database"
echo ""
echo "MEMCACHED_HOST=127.0.0.1"
echo ""
echo "REDIS_CLIENT=phpredis"
echo "REDIS_HOST=127.0.0.1"
echo "REDIS_PASSWORD=null"
echo "REDIS_PORT=6379"
echo ""
echo "MAIL_MAILER=log"
echo "MAIL_SCHEME=null"
echo "MAIL_HOST=127.0.0.1"
echo "MAIL_PORT=2525"
echo "MAIL_USERNAME=null"
echo "MAIL_PASSWORD=null"
echo "MAIL_FROM_ADDRESS=\"noreply@altakafulalsehi.com\""
echo "MAIL_FROM_NAME=\"\${APP_NAME}\""
echo ""
echo "AWS_ACCESS_KEY_ID="
echo "AWS_SECRET_ACCESS_KEY="
echo "AWS_DEFAULT_REGION=us-east-1"
echo "AWS_BUCKET="
echo "AWS_USE_PATH_STYLE_ENDPOINT=false"
echo ""
echo "VITE_APP_NAME=\"\${APP_NAME}\""
echo "EOF"
echo ""

echo -e "${GREEN}7. Run Laravel optimization commands:${NC}"
echo "php artisan config:cache"
echo "php artisan route:cache"
echo "php artisan view:cache"
echo "php artisan storage:link"
echo ""

echo -e "${GREEN}8. Run database migrations:${NC}"
echo "php artisan migrate --force"
echo ""

echo -e "${GREEN}9. Seed database (if needed):${NC}"
echo "php artisan db:seed --force"
echo ""

echo -e "${GREEN}10. Final permissions check:${NC}"
echo "chown -R www-data:www-data /root/public_html/new.altakafulalsehi.com/"
echo "chmod -R 755 /root/public_html/new.altakafulalsehi.com/"
echo "chmod -R 775 /root/public_html/new.altakafulalsehi.com/storage/"
echo "chmod -R 775 /root/public_html/new.altakafulalsehi.com/bootstrap/cache/"
echo ""

echo -e "${GREEN}11. Test the application:${NC}"
echo "curl -I https://new.altakafulalsehi.com"
echo ""

echo -e "${YELLOW}=== Deployment Complete! ===${NC}"
echo "Website should be accessible at: https://new.altakafulalsehi.com"
echo ""

echo -e "${RED}Important Notes:${NC}"
echo "1. Make sure MySQL database 'altakafulalsehi_new' exists"
echo "2. Make sure MySQL user 'altakafulalsehi_new' has proper permissions"
echo "3. Check Apache/Nginx configuration for the domain"
echo "4. Verify SSL certificate is properly configured"
echo "5. Test all functionality after deployment"
