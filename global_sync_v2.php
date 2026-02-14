<?php
$db_file = 'application/config/database.php';
$db_content = file_get_contents($db_file);

preg_match("/'hostname' => '(.*?)'/", $db_content, $hostname);
preg_match("/'username' => '(.*?)'/", $db_content, $username);
preg_match("/'password' => '(.*?)'/", $db_content, $password);
preg_match("/'database' => '(.*?)'/", $db_content, $database);

$host = $hostname[1] ?? '127.0.0.1';
$user = $username[1] ?? 'root';
$pass = $password[1] ?? '';
$db = $database[1] ?? 'hms_db';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "--- Starting Global Name Sync ---\n";

    $sql = "SELECT id, name, ion_user_id FROM doctor";
    $result = $conn->query($sql);

    while ($doctor = $result->fetch_assoc()) {
        $id = $doctor['id'];
        $name = $conn->real_escape_string($doctor['name']);

        echo "Syncing Doctor ID: $id, Name: $name\n";

        // Update payments
        $conn->query("UPDATE payment SET doctor_name = '$name' WHERE doctor = '$id'");
        // Update appointments
        $conn->query("UPDATE appointment SET doctor_name = '$name' WHERE doctor = '$id'");
        // Update labs
        $conn->query("UPDATE lab SET doctor_name = '$name' WHERE doctor = '$id'");
        // Update prescriptions
        $conn->query("UPDATE prescription SET doctor_name = '$name' WHERE doctor = '$id'");

        // Update users
        $name_parts = explode(' ', $doctor['name']);
        $first_name = $conn->real_escape_string($name_parts[0]);
        $last_name = $conn->real_escape_string(isset($name_parts[1]) ? $name_parts[1] : '');
        $ion_user_id = $doctor['ion_user_id'];

        $conn->query("UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$name' WHERE id = '$ion_user_id'");
    }

    echo "--- Global Name Sync Complete ---\n";
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
