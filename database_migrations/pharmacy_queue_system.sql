-- =============================================
-- Pharmacy Queue System Database Migration
-- =============================================
-- Creates automatic queue for pharmacy when doctors add prescriptions
-- Includes patient info, medicines, prices, and processing status

CREATE TABLE IF NOT EXISTS `pharmacy_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prescription_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `doctor_name` varchar(255) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `medicines_data` text COMMENT 'JSON encoded medicine details with dosage, frequency, days, instructions',
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `queue_number` int(11) DEFAULT NULL COMMENT 'Daily queue number for fair ordering',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL COMMENT 'Pharmacist user ID who processed',
  `processed_at` datetime DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL COMMENT 'Link to pharmacy_payment table after sale',
  `notes` text COMMENT 'Additional notes or cancellation reason',
  PRIMARY KEY (`id`),
  KEY `prescription_id` (`prescription_id`),
  KEY `patient_id` (`patient_id`),
  KEY `status` (`status`),
  KEY `hospital_id` (`hospital_id`),
  KEY `created_at` (`created_at`),
  KEY `queue_number` (`queue_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Automatic pharmacy queue for prescription fulfillment';

-- Add index for today's queue lookups
CREATE INDEX idx_daily_queue ON pharmacy_queue(hospital_id, created_at, status);

-- =============================================
-- Sample Data (Optional - for testing)
-- =============================================
-- This will be populated automatically when doctors create prescriptions
