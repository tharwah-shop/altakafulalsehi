#!/bin/bash

# System Health Check Script for Al Takaful Al Sehi
# Run this script on the server to verify system health

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="new.altakafulalsehi.com"
PROJECT_PATH="/root/public_html/$DOMAIN"
DB_NAME="altakafulalsehi_new"
DB_USER="altakafulalsehi_new"

echo -e "${BLUE}=== Al Takaful Al Sehi System Health Check ===${NC}"
echo -e "${BLUE}Domain: $DOMAIN${NC}"
echo -e "${BLUE}Project Path: $PROJECT_PATH${NC}"
echo ""

# Function to check status
check_status() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✓ PASS${NC}"
        return 0
    else
        echo -e "${RED}✗ FAIL${NC}"
        return 1
    fi
}

# Function to print test
print_test() {
    printf "%-50s" "$1"
}

TOTAL_TESTS=0
PASSED_TESTS=0

# Test 1: Check if project directory exists
print_test "Project directory exists"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -d "$PROJECT_PATH" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 2: Check if .env file exists
print_test ".env configuration file exists"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -f "$PROJECT_PATH/.env" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 3: Check if vendor directory exists
print_test "Composer dependencies installed"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -d "$PROJECT_PATH/vendor" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 4: Check storage permissions
print_test "Storage directory permissions"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -w "$PROJECT_PATH/storage" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 5: Check bootstrap/cache permissions
print_test "Bootstrap cache permissions"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -w "$PROJECT_PATH/bootstrap/cache" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 6: Check database connection
print_test "Database connection"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
cd "$PROJECT_PATH"
if php artisan migrate:status >/dev/null 2>&1; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 7: Check if storage link exists
print_test "Storage symbolic link"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -L "$PROJECT_PATH/public/storage" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 8: Check Apache configuration
print_test "Apache virtual host configuration"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if apache2ctl -S 2>/dev/null | grep -q "$DOMAIN"; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 9: Check if website is accessible
print_test "Website accessibility (HTTP)"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://$DOMAIN" 2>/dev/null)
if [[ "$HTTP_CODE" =~ ^(200|301|302)$ ]]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 10: Check if HTTPS is working
print_test "HTTPS accessibility"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
HTTPS_CODE=$(curl -s -o /dev/null -w "%{http_code}" "https://$DOMAIN" 2>/dev/null)
if [[ "$HTTPS_CODE" =~ ^(200|301|302)$ ]]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 11: Check PHP version
print_test "PHP version (8.2+)"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
PHP_VERSION=$(php -r "echo PHP_VERSION_ID;")
if [ "$PHP_VERSION" -ge 80200 ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 12: Check required PHP extensions
print_test "Required PHP extensions"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
REQUIRED_EXTENSIONS=("pdo" "pdo_mysql" "mbstring" "openssl" "tokenizer" "xml" "ctype" "json" "bcmath" "gd")
ALL_EXTENSIONS_OK=true

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if ! php -m | grep -q "^$ext$"; then
        ALL_EXTENSIONS_OK=false
        break
    fi
done

if $ALL_EXTENSIONS_OK; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 13: Check Laravel cache files
print_test "Laravel cache optimization"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -f "$PROJECT_PATH/bootstrap/cache/config.php" ] && [ -f "$PROJECT_PATH/bootstrap/cache/routes-v7.php" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 14: Check log files
print_test "Log files accessibility"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
if [ -d "$PROJECT_PATH/storage/logs" ] && [ -w "$PROJECT_PATH/storage/logs" ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

# Test 15: Check database tables
print_test "Database tables exist"
TOTAL_TESTS=$((TOTAL_TESTS + 1))
cd "$PROJECT_PATH"
TABLE_COUNT=$(php artisan tinker --execute="echo \DB::select('SHOW TABLES')" 2>/dev/null | grep -c "Tables_in_" || echo "0")
if [ "$TABLE_COUNT" -gt 10 ]; then
    check_status 0
    PASSED_TESTS=$((PASSED_TESTS + 1))
else
    check_status 1
fi

echo ""
echo -e "${BLUE}=== Test Summary ===${NC}"
echo -e "Total Tests: $TOTAL_TESTS"
echo -e "Passed: ${GREEN}$PASSED_TESTS${NC}"
echo -e "Failed: ${RED}$((TOTAL_TESTS - PASSED_TESTS))${NC}"

PASS_PERCENTAGE=$((PASSED_TESTS * 100 / TOTAL_TESTS))
echo -e "Success Rate: ${GREEN}$PASS_PERCENTAGE%${NC}"

echo ""
if [ $PASSED_TESTS -eq $TOTAL_TESTS ]; then
    echo -e "${GREEN}🎉 All tests passed! System is healthy.${NC}"
elif [ $PASS_PERCENTAGE -ge 80 ]; then
    echo -e "${YELLOW}⚠️  Most tests passed. Minor issues detected.${NC}"
else
    echo -e "${RED}❌ Multiple issues detected. System needs attention.${NC}"
fi

echo ""
echo -e "${BLUE}=== Additional Information ===${NC}"
echo -e "PHP Version: $(php -r 'echo PHP_VERSION;')"
echo -e "Laravel Version: $(cd $PROJECT_PATH && php artisan --version 2>/dev/null || echo 'Unknown')"
echo -e "Disk Usage: $(df -h $PROJECT_PATH | tail -1 | awk '{print $5}') used"
echo -e "Memory Usage: $(free -h | grep '^Mem:' | awk '{print $3 "/" $2}')"

echo ""
echo -e "${BLUE}=== Quick Commands ===${NC}"
echo "View Laravel logs: tail -f $PROJECT_PATH/storage/logs/laravel.log"
echo "View Apache error logs: tail -f /var/log/apache2/${DOMAIN}_error.log"
echo "Restart Apache: systemctl restart apache2"
echo "Clear Laravel cache: cd $PROJECT_PATH && php artisan cache:clear"
