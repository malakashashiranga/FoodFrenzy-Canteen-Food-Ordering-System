<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

$email = $_SESSION['e_mail'];
$check_user_id = NULL;

include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';

if ($connection_status === "successfully") {
    if ($_SESSION['process'] === 'forget password') {
        
        $emailIdQuery = "SELECT user_id FROM users WHERE email = ?";
        $stmt = $conn->prepare($emailIdQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();
            if ($row) {
                $check_user_id = $row['user_id'];
            }
        }
		$_SESSION['check_user_id'] = $check_user_id;
		header("Location: /FoodFrenzy/application/backend/php/sign part/find name/find names.php");
		exit;
		
    } elseif ($_SESSION['process'] === 'setting_email_change') {
	
		$_SESSION['check_user_id'] = $_SESSION['user_id'];
		header("Location: /FoodFrenzy/application/backend/php/sign part/find name/find names.php");
		exit;
		
    } else {
		$check_user_id = NULL;
		$_SESSION['check_user_id'] = $check_user_id;
		header("Location: /FoodFrenzy/application/backend/php/sign part/update verify codes/update_verify_codes_table.php");
		exit;
    }
} else {
	if ($_SESSION['process'] === "sign up"){
		$goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
	} else{
		$goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php";
	}
	$errorMessage = "Database connection failed: " . $connection_status;
    $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; // Link to your error page

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
	exit;
}
$conn->close();

?>
