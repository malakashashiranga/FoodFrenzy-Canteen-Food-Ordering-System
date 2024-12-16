<?php 
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ((!isset($_SESSION['step1_completed']) || $_SESSION['step1_completed'] === false) ||
    (!isset($_SESSION['step2_completed']) || $_SESSION['step2_completed'] === false) ||
    (!isset($_SESSION['step3_completed']) || $_SESSION['step3_completed'] === false)) {
    
    $_SESSION['alert'] = "Unfortunately, we are unable to complete your request.!";
    
    header("Location: /FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php");
    exit;
}

include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';

if ($connection_status === "successfully") {
    if ($_SESSION['process'] === 'sign up') {
    
        function generateUserID($firstName, $lastName, $conn) {

            $firstLetterFirstName = strtolower(substr($firstName, 0, 1));
            $firstLetterLastName = strtolower(substr($lastName, 0, 1));

            $randomNumber = rand(1000, 9999);

            $userID = "#{$firstLetterFirstName}{$firstLetterLastName}{$randomNumber}";

            $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $stmt->store_result();
    
            while ($stmt->num_rows > 0) {
                $randomNumber = rand(1000, 9999); 
                $userID = "#{$firstLetterFirstName}{$firstLetterLastName}{$randomNumber}";
                $stmt->bind_param("s", $userID);
                $stmt->execute();
                $stmt->store_result();
            }

            $stmt->close();
            return $userID;
        }

        $userID = generateUserID($_SESSION['f_name'], $_SESSION['l_name'], $conn);

        $hashedPassword = password_hash($_SESSION['password'], PASSWORD_ARGON2I);

        $lowerFirstName = strtolower($_SESSION['f_name']);
        $lowerLastName = strtolower($_SESSION['l_name']);
        $lowerAddress = strtolower($_SESSION['address']);

        $insertQuery = "INSERT INTO users (user_id, email, password, first_name, last_name, mobile_number, address, user_type, registered_date, registered_time) VALUES (?, ?, ?, ?, ?, ?, ?, 'customer', CURDATE(), CURTIME())";

        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssss", $userID, $_SESSION['e_mail'], $hashedPassword, $lowerFirstName, $lowerLastName, $_SESSION['mob_number'], $lowerAddress);

        if ($stmt->execute()) {
			
			$currentBal = 0;
			$createwallet = "INSERT INTO user_wallets (user_id, current_balance) VALUES (?, ?)";
			$stmt = $conn->prepare($createwallet);
			$stmt->bind_param("si", $userID, $currentBal);
			if ($stmt->execute()) {
				$retriveUserdetails = "SELECT wallet_id
                        FROM user_wallets
                        WHERE user_id = ?";

				if ($stmtCheck = $conn->prepare($retriveUserdetails)) {
					$stmtCheck->bind_param("s", $userID);
					$stmtCheck->execute();
					$resultCheck = $stmtCheck->get_result();

					$walletID = $resultCheck->fetch_assoc()['wallet_id'];

					$createwalletRecords = "INSERT INTO wallet_records (wallet_id) VALUES (?)";
					$stmt = $conn->prepare($createwalletRecords);
					$stmt->bind_param("i", $walletID);
					if ($stmt->execute()) {
						$_SESSION['alert'] = "Account created successfully. Sign in to the system.!";
					} else {
						$errorMessage = "Account creation unsuccessful with wallet_records creation. Please try again!";
						$pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html";
						$goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php";
						header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
						exit;
					}
					$stmtCheck->close();
				}
			} else {
				$errorMessage = "Account creation unsuccessful with wallet creation. Please try again!";
				$pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
				$goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php";
				header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
				exit;
			}
        $stmt->close();
        } else {
            $errorMessage = "Account creation unsuccessful. Please try again!";
            $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
            $goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php";
            header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
            exit;
        }
    } else {
        $hashedPassword = password_hash($_SESSION['password'], PASSWORD_ARGON2I);

        $updatePassword = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updatePassword);
        $stmt->bind_param("ss", $hashedPassword, $_SESSION['e_mail']);

        if ($stmt->execute()) {
            $_SESSION['alert'] = "Password changed successfully. Sign in to the system.!";
        } else {
            $errorMessage = "Password changed unsuccessful. Please try again!";
            $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
            $goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php";
            header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
            exit;
        }
        $stmt->close();
    }
} else {
    if ($_SESSION['process'] === "sign up"){
        $goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/signup first part/sign_up.php"; 
    } else {
        $goBackURL = "/FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php";
    }
    $errorMessage = "Database connection failed: " . $connection_status;
    $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
    header("Location: $pageLink?message=" . urlencode($errorMessage) . "&goBackURL=" . urlencode($goBackURL));
    exit;
}

$conn->close();

header('Location: /FoodFrenzy/application/frontend/php/pages/sign part/sign in/sign_in.php');
exit;

?>
