<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$response = array();
	$response['success'] = false;

	$_SESSION['sub_process'] = "resend code";

	include '/xampp/htdocs/FoodFrenzy/application/backend/php/sign part/update verify codes/update_verify_codes_table.php';
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
