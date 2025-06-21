-- Database setup for Al Takaful Al Sehi System
-- Run this as MySQL root user

-- Create database
CREATE DATABASE IF NOT EXISTS altakafulalsehi_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user and grant permissions
CREATE USER IF NOT EXISTS 'altakafulalsehi_new'@'localhost' IDENTIFIED BY 'altakafulalsehi_new';
CREATE USER IF NOT EXISTS 'altakafulalsehi_new'@'%' IDENTIFIED BY 'altakafulalsehi_new';

-- Grant all privileges on the database
GRANT ALL PRIVILEGES ON altakafulalsehi_new.* TO 'altakafulalsehi_new'@'localhost';
GRANT ALL PRIVILEGES ON altakafulalsehi_new.* TO 'altakafulalsehi_new'@'%';

-- Flush privileges
FLUSH PRIVILEGES;

-- Show databases and users for verification
SHOW DATABASES LIKE 'altakafulalsehi_new';
SELECT User, Host FROM mysql.user WHERE User = 'altakafulalsehi_new';

-- Test connection (optional)
-- mysql -u altakafulalsehi_new -p altakafulalsehi_new
