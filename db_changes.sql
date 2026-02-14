-- Migration script for Daycare and Emergency Invoice Integration

-- Add invoice_id and payment_status to daycare_requests
ALTER TABLE daycare_requests ADD COLUMN IF NOT EXISTS invoice_id INT DEFAULT NULL;
ALTER TABLE daycare_requests ADD COLUMN IF NOT EXISTS payment_status VARCHAR(50) DEFAULT 'unpaid';

-- Add invoice_id and payment_status to emergency_requests
ALTER TABLE emergency_requests ADD COLUMN IF NOT EXISTS invoice_id INT DEFAULT NULL;
ALTER TABLE emergency_requests ADD COLUMN IF NOT EXISTS payment_status VARCHAR(50) DEFAULT 'unpaid';

-- Ensure Payment Categories exist for all hospitals
-- Note: This is also handled automatically by the code when visiting the Add Payment page.
INSERT IGNORE INTO payment_category (category, c_price, type, hospital_id)
SELECT 'Daycare', s.daycare_fee, 'others', s.hospital_id
FROM settings s
WHERE NOT EXISTS (
    SELECT 1 FROM payment_category pc 
    WHERE pc.category = 'Daycare' AND pc.hospital_id = s.hospital_id
);

INSERT IGNORE INTO payment_category (category, c_price, type, hospital_id)
SELECT 'Emergency', s.emergency_fee, 'others', s.hospital_id
FROM settings s
WHERE NOT EXISTS (
    SELECT 1 FROM payment_category pc 
    WHERE pc.category = 'Emergency' AND pc.hospital_id = s.hospital_id
);
