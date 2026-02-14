-- Add local sequential ID for hospitals
ALTER TABLE patient ADD COLUMN hospital_patient_id INT(11) DEFAULT 0 AFTER hospital_id;
ALTER TABLE patient ADD INDEX idx_hosp_local_id (hospital_id, hospital_patient_id);

-- Optional: Backfill existing patients (simple re-sequencing for existing data)
-- This is complex in pure SQL without stored procedures, so we might skip auto-backfill or advise manual.
-- For now, new patients will start getting IDs.
