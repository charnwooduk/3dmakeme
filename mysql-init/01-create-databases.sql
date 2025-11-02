-- Create Development and Production Databases
-- This script runs on container initialization

-- Create development database (if not exists)
CREATE DATABASE IF NOT EXISTS wordpress_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create production database (if not exists)
CREATE DATABASE IF NOT EXISTS wordpress_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create development user
CREATE USER IF NOT EXISTS 'wordpress_dev'@'%' IDENTIFIED BY 'wordpress_password';
GRANT ALL PRIVILEGES ON wordpress_dev.* TO 'wordpress_dev'@'%';

-- Create production user
CREATE USER IF NOT EXISTS 'wordpress_prod'@'%' IDENTIFIED BY 'wordpress_password';
GRANT ALL PRIVILEGES ON wordpress_prod.* TO 'wordpress_prod'@'%';

-- Flush privileges to ensure all changes take effect
FLUSH PRIVILEGES;

SELECT 'Databases and users created successfully!' AS Status;
