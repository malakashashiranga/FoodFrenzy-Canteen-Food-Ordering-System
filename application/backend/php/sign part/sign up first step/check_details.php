<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $mob_number = $_POST['mob_number'];
    $address = $_POST['address'];
	
	$response['success'] = true;

    if (empty($f_name)) {
        $response['success'] = false;
        $response['firstNameAlert'] = '*First name is required.';
    } elseif (preg_match('/\d/', $f_name)) {
        $response['success'] = false;
        $response['firstNameAlert'] = '*First name should not contain numbers.';
    } else {
        $response['firstNameAlert'] = ''; // Clear the alert message
    }

    if (empty($l_name)) {
        $response['success'] = false;
        $response['lastNameAlert'] = '*Last name is required.';
    } elseif (preg_match('/\d/', $l_name)) {
        $response['success'] = false;
        $response['lastNameAlert'] = '*Last name should not contain numbers.';
    } else {
        $response['lastNameAlert'] = ''; // Clear the alert message
    }

    if (empty($mob_number)) {
        $response['success'] = false;
        $response['mobileNumberAlert'] = '*Mobile number is required.';
    } elseif (!preg_match('/^\d{10}$/', $mob_number)) {
        $response['success'] = false;
        $response['mobileNumberAlert'] = '*Mobile number should have 10 digits.';
    } else {
        $response['mobileNumberAlert'] = ''; // Clear the alert message
    }

    if (empty($address)) {
        $response['success'] = false;
        $response['addressAlert'] = '*Home address is required.';
    } else {
        $response['addressAlert'] = ''; // Clear the alert message
    }
	

} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}

if ($response['success'] === true) { 
    $_SESSION['f_name'] = $f_name;
	$_SESSION['l_name'] = $l_name;
	$_SESSION['mob_number'] = $mob_number;
	$_SESSION['address'] = $address;
	$_SESSION['process'] = "sign up";
	$_SESSION['sub_process'] = "main flow";
	$_SESSION['step1_completed'] = true;
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>