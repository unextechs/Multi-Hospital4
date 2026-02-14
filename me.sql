-- 1. Create Daily Queue table
CREATE TABLE IF NOT EXISTS `daily_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` ENUM('Doctor', 'Pharmacy', 'Lab', 'Radiology') NOT NULL DEFAULT 'Doctor',
  `type_id` int(11) NOT NULL DEFAULT 0,
  `hospital_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `current_number` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_daily_queue` (`type`, `type_id`, `hospital_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- 2. Create Queue Entries table
CREATE TABLE IF NOT EXISTS `queue_entries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `hospital_id` INT(11) NOT NULL,
  `type` ENUM('Doctor', 'Pharmacy', 'Lab', 'Radiology') NOT NULL,
  `type_id` INT(11) NULL DEFAULT 0,
  `date` VARCHAR(100) NOT NULL,
  `queue_number` INT(11) NOT NULL,
  `patient_id` INT(11) NULL,
  `status` VARCHAR(50) DEFAULT 'Waiting',
  `created_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_queue_fetch` (`hospital_id`, `type`, `type_id`, `date`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- 3. Ensure appointment table has the queue_number column
-- If you get an error here, it means the column already exists, which is fine.
ALTER TABLE `appointment` ADD COLUMN `queue_number` INT(11) NULL DEFAULT NULL AFTER `status`;