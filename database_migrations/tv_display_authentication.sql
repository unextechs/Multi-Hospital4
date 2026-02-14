-- TV Display Enhancement - Database Migration
-- Add TV access token and display enable/disable fields to settings table

ALTER TABLE `settings` 
ADD COLUMN `tv_access_token` VARCHAR(255) DEFAULT NULL COMMENT 'Secure token for TV display access',
ADD COLUMN `tv_display_enabled` TINYINT(1) DEFAULT 1 COMMENT '1=enabled, 0=disabled',
ADD INDEX `idx_tv_token` (`tv_access_token`);

-- Optional: Generate initial tokens for existing hospitals
-- UPDATE `settings` 
-- SET `tv_access_token` = SHA2(CONCAT(hospital_id, UNIX_TIMESTAMP(), RAND()), 256)
-- WHERE `tv_access_token` IS NULL;
