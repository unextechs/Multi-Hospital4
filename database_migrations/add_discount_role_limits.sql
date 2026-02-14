ALTER TABLE settings ADD COLUMN discount_limit_pharmacist FLOAT DEFAULT 0;
ALTER TABLE settings ADD COLUMN discount_limit_receptionist FLOAT DEFAULT 0;
ALTER TABLE settings ADD COLUMN discount_limit_doctor FLOAT DEFAULT 0;
ALTER TABLE settings ADD COLUMN discount_limit_admin FLOAT DEFAULT 100;
