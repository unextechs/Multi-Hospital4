-- HL7/DICOM Settings Migration
-- Creates tables for HL7 connections, DICOM nodes and worklist

-- =========================================
-- HL7 TABLES
-- =========================================

-- HL7 Connections Table
CREATE TABLE IF NOT EXISTS `hl7_connections` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `host` varchar(255) NOT NULL,
    `port` int(11) NOT NULL DEFAULT 2575,
    `connection_type` enum('TCP','MLLP') DEFAULT 'MLLP',
    `sending_application` varchar(100) DEFAULT NULL,
    `sending_facility` varchar(100) DEFAULT NULL,
    `receiving_application` varchar(100) DEFAULT NULL,
    `receiving_facility` varchar(100) DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    `last_connected` datetime DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- HL7 Message Queue Table
CREATE TABLE IF NOT EXISTS `hl7_message_queue` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `connection_id` int(11) DEFAULT NULL,
    `message_type` varchar(50) NOT NULL COMMENT 'ADT, ORM, ORU, etc.',
    `trigger_event` varchar(10) DEFAULT NULL COMMENT 'A01, O01, R01, etc.',
    `control_id` varchar(100) DEFAULT NULL,
    `payload` longtext NOT NULL,
    `direction` enum('inbound','outbound') DEFAULT 'outbound',
    `status` enum('pending','processing','sent','received','failed','acknowledged') DEFAULT 'pending',
    `attempts` int(11) DEFAULT 0,
    `response` text,
    `error_message` text,
    `scheduled_at` datetime DEFAULT NULL,
    `processed_at` datetime DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`),
    KEY `connection_id` (`connection_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- HL7 Message Templates Table
CREATE TABLE IF NOT EXISTS `hl7_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `message_type` varchar(50) NOT NULL,
    `trigger_event` varchar(10) DEFAULT NULL,
    `template` longtext NOT NULL,
    `description` text,
    `status` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- DICOM TABLES
-- =========================================

-- DICOM Nodes Table
CREATE TABLE IF NOT EXISTS `dicom_nodes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `ae_title` varchar(16) NOT NULL COMMENT 'Application Entity Title',
    `host` varchar(255) NOT NULL,
    `port` int(11) NOT NULL DEFAULT 104,
    `node_type` enum('SCP','SCU','BOTH') DEFAULT 'SCU' COMMENT 'Service Class Provider/User',
    `is_default` tinyint(1) DEFAULT 0,
    `status` enum('active','inactive') DEFAULT 'active',
    `last_echo` datetime DEFAULT NULL,
    `last_echo_status` enum('success','failed') DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `hospital_id` (`hospital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DICOM Worklist Table
CREATE TABLE IF NOT EXISTS `dicom_worklist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `patient_name` varchar(255) DEFAULT NULL,
    `patient_birth_date` date DEFAULT NULL,
    `patient_sex` varchar(10) DEFAULT NULL,
    `study_instance_uid` varchar(128) NOT NULL,
    `accession_number` varchar(50) DEFAULT NULL,
    `requested_procedure_id` varchar(50) DEFAULT NULL,
    `scheduled_procedure_step_id` varchar(50) DEFAULT NULL,
    `modality` varchar(20) DEFAULT NULL COMMENT 'CT, MR, XR, US, etc.',
    `scheduled_start_date` datetime DEFAULT NULL,
    `scheduled_end_date` datetime DEFAULT NULL,
    `scheduled_station_ae` varchar(16) DEFAULT NULL,
    `scheduled_station_name` varchar(100) DEFAULT NULL,
    `referring_physician` varchar(255) DEFAULT NULL,
    `procedure_description` text,
    `study_description` text,
    `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
    `radiology_order_id` int(11) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `study_instance_uid` (`study_instance_uid`),
    KEY `hospital_id` (`hospital_id`),
    KEY `patient_id` (`patient_id`),
    KEY `accession_number` (`accession_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DICOM Studies Table
CREATE TABLE IF NOT EXISTS `dicom_studies` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `hospital_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `worklist_id` int(11) DEFAULT NULL,
    `study_instance_uid` varchar(128) NOT NULL,
    `study_date` date DEFAULT NULL,
    `study_time` time DEFAULT NULL,
    `accession_number` varchar(50) DEFAULT NULL,
    `modality` varchar(20) DEFAULT NULL,
    `study_description` text,
    `referring_physician` varchar(255) DEFAULT NULL,
    `performing_physician` varchar(255) DEFAULT NULL,
    `institution_name` varchar(255) DEFAULT NULL,
    `series_count` int(11) DEFAULT 0,
    `images_count` int(11) DEFAULT 0,
    `storage_path` varchar(500) DEFAULT NULL,
    `pacs_location` varchar(500) DEFAULT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `study_instance_uid` (`study_instance_uid`),
    KEY `hospital_id` (`hospital_id`),
    KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Update existing hl7_messages table if exists
ALTER TABLE `hl7_messages` 
    ADD COLUMN IF NOT EXISTS `hospital_id` int(11) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `connection_id` int(11) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `direction` enum('inbound','outbound') DEFAULT 'outbound',
    ADD COLUMN IF NOT EXISTS `processed_at` datetime DEFAULT NULL;

-- Update existing dicom_files table if exists
ALTER TABLE `dicom_files`
    ADD COLUMN IF NOT EXISTS `hospital_id` int(11) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `study_instance_uid` varchar(128) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `series_instance_uid` varchar(128) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `sop_instance_uid` varchar(128) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `modality` varchar(20) DEFAULT NULL;
