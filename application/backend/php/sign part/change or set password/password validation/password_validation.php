<?php 
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    $newPassword = $_POST['newPassword'];
    $retypePassword = $_POST['retypePassword'];
    
    $response['success'] = true;
    
    if (empty($newPassword)) {
        $response['success'] = false;
        $response['newPasswordAlert'] = '*required.';
    } else {
        $response['newPasswordAlert'] = '';
    }
    
    if (empty($retypePassword)) {
        $response['success'] = false;
        $response['retypePasswordAlert'] = '*required.';
    } else {
        $response['retypePasswordAlert'] = '';
    }
    
    if (!empty($newPassword) && !empty($retypePassword)) {
        if (strlen($newPassword) < 8 || strlen($newPassword) > 16) {
            $response = array(
                'success' => false,
                'alert' => 'Password should be 8 to 16 characters long.',
                'showAlertContent' => true,
            );
        } elseif (!preg_match('/[a-z]/', $newPassword) || !preg_match('/[A-Z]/', $newPassword)) {
            $response = array(
                'success' => false,
                'alert' => 'Password must include both uppercase and lowercase letters.',
                'showAlertContent' => true,
            );
        } elseif ($newPassword !== $retypePassword) {
            $response = array(
                'success' => false,
                'alert' => 'The new password and retype password are not the same.',
                'showAlertContent' => true,
            );
        } else {
			$_SESSION['password'] = $newPassword;
		}
    }
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
