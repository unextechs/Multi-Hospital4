<?php
define('BASEPATH', 'foo');
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

echo "--- Doctor Table Columns ---\n";
$fields = $CI->db->list_fields('doctor');
foreach ($fields as $field) {
    echo "$field\n";
}

echo "\n--- Doctor Records ---\n";
$doctors = $CI->db->get('doctor')->result_array();
foreach ($doctors as $d) {
    echo "ID: {$d['id']}, Name: {$d['name']}, Email: {$d['email']}\n";
}
