-- Sidebar Color Customization - Database Migration
-- Add custom sidebar color field to settings table safely

-- Ensure the correct database is selected
USE `hms_db`;

-- Add the column only if it doesn't exist (Supported in MariaDB 10.2.8+)
ALTER TABLE `settings` 
ADD COLUMN IF NOT EXISTS `sidebar_color` VARCHAR(7) DEFAULT '#2c3e50' COMMENT 'Background color for the main sidebar';

-- Set default color for existing hospitals
UPDATE `settings` 
SET `sidebar_color` = '#2c3e50'
WHERE `sidebar_color` IS NULL OR `sidebar_color` = '';
