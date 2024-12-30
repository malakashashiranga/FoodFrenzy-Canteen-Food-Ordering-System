<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    if (isset($_POST['searchTerm'])) {
		$searchTerm = $_POST['searchTerm'];

        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();

        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
			
			$userType = 'customer';
			$activeness = 'active';
			$deactiveness = 'deactivated';
			$declined = 'declined';
			if ($searchTerm === '') {
				$retrieveUsersDetails = "SELECT u.user_id, u.first_name, u.last_name, u.mobile_number, u.email, u.registered_date
										FROM users u
										INNER JOIN active_sessions acs ON u.user_id = acs.user_id
										WHERE u.user_type = ? AND ((acs.activeness = ? AND (
										(acs.last_response_date = DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND acs.last_response_time <= CURTIME())
										OR acs.last_response_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY))) OR (acs.activeness = ?) OR (acs.activeness = ?))
										ORDER BY u.registered_date DESC";
			} else {                        
				$retrieveUsersDetails = "SELECT u.user_id, u.first_name, u.last_name, u.mobile_number, u.email, u.registered_date
										FROM users u
										INNER JOIN active_sessions acs ON u.user_id = acs.user_id
										WHERE u.user_id LIKE ? AND u.user_type = ? AND ((acs.activeness = ? AND
										(acs.last_response_date = DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND acs.last_response_time <= CURTIME())
										OR acs.last_response_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY)) OR (acs.activeness = ?) OR (acs.activeness = ?))
										ORDER BY u.registered_date DESC";
			}

			if ($stmt = $conn->prepare($retrieveUsersDetails)) {
			
				if ($searchTerm !== '') {
					$searchNewTerm = '%' . $searchTerm . '%';
					$stmt->bind_param("sssss", $searchNewTerm, $userType, $deactiveness, $activeness, $declined);
				} else {
					$stmt->bind_param("ssss", $userType, $deactiveness, $activeness, $declined);
				} 
				
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					$userDetails = array(); 
					while ($row = $result->fetch_assoc()) {
						$userDetail = array(
							'userID' => strtoupper($row['user_id']),
							'userName' => ucfirst($row['first_name']). ' ' . ucfirst($row['last_name']),
							'email' => $row['email'],
							'mobileNumber' => $row['mobile_number'],
							'registeredDate' => $row['registered_date']
						);

						$userDetails[] = $userDetail; 
					}
					$response['userDetails'] = $userDetails; 
				} else {
					if ($searchTerm === '') {
						$response['noFoods'] = true;
						$response['content'] = "No user registered to the system yet!";
					} else {
						$response['noFoods'] = true;
						$response['content'] = "No user found with the id '$searchTerm'";
					}
				}
				$stmt->close();
			} else {
                $response = array(
                    'success' => false,
                    'alert' => 'Error in retrieveUsersDetails query.',
                    'error_page' => true
                );
            }
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Database connection error.',
                'error_page' => true
            );
        }
        $conn->close();
    } else {
		$response = array(
			'success' => false,
			'alert' => 'Error in selection type.',
			'error_page' => true
		);
	}
} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


function capitalizeAfterSpaceOrSpecialChar($word) {
    $nameParts = preg_split("/[\s_]+/", $word);
    $capitalizedParts = array_map('ucwords', $nameParts);
    $capitalizedName = implode(' ', $capitalizedParts);

    $capitalizedName = ucfirst($capitalizedName);

    return $capitalizedName;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
