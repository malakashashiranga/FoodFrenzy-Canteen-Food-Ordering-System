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
		if ($_SESSION['currentPage'] === 'un_reg_food_page' || $_SESSION['currentPage'] === 'reg_food_page') {
			
			$pageProcess = $_POST['process'];
			$selectedOption = $_POST['selectedOption'];
			$searchValue = $_POST['searchValue'];
			
			$response['moreHeightSize'] = 0;
			
			if ($pageProcess === 'page_load'){
				
				if ($selectedOption === 'all' && $searchValue === '') {
					$foodCount = getFoodCount($conn);
					$response['getFoodCount'] = 1;
				} elseif ($selectedOption !== 'all' && $searchValue === '') {
					$foodCount = filterFoodCount($conn, $selectedOption);
					$response['filterFoodCount'] = 1;
				} elseif ($selectedOption === 'all' && $searchValue !== '') {
					$searchValue = '%' . $searchValue . '%';
					$foodCount = searchFoodCount($conn, $searchValue);
					$response['searchFoodCount'] = 1;
				} elseif ($selectedOption !== 'all' && $searchValue !== '') {
					$searchValue = '%' . $searchValue . '%';
					$foodCount = searchFilterFoodCount($conn, $searchValue, $selectedOption);
					$response['searchFilterFoodCount'] = 1;
				}
				
				if ($foodCount === 0) {
					$response['noFoods'] = true;
					$response['segment'] = 0;
					$newSelectedOption = $selectedOption;

					if ($selectedOption === 'all' && $searchValue === '') {
						$response['content'] = "No foods have been added to the system yet. Please stay with us!";
					} elseif ($selectedOption !== 'all' && $searchValue === '') {
						if ($selectedOption === 'main_dish') {
							$newSelectedOption = 'main dish';
						} elseif ($selectedOption === 'short_eat') {
							$newSelectedOption = 'short eat';
						}
						$response['content'] = "No foods have been added to the system in the category '$newSelectedOption'.<br/>Please stay with us!";
					} elseif ($selectedOption === 'all' && $searchValue !== '') {
						$trimmedSearchValue = substr($searchValue, 1, -1); // Remove first and last characters
						$response['content'] = "No foods have been added to the system yet matching the term '$trimmedSearchValue'.<br/>Please stay with us!";
					} elseif ($selectedOption !== 'all' && $searchValue !== '') {
						if ($selectedOption === 'main_dish') {
							$newSelectedOption = 'main dish';
						} elseif ($selectedOption === 'short_eat') {
							$newSelectedOption = 'short eat';
						}
						$trimmedSearchValue = substr($searchValue, 1, -1); 
						$response['content'] = "No foods have been added to the system yet in the category '$newSelectedOption' matching the term '$trimmedSearchValue'.<br/>Please stay with us!";
					}
				} elseif ($foodCount > 0 && $foodCount <= 20) {
					$response['segment'] = 1;
				} elseif ($foodCount > 20) {
					$response['segment'] = ceil($foodCount / 20); 
				}
			}
			
			if ($pageProcess === 'skip_food_seg'){
				if (isset ($_POST['segmentIndex']) && isset ($_POST['className'])) {
					$segmentIndex = $_POST['segmentIndex'];
					$className = $_POST['className'];
					$response['segment'] = 1; 
				}
			}
			
			if ($response['segment'] !== 0) {
				
				$availability = 'available';
				if ($pageProcess === 'page_load'){
					
					if ($response['segment'] === 1) {
						if ($selectedOption === 'all' && $searchValue === '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ?";
						} elseif ($selectedOption !== 'all' && $searchValue === '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND category = ?";
						} elseif ($selectedOption === 'all' && $searchValue !== '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_name LIKE ?";
							$searchValue = '%' . $searchValue . '%';
						} elseif ($selectedOption !== 'all' && $searchValue !== '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_name LIKE ? AND category = ?";
							$searchValue = '%' . $searchValue . '%';
						}
						
					} else {
						$offset = 0; 
						if ($selectedOption === 'all' && $searchValue === '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? LIMIT 20 OFFSET $offset";
						} elseif ($selectedOption !== 'all' && $searchValue === '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND category = ? LIMIT 20 OFFSET $offset";
						} elseif ($selectedOption === 'all' && $searchValue !== '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_name LIKE ? LIMIT 20 OFFSET $offset";
							$searchValue = '%' . $searchValue . '%';
						} elseif ($selectedOption !== 'all' && $searchValue !== '') {
							$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_name LIKE ? AND category = ? LIMIT 20 OFFSET $offset";
							$searchValue = '%' . $searchValue . '%';
						}
					}
				}
				
				if ($pageProcess === 'skip_food_seg'){
					$offset = $segmentIndex * 20;
					if ($selectedOption === 'all' && $searchValue === '') {
						$getFoodDetails = "SELECT * FROM foods WHERE availability = ? LIMIT 20 OFFSET $offset";
					} elseif ($selectedOption !== 'all' && $searchValue === '') {
						$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND category = ? LIMIT 20 OFFSET $offset";
					} elseif ($selectedOption === 'all' && $searchValue !== '') {
						$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_name LIKE ? LIMIT 20 OFFSET $offset";
						$searchValue = '%' . $searchValue . '%';
					} elseif ($selectedOption !== 'all' && $searchValue !== '') {
						$getFoodDetails = "SELECT * FROM foods WHERE availability = ? AND food_name LIKE ? AND category = ? LIMIT 20 OFFSET $offset";
						$searchValue = '%' . $searchValue . '%';
					}
				}
				
				if ($stmt = $conn->prepare($getFoodDetails)) {
					if ($selectedOption === 'all' && $searchValue === '') {
						$stmt->bind_param("s", $availability);
					} elseif ($selectedOption !== 'all' && $searchValue === '') {
						$stmt->bind_param("ss", $availability, $selectedOption);
					} elseif ($selectedOption === 'all' && $searchValue !== '') {
						$stmt->bind_param("ss", $availability, $searchValue);
					} elseif ($selectedOption !== 'all' && $searchValue !== '') {
						$stmt->bind_param("sss", $availability, $searchValue, $selectedOption);
					}
					$stmt->execute();
					$result = $stmt->get_result();

					if ($result->num_rows > 0) {
						$foodDetails = array(); 
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
						
						if ($pageProcess === 'page_load'){
							$foodDetailsCount = count($foodDetails);
							if ($foodDetailsCount > 4) {
								if ($foodDetailsCount % 4 !== 0 ) {
									$response['moreHeightSize'] = 330 * intdiv($foodDetailsCount, 4);
								} else {
									$response['moreHeightSize'] = 330 * (intdiv($foodDetailsCount, 4) - 1);

								}
							}
							$decreaseHeightSize = ($response['moreHeightSize'] / 330) ;
						
							if ($decreaseHeightSize > 2) {
								$response['decreaseHeightSize'] = $decreaseHeightSize * 20;
							}	
						
							if ($response['segment'] > 1) {
								$segmentNumbers = [];
								for ($i = 1; $i <= $response['segment']; $i++) {
									$segmentNumbers[] = $i;
								}
								$response['segmentNumbers'] = implode(',', $segmentNumbers);
							}
						}
						
						if ($selectedOption === 'all' && $searchValue === '') {
							if (getFoodCount($conn) > 20){
								$response['moreButton'] = 'yes';
								$response['moreHeightSize'] = 330 * 4;
							} else {
								$response['moreButton'] = 'no';
							}
						} elseif ($selectedOption !== 'all' && $searchValue === '') {
							if (filterFoodCount($conn, $selectedOption) > 20){
								$response['moreButton'] = 'yes';
								$response['moreHeightSize'] = 330 * 4;
							} else {
								$response['moreButton'] = 'no';
							}
						} elseif ($selectedOption === 'all' && $searchValue !== '') {
							if (searchFoodCount($conn, $searchValue) > 20){
								$response['moreButton'] = 'yes';
								$response['moreHeightSize'] = 330 * 4;
							} else {
								$response['moreButton'] = 'no';
							}
						} elseif ($selectedOption === 'all' && $searchValue !== '') {
							if (searchFilterFoodCount($conn, $searchValue, $selectedOption) > 20){
								$response['moreButton'] = 'yes';
								$response['moreHeightSize'] = 330 * 4;
							} else {
								$response['moreButton'] = 'no';
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


function searchFoodCount($conn, $searchTerm) {
    $availability = 'available';
    $getFoodCount = "SELECT COUNT(*) AS food_count FROM foods WHERE availability = ? AND food_name LIKE ?";

    if ($stmt = $conn->prepare($getFoodCount)) {
        $stmt->bind_param("ss", $availability, $searchTerm);
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


function filterFoodCount($conn, $option) {
    $availability = 'available';
    $getFoodCount = "SELECT COUNT(*) AS food_count FROM foods WHERE availability = ? AND category = ?";

    if ($stmt = $conn->prepare($getFoodCount)) {
        $stmt->bind_param("ss", $availability, $option);
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


function searchFilterFoodCount($conn, $searchTerm, $option) {
    $availability = 'available';
    $getFoodCount = "SELECT COUNT(*) AS food_count FROM foods WHERE availability = ? AND food_name LIKE ? AND category = ?";

    if ($stmt = $conn->prepare($getFoodCount)) {
        $stmt->bind_param("sss", $availability, $searchTerm, $option);
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