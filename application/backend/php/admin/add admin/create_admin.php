<?php
ob_start();
include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
$includedContent = ob_get_clean();

$conn_status_data = json_decode($includedContent, true);
$conn_status = $conn_status_data['connection_status'];

if ($conn_status === "successfully") {
    $password = 'password';

    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

    $sql = "INSERT INTO users (user_id, email, password, first_name, last_name, mobile_number, address, user_type, registered_date, registered_time, photo_path)
    VALUES ('#jd1000', 'foodfrenzy0001@gmail.com', '$hashedPassword', 'john', 'doe', 0111111111, '123 main street,home town', 'owner', CURDATE(), CURTIME(), NULL)";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Database connection failed. Please try again later.";
}
$conn->close();
?>
