-- Add timezone column to settings (only if it does NOT exist)
ALTER TABLE `settings`
  ADD COLUMN IF NOT EXISTS `timezone` VARCHAR(10) NOT NULL DEFAULT '+3' COMMENT 'Timezone offset for default timezone';

-- Add barcode column to medicine (only if it does NOT exist)
ALTER TABLE `medicine`
  ADD COLUMN IF NOT EXISTS `barcode` VARCHAR(100) NULL COMMENT 'Barcode for medicine lookup';

-- Create hl7_messages table (only if it does NOT exist)
CREATE TABLE IF NOT EXISTS `hl7_messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `message_type` VARCHAR(50) NOT NULL,
  `payload` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hospital_id` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create dicom_files table (only if it does NOT exist)
CREATE TABLE IF NOT EXISTS `dicom_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `file_path` VARCHAR(255) NOT NULL,
  `patient_id` INT NOT NULL,
  `study_date` DATE NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hospital_id` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
