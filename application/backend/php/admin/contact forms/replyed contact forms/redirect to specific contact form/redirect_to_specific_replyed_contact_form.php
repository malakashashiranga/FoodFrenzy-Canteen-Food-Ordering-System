<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['form_number'])) {
        $formNumber = strtolower($_GET['form_number']);
		
		ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {	

			$retriveContactFormDetails = "SELECT * FROM contact_forms WHERE number = ?";
						
			if ($stmt = $conn->prepare($retriveContactFormDetails)) {
				$stmt->bind_param("i", $formNumber); 
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						
						$userID = '';
						if ($row['user_id'] === null) {
							$userID = 'Non registered customer';
						}  else {
							$userID = $row['user_id'];
						}
						
						$_SESSION['replyed_contact_form_details'] = array(
							'formNumber' => $row['number'],
							'userID' => $userID,
							'name' => capitalizeAfterSpaceOrSpecialChar($row['name']),
							'senderEmail' => $row['sender_email'],
							'senderPhone' => $row['sender_phone_number'],
							'message' => $row['message'],
							'receivedDateTime' => $row['recieved_date']. ' / '. $row['recieved_time'],
							'reply' => $row['reply'],
							'replyedDateTime' => $row['replyed_date']. ' / '. $row['replyed_time']
						);
						header('Location: /FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/replyed contact forms/specific replyed contact form/specific_replyed_contact_form_page.php');
					}
				} else {
					$errorMessage = "There is no contact form with this number.";
					redirectToErrorPage($errorMessage); 
				}
			} else {
				$errorMessage = "Error in retriveContactFormDetails query.";
				redirectToErrorPage($errorMessage);    	
			}
		} else {
			$errorMessage = "Database connection error.";
			redirectToErrorPage($errorMessage); 
		}
    } else {
		$errorMessage = "Contact form number not provided in the URL.";
		redirectToErrorPage($errorMessage);    
    }
} else {
    $errorMessage = "Error in request method.";
    redirectToErrorPage($errorMessage);    
}


function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/html/common/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy-Canteen-Food-Ordering-System/application/frontend/php/pages/admin/contact forms/replyed contact forms/replyed contact forms/replyed_contact_forms.php"; 

    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}


function capitalizeAfterSpaceOrSpecialChar($word) {
    $nameParts = preg_split("/[\s_]+/", $word);
    $capitalizedParts = array_map('ucwords', $nameParts);
    $capitalizedName = implode(' ', $capitalizedParts);

    $capitalizedName = ucfirst($capitalizedName);

    return $capitalizedName;
}

?>
