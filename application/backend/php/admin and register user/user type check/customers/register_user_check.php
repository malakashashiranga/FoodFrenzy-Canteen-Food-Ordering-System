<?php
ob_start(); 
include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
$includedContent = ob_get_clean();

$conn_status_data = json_decode($includedContent, true);
$conn_status = $conn_status_data['connection_status'];

if ($conn_status === "successfully") {
	
	$UserId = $_SESSION['user_id'];
	
	$userTypeCheck = "SELECT user_type FROM users WHERE user_id = ?";
	if ($stmt = $conn->prepare($userTypeCheck)) {
		$stmt->bind_param("s", $UserId);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc(); 
			$userType = $row['user_type'];
			
			$checkUserType = 'customer';
			
			if ($userType !==  $checkUserType) {
				header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
				exit;
			}
		}
	} else {
		$errorMessage = "Error in userTypeCheck query:" . $conn->error; 
		redirectToErrorPage($errorMessage);
	}
} else {    
    $errorMessage = "Database connection failed: " . $connection_status;
    redirectToErrorPage($errorMessage);        
}
$conn->close();

?>