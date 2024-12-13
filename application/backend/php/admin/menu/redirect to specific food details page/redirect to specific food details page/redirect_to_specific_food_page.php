<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['food_id'])) {
		$encodedFoodId = $_GET['food_id'];
		$foodId = urldecode($encodedFoodId);
		
		ob_start();
        include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();
        
        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];    
    
        if ($conn_status === "successfully") {
		
			$selectSpecificFoodDetails = "SELECT * FROM foods WHERE food_number = ?";
		
			if ($stmt = $conn->prepare($selectSpecificFoodDetails)) {
				$stmt->bind_param("s", $foodId); 
				$stmt->execute();
				$result = $stmt->get_result();
				
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						$_SESSION['food_details'] = array(
							'foodNumber' => $row['food_number'],
							'foodName' => capitalizeAfterSpaceOrSpecialChar($row['food_name']),
							'discountPrice' => $row['discount_price'],
							'nonDiscountPrice' => $row['non_discount_price'],
							'category' => $row['category'],
							'availability' => $row['availability'],
							'photoPath' => $row['photo_path'],
							'details' => ucfirst(capitalizeAfterDot($row['details']))
						);
						header('Location: /FoodFrenzy/application/frontend/php/pages/admin/menu/manage food details/manage specific food details/manage_specific_food_details.php');
					}
				} else {
					$errorMessage = "There is no food with this number.";
					redirectToErrorPage($errorMessage); 
				}
			} else {
				$errorMessage = "Error in selectSpecificFoodDetails query.";
				redirectToErrorPage($errorMessage);    	
			}
		} else {
			$errorMessage = "Database connection error.";
			redirectToErrorPage($errorMessage); 
		}
    } else {
		$errorMessage = "Food ID not provided in the URL.";
		redirectToErrorPage($errorMessage);    
    }
} else {
    $errorMessage = "Error in request method.";
    redirectToErrorPage($errorMessage);    
}


function redirectToErrorPage($errorMessage) {
    $pageLink = "/FoodFrenzy/application/frontend/html/same/admin, register and unregister user/error page/error.html"; 
    $goBackURL = "/FoodFrenzy/application/frontend/php/pages/admin/menu/manage food details/food list/manage_food_details.php"; 

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


function capitalizeAfterDot($text) {
    $result = preg_replace_callback('/\b([a-zA-Z])\.\s*([a-z])/i', function ($match) {
        return $match[1] . '.' . strtoupper($match[2]);
    }, $text);

    return $result;
}

?>
