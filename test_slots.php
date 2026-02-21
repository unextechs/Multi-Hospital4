<?php
define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . '/');
define('BASEPATH', FCPATH . 'system/');
define('APPPATH', FCPATH . 'application/');
// We will just directly query the database to see what time slots a doctor has
require_once APPPATH . 'config/database.php';
$conn = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SELECT * FROM time_slot LIMIT 5");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
$res = $conn->query("SELECT * FROM holidays WHERE date = " . strtotime(date('d-m-Y')) . " OR date = " . strtotime(date('Y-m-d')) . " LIMIT 2");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
echo "\nCurrent time: " . date('Y-m-d H:i:s') . "\n";
echo "Current h:i A : " . date('h:i A') . "\n";
$minute = date('i');
$hour = date('h');
if (date('A') == 'PM') {
    if ($hour != 12) {
        $hour = $hour + 12;
    }
} else {
    if ($hour == 12) {
        $hour = 0;
    }
}
$total_minutes = $hour * 60 + $minute;
$time_key = floor($total_minutes / 5);
echo "Current Time Key: $time_key \n";

$is_today = (date('Y-m-d', time()) == date('Y-m-d'));
echo "Is Today: " . ($is_today ? "True" : "False") . "\n";
