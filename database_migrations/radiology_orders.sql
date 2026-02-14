-- Radiology Orders Migration
-- Creates tables for radiology orders with discount and barcode support

-- Radiology Orders Table
CREATE TABLE IF NOT EXISTS `radiology_orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `order_number` varchar(50) DEFAULT NULL,
    `barcode` varchar(100) DEFAULT NULL,
    `patient_id` int(11) NOT NULL,
    `patient_name` varchar(255) DEFAULT NULL,
    `patient_phone` varchar(50) DEFAULT NULL,
    `patient_age` int(11) DEFAULT NULL,
    `patient_gender` varchar(20) DEFAULT NULL,
    `patient_address` text,
    `doctor_id` int(11) DEFAULT NULL,
    `doctor_name` varchar(255) DEFAULT NULL,
    `test_ids` text COMMENT 'Comma-separated test IDs',
    `test_details` text COMMENT 'JSON encoded test details with quantities',
    `subtotal` decimal(15,2) DEFAULT 0.00,
    `discount_percent` decimal(5,2) DEFAULT 0.00,
    `discount_amount` decimal(15,2) DEFAULT 0.00,
    `flat_discount` decimal(15,2) DEFAULT 0.00,
    `vat_percent` decimal(5,2) DEFAULT 0.00,
    `vat_amount` decimal(15,2) DEFAULT 0.00,
    `gross_total` decimal(15,2) DEFAULT 0.00,
    `amount_received` decimal(15,2) DEFAULT 0.00,
    `due_amount` decimal(15,2) DEFAULT 0.00,
    `payment_status` enum('paid','partial','unpaid') DEFAULT 'unpaid',
    `payment_type` varchar(50) DEFAULT 'Cash',
    `insurance_company_id` int(11) DEFAULT NULL,
    `insurance_details` text,
    `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `appointment_date` datetime DEFAULT NULL,
    `urgency` enum('routine','urgent','stat') DEFAULT 'routine',
    `clinical_history` text,
    `clinical_notes` text,
    `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
    `created_by` int(11) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `barcode` (`barcode`),
    KEY `hospital_id` (`hospital_id`),
    KEY `patient_id` (`patient_id`),
    KEY `doctor_id` (`doctor_id`),
    KEY `status` (`status`),
    KEY `order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Radiology Tests Table
CREATE TABLE IF NOT EXISTS `radiology_tests` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `category_id` int(11) DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `description` text,
    `price` decimal(15,2) DEFAULT 0.00,
    `duration_minutes` int(11) DEFAULT 30,
    `preparation_instructions` text,
    `status` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Radiology Categories Table
CREATE TABLE IF NOT EXISTS `radiology_categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text,
    `status` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Radiology Reports Table
CREATE TABLE IF NOT EXISTS `radiology_reports` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `order_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `report_title` varchar(255) DEFAULT NULL,
    `report_content` longtext,
    `findings` text,
    `impression` text,
    `recommendations` text,
    `radiologist_id` int(11) DEFAULT NULL,
    `radiologist_name` varchar(255) DEFAULT NULL,
    `report_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `status` enum('draft','preliminary','final') DEFAULT 'draft',
    `signed_by` int(11) DEFAULT NULL,
    `signed_at` datetime DEFAULT NULL,
    `attachment_path` varchar(500) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `patient_id` (`patient_id`),
    KEY `hospital_id` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add discount limit columns to settings if not exists
ALTER TABLE `settings` 
    ADD COLUMN IF NOT EXISTS `radiology_discount_limit_admin` decimal(5,2) DEFAULT 100.00,
    ADD COLUMN IF NOT EXISTS `radiology_discount_limit_receptionist` decimal(5,2) DEFAULT 10.00,
    ADD COLUMN IF NOT EXISTS `radiology_discount_limit_doctor` decimal(5,2) DEFAULT 15.00;
