<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

	ob_start();
	include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
    
	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];

	if ($conn_status === "successfully") {

		$messageReply = 'no_reply';
		$getReplyedContactFormDetails = "SELECT number, name, sender_email, replyed_date, replyed_time
								FROM contact_forms 
								WHERE reply != ? 
								ORDER BY replyed_date ASC, replyed_time ASC";

		if ($stmt = $conn->prepare($getReplyedContactFormDetails)) {
			$stmt->bind_param("s", $messageReply);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$contactFormsDetails = array(); 
				while ($row = $result->fetch_assoc()) {
					$contactFormsDetail = array(
						'number' => $row['number'],
						'senderName' => capitalizeAfterSpaceOrSpecialChar(strtolower($row['name'])),
						'senderEmail' => $row['sender_email'],
						'replyedDate' => $row['replyed_date'],
						'replyedTime' => $row['replyed_time'],
					);
					$contactFormsDetails[] = $contactFormsDetail; 
				}
				$response['contactFormsDetails'] = $contactFormsDetails; 		
			} else {
                $response['noContactForms'] = true;
                $response['content'] = "No replyed contacts forms available!";
            } 
		} else {
			$response = array(
				'success' => false,
				'alert' => 'Error in getReplyedContactFormDetails query.',
				'error_page' => true
			);
		}
		$stmt->close(); 
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