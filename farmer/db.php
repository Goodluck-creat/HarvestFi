<?php
// db.php - HarvestFi database connection

// Database credentials
$host = "localhost";        // Usually localhost
$user = "oqmntiar_Harvestfi";             // Your DB username
$password = "Harvestfi1@";             // Your DB password
$database = "oqmntiar_Harvestfi";    // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to UTF-8
$conn->set_charset("utf8");

?>