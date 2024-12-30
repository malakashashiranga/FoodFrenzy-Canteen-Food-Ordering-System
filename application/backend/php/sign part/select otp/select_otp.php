<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

$e_mail = $_SESSION['e_mail'];
$userOTP = $_SESSION['user_otp'];

include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';

if ($connection_status === "successfully") {
	
	$updateQuery = "UPDATE verify_codes SET status = 'selected' WHERE email = '$e_mail' AND otp = '$userOTP' AND status = 'pending'";
	if ($conn->query($updateQuery) === TRUE) {
		if ($_SESSION['process'] === 'setting_email_change') {
			
			$UserId = $_SESSION['user_id'];
			$updateEmail = "UPDATE users SET email = ? WHERE user_id = ?";
			if ($stmt = $conn->prepare($updateEmail)) {
                $stmt->bind_param("ss", $e_mail, $UserId);
                $stmt->execute();
                $stmt->close(); 
				
				unset($_SESSION['step1_completed']);
				unset($_SESSION['process']);
				unset($_SESSION['sub_process']);
				unset($_SESSION['e_mail']);
				unset($_SESSION['check_user_id']);
				unset($_SESSION['user_otp']);
				unset($_SESSION['f_name']);
				unset($_SESSION['l_name']);

				$_SESSION['alert'] = "Your e-mail was updated successfully.!";
				
				if ($_SESSION['currentPage'] === 'reg_settings') {
					header("Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/register user/settings/settings.php");
					exit;  
				} elseif ($_SESSION['currentPage'] === 'admin_settings') {
					header("Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/settings/settings.php");
					exit;
				}
            }
		} else {
			if ($_SESSION['process'] === "sign up") {
				$_SESSION['title'] = "Set";
			}else {
				$_SESSION['title'] = "Change";
			} 
			$_SESSION['alert'] = "E-mail verification was successful.!";
			$_SESSION['step3_completed'] = true;
			header("Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/change or set password/change_or_set_password.php");
			exit;
		}
	} else {
		if ($_SESSION['process'] === "sign up"){
			$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
		} else{
			$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php";
		}
		$errorMessage = "Database updating failed: " . $connection_status;
		$pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 

		header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
		exit;
	}	
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
