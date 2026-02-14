-- TV Display Color Customization - Database Migration
-- Add custom color fields to settings table for multi-hospital branding

ALTER TABLE `settings` 
ADD COLUMN `tv_primary_color` VARCHAR(7) DEFAULT '#667eea' COMMENT 'Primary gradient color for TV display',
ADD COLUMN `tv_secondary_color` VARCHAR(7) DEFAULT '#764ba2' COMMENT 'Secondary gradient color for TV display',
ADD COLUMN `tv_accent_color` VARCHAR(7) DEFAULT '#f093fb' COMMENT 'Accent color for TV display elements';

-- Set default colors for existing hospitals
UPDATE `settings` 
SET 
    `tv_primary_color` = '#667eea',
    `tv_secondary_color` = '#764ba2',
    `tv_accent_color` = '#f093fb'
WHERE `tv_primary_color` IS NULL;
