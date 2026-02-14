CREATE TABLE `queue_entries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `hospital_id` INT(11) NOT NULL,
  `type` ENUM('Doctor', 'Pharmacy', 'Lab', 'Radiology') NOT NULL,
  `type_id` INT(11) NULL DEFAULT 0,
  `date` VARCHAR(100) NOT NULL, -- Storing as timestamp string or int, consistent with other tables
  `queue_number` INT(11) NOT NULL,
  `patient_id` INT(11) NULL,
  `status` VARCHAR(50) DEFAULT 'Waiting',
  `created_at` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_queue_fetch` (`hospital_id`, `type`, `type_id`, `date`, `status`)
);
