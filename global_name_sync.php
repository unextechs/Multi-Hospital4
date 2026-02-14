<?php
define('BASEPATH', 'foo');
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();
$CI->load->model('doctor/doctor_model');

echo "--- Starting Global Name Sync ---\n";

$doctors = $CI->db->get('doctor')->result();

foreach ($doctors as $doctor) {
    echo "Syncing Doctor ID: {$doctor->id}, Name: {$doctor->name}\n";
    $CI->doctor_model->syncDoctorName($doctor->id, $doctor->name);

    // Also sync Ion User if needed
    $name_parts = explode(' ', $doctor->name);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

    $uptade_ion_user = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'username' => $doctor->name
    );
    $CI->db->where('id', $doctor->ion_user_id);
    $CI->db->update('users', $uptade_ion_user);
}

echo "--- Global Name Sync Complete ---\n";
