<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
	$response['success'] = true;
    
    if (isset($_SESSION['active_session'])) {
        $activeSessionId = $_SESSION['active_session'];
        $activenessValue = 'active';
		$UserId = $_SESSION['user_id'];
        
        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {
            
            $updateLastActive = "UPDATE active_sessions SET last_response_date = CURDATE(), last_response_time = CURTIME(), activeness = ? WHERE user_id = ?";
            
            if ($stmt = $conn->prepare($updateLastActive)) {
                $stmt->bind_param("ss", $activenessValue, $UserId);
                $stmt->execute();
                $stmt->close(); 
            
                $response['success'] = true;
                $response['message'] = "Last active updated successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "Failed to update last active.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Database connection failed.";
        }
        $conn->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Session 'active_session' is not set.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
