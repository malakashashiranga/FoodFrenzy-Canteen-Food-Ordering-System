<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_POST['foodNumber'])) {
    $response = array();
    $response['success'] = false; 

	ob_start();
	include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
    
	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];

	if ($conn_status === "successfully") {
		
		$foodNumber = filter_input(INPUT_POST, 'foodNumber', FILTER_SANITIZE_STRING);
		
		$getFoodDetails = "SELECT * FROM foods WHERE food_number = ?";
		if ($stmt = $conn->prepare($getFoodDetails)) {
			$stmt->bind_param("s", $foodNumber);
			$stmt->execute();
			$result = $stmt->get_result();
			
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$foodDetails = array();
							
					$foodDetail = array(
						'foodNumber' => $row['food_number'],
						'foodName' => capitalizeAfterSpaceOrSpecialChar($row['food_name']),
						'discountPrice' => "Rs.".$row['discount_price'],
						'nonDiscountPrice' => "Rs.".$row['non_discount_price'],
						'category' => $row['category'],
						'availability' => ucfirst($row['availability']),
						'photo_path' => "/FoodFrenzy/storage/photos/foods/". $row['food_name'] ."/".$row['photo_path'],	
						'details' => ucfirst(capitalizeAfterDot($row['details']))
					);
				
					if ($foodDetail['category'] === 'main_dish') {
						$foodDetail['category'] = 'Main Dish';
					} elseif ($foodDetail['category'] === 'short_eat') {
						$foodDetail['category'] = 'Short Eat';
					} elseif ($foodDetail['category'] === 'dessert') {
						$foodDetail['category'] = 'Dessert';
					} elseif ($foodDetail['category'] === 'drink') {
						$foodDetail['category'] = 'Drink';
					}
					
					$percentage = (($row['non_discount_price'] - $row['discount_price']) / $row['non_discount_price']) * 100;
					$foodDetail['discountPercentage'] = number_format($percentage, 1)."%";
					
					$response['success'] = true;
					$response['foodDetails'] = $foodDetail;
					
					if ($_SESSION['currentPage'] === 'un_reg_home_page' || $_SESSION['currentPage'] === 'un_reg_food_page') {
						$response['page_details'] = 'unreg_page';
					} elseif ($_SESSION['currentPage'] === 'reg_home_page' || $_SESSION['currentPage'] === 'reg_food_page') {
						$response['page_details'] = 'reg_page';
					}
				} 
			}
		} else {
			$response = array(
				'success' => false,
				'alert' => 'Error in getFoodDetails query.',
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


function capitalizeAfterDot($text) {
    $result = preg_replace_callback('/\b([a-zA-Z])\.\s*([a-z])/i', function ($match) {
        return $match[1] . '.' . strtoupper($match[2]);
    }, $text);

    return $result;
}


header('Content-Type: application/json');
echo json_encode($response);
?>