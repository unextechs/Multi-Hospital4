-- Insurance Claims Migration
-- Creates tables for insurance claims and patient insurance links

-- Insurance Claims Table
CREATE TABLE IF NOT EXISTS `insurance_claims` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `claim_number` varchar(50) DEFAULT NULL,
    `patient_id` int(11) NOT NULL,
    `patient_name` varchar(255) DEFAULT NULL,
    `insurance_company_id` int(11) NOT NULL,
    `insurance_company_name` varchar(255) DEFAULT NULL,
    `policy_number` varchar(100) DEFAULT NULL,
    `invoice_id` int(11) DEFAULT NULL,
    `claim_amount` decimal(15,2) DEFAULT 0.00,
    `approved_amount` decimal(15,2) DEFAULT 0.00,
    `copay_amount` decimal(15,2) DEFAULT 0.00,
    `deductible` decimal(15,2) DEFAULT 0.00,
    `claim_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `service_date` date DEFAULT NULL,
    `diagnosis_codes` text COMMENT 'ICD codes JSON array',
    `procedure_codes` text COMMENT 'CPT codes JSON array',
    `claim_items` text COMMENT 'JSON array of claim line items',
    `supporting_documents` text COMMENT 'JSON array of document paths',
    `status` enum('draft','submitted','pending','approved','rejected','paid','appealed') DEFAULT 'draft',
    `submission_date` datetime DEFAULT NULL,
    `approval_date` datetime DEFAULT NULL,
    `payment_date` datetime DEFAULT NULL,
    `rejection_reason` text,
    `notes` text,
    `submitted_by` int(11) DEFAULT NULL,
    `approved_by` int(11) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `claim_number` (`claim_number`),
    KEY `hospital_id` (`hospital_id`),
    KEY `patient_id` (`patient_id`),
    KEY `insurance_company_id` (`insurance_company_id`),
    KEY `invoice_id` (`invoice_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Patient Insurance Table
CREATE TABLE IF NOT EXISTS `patient_insurance` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `insurance_company_id` int(11) NOT NULL,
    `policy_number` varchar(100) NOT NULL,
    `group_number` varchar(100) DEFAULT NULL,
    `subscriber_id` varchar(100) DEFAULT NULL,
    `subscriber_name` varchar(255) DEFAULT NULL,
    `subscriber_relationship` varchar(50) DEFAULT NULL COMMENT 'self, spouse, child, other',
    `coverage_type` varchar(50) DEFAULT 'primary' COMMENT 'primary, secondary, tertiary',
    `coverage_percent` decimal(5,2) DEFAULT 80.00,
    `copay_amount` decimal(15,2) DEFAULT 0.00,
    `deductible_amount` decimal(15,2) DEFAULT 0.00,
    `deductible_met` decimal(15,2) DEFAULT 0.00,
    `max_out_of_pocket` decimal(15,2) DEFAULT 0.00,
    `out_of_pocket_met` decimal(15,2) DEFAULT 0.00,
    `effective_date` date DEFAULT NULL,
    `expiry_date` date DEFAULT NULL,
    `is_verified` tinyint(1) DEFAULT 0,
    `verification_date` datetime DEFAULT NULL,
    `verified_by` int(11) DEFAULT NULL,
    `status` enum('active','inactive','expired') DEFAULT 'active',
    `notes` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `patient_id` (`patient_id`),
    KEY `insurance_company_id` (`insurance_company_id`),
    KEY `policy_number` (`policy_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insurance Claim Items Table
CREATE TABLE IF NOT EXISTS `insurance_claim_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `claim_id` int(11) NOT NULL,
    `service_code` varchar(50) DEFAULT NULL,
    `service_description` text,
    `service_date` date DEFAULT NULL,
    `quantity` int(11) DEFAULT 1,
    `unit_price` decimal(15,2) DEFAULT 0.00,
    `total_price` decimal(15,2) DEFAULT 0.00,
    `approved_amount` decimal(15,2) DEFAULT 0.00,
    `diagnosis_pointer` varchar(10) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `claim_id` (`claim_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update existing insurance_company table if needed
ALTER TABLE `insurance_company`
    ADD COLUMN IF NOT EXISTS `email` varchar(255) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `phone` varchar(50) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `address` text,
    ADD COLUMN IF NOT EXISTS `claims_email` varchar(255) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `claims_phone` varchar(50) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `payer_id` varchar(50) DEFAULT NULL COMMENT 'Electronic payer ID',
    ADD COLUMN IF NOT EXISTS `default_coverage_percent` decimal(5,2) DEFAULT 80.00,
    ADD COLUMN IF NOT EXISTS `notes` text,
    ADD COLUMN IF NOT EXISTS `status` enum('active','inactive') DEFAULT 'active';
