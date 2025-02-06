<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

$check_user_id = $_SESSION['check_user_id'];
include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';

if ($connection_status === "successfully") {
    $getUserInfoQuery = "SELECT first_name, last_name FROM users WHERE user_id = ?";
    
    $stmt = $conn->prepare($getUserInfoQuery);
    
    if ($stmt) {
        $stmt->bind_param("s", $check_user_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $last_name);
        $stmt->fetch();
        $stmt->close();
        
        $_SESSION['f_name'] = $first_name;
		$_SESSION['l_name'] = $last_name;
		
		header ("Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/backend/php/sign part/update verify codes/update_verify_codes_table.php");	
		exit;
	} 
} else {
	if ($_SESSION['process'] === "sign up"){
		$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
	} else{
		$goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/sign part/sign in/sign_in.php";
	}
	$errorMessage = "Database connection failed: " . $connection_status;
    $pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; // Link to your error page

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;
}

$conn->close();

?>