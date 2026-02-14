-- Fix missing columns in radiology_orders table
-- Run this if you already have an older version of the table
-- Run each statement separately if needed

ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `subtotal` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `discount_percent` decimal(5,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `discount_amount` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `flat_discount` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `vat_percent` decimal(5,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `vat_amount` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `gross_total` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `amount_received` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `due_amount` decimal(15,2) DEFAULT 0.00;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `payment_status` enum('paid','partial','unpaid') DEFAULT 'unpaid';
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `payment_type` varchar(50) DEFAULT 'Cash';
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `barcode` varchar(100) DEFAULT NULL;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `urgency` enum('routine','urgent','stat') DEFAULT 'routine';
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `clinical_history` text;
ALTER TABLE `radiology_orders` ADD COLUMN IF NOT EXISTS `clinical_notes` text;
