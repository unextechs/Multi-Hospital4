<?php
// Since I can't easily bootstrap CodeIgniter standalone, 
// I'll try to use a script that reads the database config and connects manually.

$db_file = 'application/config/database.php';
$db_content = file_get_contents($db_file);

// Extract DB settings
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

    echo "--- Doctor Duplicates Check ---\n";
    $sql = "SELECT email, COUNT(*) as count FROM doctor GROUP BY email HAVING count > 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Duplicate Email: " . $row["email"] . " (Count: " . $row["count"] . ")\n";
        }
    } else {
        echo "No duplicate emails found in doctor table.\n";
    }

    echo "\n--- Doctor Records (Last 10) ---\n";
    $sql = "SELECT id, name, email, hospital_id FROM doctor ORDER BY id DESC LIMIT 10";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Name: {$row['name']}, Email: {$row['email']}, Hospital: {$row['hospital_id']}\n";
    }

    echo "\n--- Ion Users (Last 10) ---\n";
    $sql = "SELECT id, username, first_name, email FROM users ORDER BY id DESC LIMIT 10";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Username: {$row['username']}, Name: {$row['first_name']}, Email: {$row['email']}\n";
    }

    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
