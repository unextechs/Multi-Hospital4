-- Add Global MRN and Local Hospital ID to patient table
ALTER TABLE `patient` ADD `medical_record_number` VARCHAR(50) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `patient` ADD `hospital_patient_id` VARCHAR(50) NULL DEFAULT NULL AFTER `medical_record_number`;
ALTER TABLE `patient` ADD INDEX `idx_mrn` (`medical_record_number`);

-- Add Queue Number and Slot Time to appointment table
ALTER TABLE `appointment` ADD `queue_number` INT(11) NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `appointment` ADD `slot_time` TIME NULL DEFAULT NULL AFTER `time_slot`;

-- Create Doctor Daily Queue table
CREATE TABLE `doctor_daily_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` varchar(100) NOT NULL,
  `hospital_id` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `current_number` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_daily_queue` (`doctor_id`, `hospital_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
