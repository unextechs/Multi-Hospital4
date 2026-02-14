<?php
/**
 * Patient ID Migration Script
 * 
 * This script renumbers existing patients to have sequential hospital_patient_id
 * per hospital, ensuring proper P1, P2, P3... format.
 * 
 * Usage: php migrate_patient_ids.php
 */

// Load CodeIgniter (adjust path if needed)
define('BASEPATH', true);
require_once('index.php');

// Get CI instance
$CI =& get_instance();
$CI->load->database();

echo "=== Patient ID Migration Script ===\n\n";

// Get all hospitals
$hospitals = $CI->db->get('hospital')->result();

if (empty($hospitals)) {
    echo "No hospitals found.\n";
    exit;
}

echo "Found " . count($hospitals) . " hospital(s)\n\n";

foreach ($hospitals as $hospital) {
    echo "Processing Hospital ID: {$hospital->id} - {$hospital->hospital_name}\n";

    // Get all patients for this hospital, ordered by their current ID (creation order)
    $CI->db->select('id, name, hospital_patient_id');
    $CI->db->where('hospital_id', $hospital->id);
    $CI->db->order_by('id', 'ASC'); // Order by database ID (chronological)
    $patients = $CI->db->get('patient')->result();

    if (empty($patients)) {
        echo "  No patients found for this hospital.\n\n";
        continue;
    }

    echo "  Found " . count($patients) . " patient(s)\n";

    // Renumber sequentially
    $counter = 1;
    foreach ($patients as $patient) {
        $old_id = $patient->hospital_patient_id;

        // Update to sequential number
        $CI->db->where('id', $patient->id);
        $CI->db->update('patient', ['hospital_patient_id' => $counter]);

        echo "  - Patient #{$patient->id} ({$patient->name}): P{$old_id} → P{$counter}\n";

        $counter++;
    }

    echo "  ✓ Completed renumbering for Hospital {$hospital->id}\n\n";
}

echo "=== Migration Complete! ===\n";
echo "All patients have been renumbered sequentially per hospital.\n";
