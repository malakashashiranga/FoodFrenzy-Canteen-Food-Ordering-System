<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

	ob_start();
	include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
    
	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];

	if ($conn_status === "successfully") {
		if ($_SESSION['currentPage'] === 'un_reg_home_page' || $_SESSION['currentPage'] === 'reg_home_page') {
			
			if (getFoodCount($conn) === 0) {
				$response['noFoods'] = true;
				$response['content'] = "No foods added to the system yet. Keep stay with us !!!";
				
			} elseif (getFoodCount($conn) > 0) {
			
				$availability = 'available';
				$getFoodDetails = "SELECT * FROM foods WHERE availability = ? LIMIT 12";

				if ($stmt = $conn->prepare($getFoodDetails)) {
					$stmt->bind_param("s", $availability);
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows > 0) {
						$foodDetails = array(); // Initialize the array
						while ($row = $result->fetch_assoc()) {
							$foodDetail = array(
								'foodNumber' => $row['food_number'],
								'foodName' => capitalizeAfterSpaceOrSpecialChar($row['food_name']),
								'nonDiscountPrice' => "Rs.".$row['non_discount_price'],
								'discountPrice' => "Rs.".$row['discount_price'],
								'photo_path' => "/FoodFrenzy-Canteen-Food-Ordering-System/storage/photos/foods/". $row['food_name'] ."/".$row['photo_path'],	
							);
							$foodDetails[] = $foodDetail; // Append this food detail to the array
						}
						$response['foodDetails'] = $foodDetails; 

						$foodDetailsCount = count($foodDetails);
						if ($foodDetailsCount > 4) {
    
							if ($foodDetailsCount % 4 !== 0 ) {
								$response['moreHeightSize'] = 330 * intdiv($foodDetailsCount, 4);
							} else {
								$response['moreHeightSize'] = 330 * (intdiv($foodDetailsCount, 4) - 1);
							}
						}
						
						if (getFoodCount($conn) > 12){
							$response['moreButton'] = true;
							$response['moreHeightSize'] = 330 * 2;
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
			} elseif (getFoodCount($conn) === 'error1') {
				$response = array(
					'success' => false,
					'alert' => 'Error in getFoodCount query.',
					'error_page' => true
				);
			}
		} else {
			$response = array(
				'success' => false,
				'alert' => 'This is not the right page operation.',
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


function getFoodCount($conn) {
    $availability = 'available';
    $getFoodCount = "SELECT COUNT(*) AS food_count FROM foods WHERE availability = ?";

    if ($stmt = $conn->prepare($getFoodCount)) {
        $stmt->bind_param("s", $availability);
        $stmt->execute(); 

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $foodCount = $row["food_count"];
            return $foodCount;
        } else {
            return 0; 
        }
    } else {
        return 'error1';
    }
}


header('Content-Type: application/json');
echo json_encode($response);
?>