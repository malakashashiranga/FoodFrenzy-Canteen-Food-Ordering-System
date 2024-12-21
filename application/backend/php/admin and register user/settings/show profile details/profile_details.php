<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    $UserId = $_SESSION['user_id'];

    ob_start();
    include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {
        $selectUserDetails = "SELECT * FROM users WHERE user_id = ?";
        
        if ($stmt = $conn->prepare($selectUserDetails)) {
            $stmt->bind_param("s", $UserId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
				
				$response['user_id'] = strtoupper($row['user_id']);
				$response['first_name'] = capitalizeAfterSpaceOrSpecialChar($row['first_name']);
				$response['last_name'] = capitalizeAfterSpaceOrSpecialChar($row['last_name']);
				$response['email'] = $row['email'];
				$response['mobile_number'] = $row['mobile_number'];
				$response['address'] = capitalizeAfterSpaceOrSpecialChar($row['address']);
				$photo_path = $row['photo_path'];
				
				$encodedUserId = urlencode($UserId);
								
				if (!empty($photo_path)){
					$response['photo_path'] = '/FoodFrenzy/storage/photos/users/'. $encodedUserId . '/' . $encodedUserId . '.jpg';
				} else {
					$response['photo_path'] = '/FoodFrenzy/storage/photos/users/default user/default_user.svg';
				}
            } else {
                $response = array(
                    'success' => false,
                    'alert' => 'User not found in the database for user data showing.',
                    'error_page' => true
                );
            }
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Error in user data showing preparation.',
                'error_page' => true
            );
        }
        $stmt->close();
    } else {
        $response = array(
            'success' => false,
            'alert' => 'Database connection status is not "successfully" for user data showing.',
            'error_page' => true
        );
    }
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method for user data showing.',
        'error_page' => true
    );
}


function capitalizeAfterSpaceOrSpecialChar($name) {
	$nameParts = preg_split("/[\s_]+/", $name);
	$capitalizedParts = array_map('ucwords', $nameParts);
	$capitalizedName = implode(' ', $capitalizedParts);
    
	$capitalizedName = ucfirst($capitalizedName);
		
	return $capitalizedName;
}


header('Content-Type: application/json');
echo json_encode($response);
?>
