-- ============================================
-- Hospital Management System - Database Setup
-- Patient ID Migration and Workspace Services
-- ============================================

-- 1. PATIENT ID MIGRATION
-- Renumber existing patients to have sequential hospital_patient_id per hospital
-- ============================================

-- This will update all existing patients to have sequential IDs
-- Run this carefully - it modifies existing data!

SET @counter = 0;
SET @current_hospital = 0;

UPDATE patient p1
JOIN (
    SELECT 
        id,
        hospital_id,
        @counter := IF(@current_hospital = hospital_id, @counter + 1, 1) AS new_id,
        @current_hospital := hospital_id
    FROM patient
    ORDER BY hospital_id ASC, id ASC
) p2 ON p1.id = p2.id
SET p1.hospital_patient_id = p2.new_id;

-- ============================================
-- 2. DAY CARE REQUESTS TABLE
-- ============================================

CREATE TABLE IF NOT EXISTS `daycare_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `admission_date` date NOT NULL,
  `duration` int(11) DEFAULT 1 COMMENT 'Expected duration in days',
  `reason` text NOT NULL COMMENT 'Reason for day care admission',
  `instructions` text COMMENT 'Special care instructions',
  `fee` decimal(10,2) DEFAULT 0.00 COMMENT 'Day care fee',
  `payment_status` enum('unpaid','paid','waived') DEFAULT 'unpaid',
  `status` varchar(50) DEFAULT 'pending' COMMENT 'pending, approved, admitted, discharged, cancelled',
  `request_date` datetime NOT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL COMMENT 'User ID who approved',
  `hospital_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_patient` (`patient_id`),
  KEY `idx_doctor` (`doctor_id`),
  KEY `idx_hospital` (`hospital_id`),
  KEY `idx_status` (`status`),
  KEY `idx_admission_date` (`admission_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Day care admission requests from doctors';

-- ============================================
-- 3. EMERGENCY REQUESTS TABLE
-- ============================================

CREATE TABLE IF NOT EXISTS `emergency_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `patient_phone` varchar(50) DEFAULT NULL,
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `priority` enum('critical','high','medium') NOT NULL COMMENT 'Priority level',
  `chief_complaint` varchar(255) NOT NULL COMMENT 'Main presenting complaint',
  `assessment` text NOT NULL COMMENT 'Clinical assessment and findings',
  `immediate_actions` text COMMENT 'Immediate interventions required',
  `fee` decimal(10,2) DEFAULT 0.00 COMMENT 'Emergency admission fee',
  `payment_status` enum('unpaid','paid','waived') DEFAULT 'unpaid',
  `status` varchar(50) DEFAULT 'pending' COMMENT 'pending, acknowledged, admitted, treated, discharged, cancelled',
  `request_date` datetime NOT NULL,
  `acknowledged_date` datetime DEFAULT NULL COMMENT 'When emergency dept acknowledged',
  `acknowledged_by` int(11) DEFAULT NULL COMMENT 'Emergency staff who acknowledged',
  `hospital_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_patient` (`patient_id`),
  KEY `idx_doctor` (`doctor_id`),
  KEY `idx_hospital` (`hospital_id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_request_date` (`request_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Emergency admission requests from doctors';

-- ============================================
-- 4. HOSPITAL SETTINGS FOR FEES
-- ============================================

-- Add fee columns to settings table if they don't exist
ALTER TABLE `settings` 
ADD COLUMN IF NOT EXISTS `daycare_fee` decimal(10,2) DEFAULT 10.00 COMMENT 'Default day care admission fee',
ADD COLUMN IF NOT EXISTS `emergency_fee` decimal(10,2) DEFAULT 5.00 COMMENT 'Default emergency admission fee';

-- Update existing hospitals with default fees (if not already set)
UPDATE `settings` 
SET 
    `daycare_fee` = IFNULL(`daycare_fee`, 10.00),
    `emergency_fee` = IFNULL(`emergency_fee`, 5.00)
WHERE `daycare_fee` IS NULL OR `emergency_fee` IS NULL;

-- ============================================
-- 5. SAMPLE DATA (OPTIONAL - REMOVE IF NOT NEEDED)
-- ============================================

-- Set custom fees for specific hospitals (example)
-- UPDATE settings SET daycare_fee = 15.00, emergency_fee = 8.00 WHERE hospital_id = 1;

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Check patient IDs are sequential per hospital
-- SELECT hospital_id, hospital_patient_id, name 
-- FROM patient 
-- WHERE hospital_id = 1 
-- ORDER BY hospital_patient_id ASC 
-- LIMIT 20;

-- Check if tables were created
-- SHOW TABLES LIKE '%requests';

-- Check settings fees
-- SELECT hospital_id, hospital_name, daycare_fee, emergency_fee FROM settings;

-- ============================================
-- NOTES:
-- 1. Run the patient ID migration carefully - it modifies existing data
-- 2. Adjust default fees in the ALTER TABLE statement as needed
-- 3. Fee amounts are in the hospital's default currency
-- 4. Payment integration should be added separately
-- ============================================
