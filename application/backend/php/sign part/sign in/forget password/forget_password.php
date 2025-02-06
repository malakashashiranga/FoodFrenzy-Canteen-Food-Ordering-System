<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
	$response['success'] = true;
	
	$_SESSION['process'] = "forget password";
	$_SESSION['step1_completed'] = true;
	$_SESSION['sub_process'] = "main flow";
	
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


header('Content-Type: application/json');
echo json_encode($response);
?>