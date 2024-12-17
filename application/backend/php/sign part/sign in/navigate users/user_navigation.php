<?php

include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';

ob_start(); 
include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
$includedContent = ob_get_clean();

$conn_status_data = json_decode($includedContent, true);
$conn_status = $conn_status_data['connection_status'];

if ($conn_status === "successfully") {

	$checkUserTypeQuery = "SELECT user_type FROM users WHERE user_id = ?";

	if ($stmt = $conn->prepare($checkUserTypeQuery)) {
	$stmt->bind_param("s", $UserId);
	$stmt->execute();
	$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc(); 
			$UserType = $row['user_type'];
			
			if ($UserType === 'owner'){
				header('Location: /FoodFrenzy/application/frontend/php/pages/admin/home/home.php');
				exit;
			} elseif ($UserType === 'customer'){
				header('Location: /FoodFrenzy/application/frontend/php/pages/register user/home/home_page.php');
				exit;
			}
			
		} else {
			$errorMessage = "Database user type field error: " . $conn->error; 
			redirectToErrorPage($errorMessage);        
		}
	} else {
		$errorMessage = "Database checkUserTypeQuery error: " . $conn->error; 
		redirectToErrorPage($errorMessage);
	}
	$stmt->close(); 
} else {    
    $errorMessage = "Database connection failed: " . $connection_status;
    redirectToErrorPage($errorMessage);        
}
$conn->close();

?>