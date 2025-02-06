<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

$userEmail = $_SESSION['userEmail'];
$userPassword = $_SESSION['userPassword'];

function generateAndSetAuthToken() {
    $token = bin2hex(random_bytes(256)); // Generate a 512-character token
    $_SESSION['auth_token'] = $token;

    $expirationTimestamp = time() + 3600 * 24 * 30;

    $expirationDate = date('Y-m-d', $expirationTimestamp);
    $expirationTime = date('H:i:s', $expirationTimestamp);

    setcookie("auth_token", $token, $expirationTimestamp, "/", "", true, true);
    return [$expirationDate, $expirationTime, $token];
}

list($expirationDate, $expirationTime, $token) = generateAndSetAuthToken();

ob_start();
include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
$includedContent = ob_get_clean();
    
$conn_status_data = json_decode($includedContent, true);
$conn_status = $conn_status_data['connection_status'];	
	
if ($conn_status === "successfully") {
	$activenessValue = 'active';
	
	$checkTokenQuery = "SELECT auth_token FROM users_last_login WHERE auth_token = ?";
    $stmt = $conn->prepare($checkTokenQuery);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
	
	while ($stmt->num_rows > 0) {
        list($expirationDate, $expirationTime, $token) = generateAndSetAuthToken(); 
	   
	    $stmt->bind_param("s", $token);
		$stmt->execute();
		$result = $stmt->get_result();
    }
        
	
    $getUserIDQuery = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($getUserIDQuery);
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result1 = $stmt->get_result();
	
	$row = $result1->fetch_assoc();
    $userID = $row['user_id'];
    
	$getUserIDQuery = "SELECT user_id FROM users_last_login WHERE user_id = ?";
    $stmt = $conn->prepare($getUserIDQuery);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result2 = $stmt->get_result();
    
	if ($result2->num_rows === 0){
		$insertTokenQuery = "INSERT INTO users_last_login (user_id, auth_token, token_expiration_date, token_expiration_time, last_sign_date, last_sign_time, token_validity) VALUES (?, ?, ?, ?, CURDATE(), CURTIME(), ?)";
		$stmtInsert = $conn->prepare($insertTokenQuery);
		$stmtInsert->bind_param("sssss", $userID, $token, $expirationDate, $expirationTime, $activenessValue);
		$stmtInsert->execute();
		if ($stmtInsert->error) {
			$errorMessage = "Database process error: " . $stmtInsert->error;
			$pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
			$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 
			header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
			exit;
		} else {
			$stmtInsert->close();
		}
			
	} else {
		$updateTokenQuery = "UPDATE users_last_login SET auth_token = ?, token_expiration_date = ?, token_expiration_time = ?, last_sign_date = CURDATE(), last_sign_time = CURTIME(), token_validity = ? WHERE user_id = ?";
		$stmtUpdate = $conn->prepare($updateTokenQuery);
		$stmtUpdate->bind_param("sssss", $token, $expirationDate, $expirationTime, $activenessValue, $userID); // Pass the variable as a reference
		$stmtUpdate->execute();
		if ($stmtUpdate->error) {
			$errorMessage = "Database process error: " . $stmtUpdate->error;
			$pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
			$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 
			header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
			exit;
		} else {
			$stmtUpdate->close();
		}
	}
} else {
    $errorMessage = "Database connection failed: " . $connection_status;
    $pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;
}

$conn->close();

session_destroy();

header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/sign in/navigate users/user_navigation.php');

?>
