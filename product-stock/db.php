<?php
$host = "localhost"; // Host database
$user = "advcshop";      // Username database
$password = "osttamvan123";      // Password database
$database = "advcshop_product"; // Nama database

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Failed to connect to database: " . $conn->connect_error]));
}
?>
