-- Rename the table to be more general
RENAME TABLE doctor_daily_queue TO daily_queue;

-- Modify columns to support generalized queues
ALTER TABLE daily_queue CHANGE doctor_id type_id INT(11) NULL DEFAULT 0;
ALTER TABLE daily_queue ADD COLUMN type ENUM('Doctor', 'Pharmacy', 'Lab', 'Radiology') NOT NULL DEFAULT 'Doctor' AFTER hospital_id;

-- Add index for performance
ALTER TABLE daily_queue ADD INDEX idx_queue_lookup (hospital_id, type, type_id, date);
