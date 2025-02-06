<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    if (isset($_POST['userID'])) {
        $userID = $_POST['userID'];
        $response['alert1'] = $userID;

        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();

        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
            
            $getUserDetailsQuery = "SELECT * FROM users WHERE user_id = ?";
            if ($stmt = $conn->prepare($getUserDetailsQuery)) {
                $stmt->bind_param("s", $userID);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $userData = $result->fetch_assoc();
                    $stmt->close();

                    $deleteUserQuery = "DELETE FROM users WHERE user_id = ?";
                    if ($stmt = $conn->prepare($deleteUserQuery)) {
                        $stmt->bind_param("s", $userID);
                        $stmt->execute();
                        $stmt->close();

                        $insertQuery = "INSERT INTO deleted_users (user_id, email, first_name, last_name, mobile_number, address, registered_date, registered_time) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        if ($stmt = $conn->prepare($insertQuery)) {
                            $stmt->bind_param("ssssssss", $userData['user_id'], $userData['email'], $userData['first_name'], $userData['last_name'], $userData['mobile_number'], $userData['address'], $userData['registered_date'], $userData['registered_time']);
                            $stmt->execute();
                            $stmt->close();

                            $response['alertContent'] = true;
                            $response['alert'] = 'Account was successfully deleted.!';
                        } else {
                            $response = array(
                                'success' => false,
                                'alert' => 'Error in insertQuery query.',
                                'error_page' => true
                            );
                        }
                    } else {
                        $response = array(
                            'success' => false,
                            'alert' => 'Error in deleteUserQuery query.',
                            'error_page' => true
                        );
                    }
                } else {
                    $response = array(
                        'success' => false,
                        'alert' => 'User not found in the database.',
                        'error_page' => true
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'alert' => 'Error in getUserDetailsQuery query.',
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
            'alert' => 'User ID not provided.',
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


header('Content-Type: application/json');
echo json_encode($response);
?>
