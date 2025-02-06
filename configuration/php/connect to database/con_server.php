<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "FoodFrenzy";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    $connection_status = "Connection failed: " . $conn->connect_error;
} else {
    $connection_status = "successfully";
}

echo json_encode(['connection_status' => $connection_status]);
?>
