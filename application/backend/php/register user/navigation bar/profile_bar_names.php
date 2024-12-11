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
		
		$selectUserDetails = "SELECT first_name, last_name FROM users WHERE user_id = ?";
        if ($stmt = $conn->prepare($selectUserDetails)) {
			$stmt->bind_param("s", $UserId);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
        
				$row = $result->fetch_assoc();
				$full_name = $row['first_name'] . ' ' .$row['last_name'];
				$first_name = $row['first_name'];
				$last_name = $row['last_name'];

				if (strlen($first_name) > 7) {
					$first_name = substr($first_name, 0, 7) . '...';
					$last_name = '..........';
				} elseif (strlen($last_name) > 8) {
					$last_name = substr($last_name, 0, 10) . '..';
				}
			} else {
				$response['success'] = false;
                $response['message'] = "User not found on database.";
			}
		} else {
			$response['success'] = false;
            $response['message'] = "User details retrive problem.";
		}
		$stmt->close();
		
		
	} else {
		$response['success'] = false;
        $response['message'] = "Database connection error.!";
	}
	$conn->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method."; 
}


if ($response['success'] === true) {
	$response['prof_bar_first_name'] = capitalizeAfterSpaceOrSpecialChar($first_name);
	$response['prof_bar_last_name'] = capitalizeAfterSpaceOrSpecialChar($last_name);
	$response['prof_bar_full_name'] = capitalizeAfterSpaceOrSpecialChar($full_name);
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

