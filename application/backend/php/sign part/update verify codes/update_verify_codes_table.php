<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';

if ($connection_status === "successfully") {
    $email = $_SESSION['e_mail'];
	$check_user_id = $_SESSION['check_user_id'];
    $otp = rand(10000000, 99999999);

    $updateUserQuery = "UPDATE verify_codes SET status = 'expired' WHERE email = ? AND status = 'pending'";
    $stmtUpdate = $conn->prepare($updateUserQuery);
    $stmtUpdate->bind_param("s", $email);
    $stmtUpdate->execute();
    $stmtUpdate->close();
   
    $insertOTPQuery = "INSERT INTO verify_codes (email, user_id, otp, date, time) VALUES (?, ?, ?, CURDATE(), CURTIME())";
	$stmtInsertOTP = $conn->prepare($insertOTPQuery);
	$stmtInsertOTP->bind_param("sss", $email, $check_user_id, $otp); 
	$stmtInsertOTP->execute();
	$stmtInsertOTP->close();
	
	
	$_SESSION['otp'] = $otp;
	header ("Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/send verify code/send_verify_code.php");	
	exit;
} else {
	if ($_SESSION['process'] === "sign up"){
		$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
	} else{
		$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php";
	}
	$errorMessage = "Database connection failed: " . $connection_status;
	$pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 

	header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;
}


$conn->close();

?>
