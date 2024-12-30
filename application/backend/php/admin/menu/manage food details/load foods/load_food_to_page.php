<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;

    if (isset($_POST['category']) && isset($_POST['searchTerm']) && isset($_POST['offset'])) {
        $category = $_POST['category'];
        $searchTerm = $_POST['searchTerm'];
		$offset = intval($_POST['offset']); // Convert offset to integer

        ob_start();
        include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
        $includedContent = ob_get_clean();

        $conn_status_data = json_decode($includedContent, true);
        $conn_status = $conn_status_data['connection_status'];

        if ($conn_status === "successfully") {
            // Define SQL query based on category and search term
            if ($category === 'all' && $searchTerm === '') {
                $selectFoodDetails = "SELECT * FROM foods 
                                      WHERE availability IN ('available', 'unavailable') 
                                      ORDER BY CASE 
                                          WHEN category = 'main_dish' THEN 1 
                                          WHEN category = 'short_eat' THEN 2 
                                          WHEN category = 'dessert' THEN 3 
                                          ELSE 4 
                                      END
                                      LIMIT ?, 10"; // Use LIMIT to get only 10 records starting from offset
            } elseif ($category !== 'all' && $searchTerm === '') {
                $selectFoodDetails = "SELECT * FROM foods 
                                      WHERE category = ? 
                                      AND availability IN ('available', 'unavailable') 
                                      ORDER BY CASE 
                                          WHEN category = 'main_dish' THEN 1 
                                          WHEN category = 'short_eat' THEN 2 
                                          WHEN category = 'dessert' THEN 3 
                                          ELSE 4 
                                      END
                                      LIMIT ?, 10"; // Use LIMIT to get only 10 records starting from offset
            } elseif ($category === 'all' && $searchTerm !== '') {
                $selectFoodDetails = "SELECT * FROM foods 
                                      WHERE food_name LIKE ? 
                                      AND availability IN ('available', 'unavailable') 
                                      ORDER BY CASE 
                                          WHEN category = 'main_dish' THEN 1 
                                          WHEN category = 'short_eat' THEN 2 
                                          WHEN category = 'dessert' THEN 3 
                                          ELSE 4 
                                      END
                                      LIMIT ?, 10"; // Use LIMIT to get only 10 records starting from offset
            } elseif ($category !== 'all' && $searchTerm !== '') {
                $selectFoodDetails = "SELECT * FROM foods 
                                      WHERE category = ? 
                                      AND food_name LIKE ? 
                                      AND availability IN ('available', 'unavailable') 
                                      ORDER BY CASE 
                                          WHEN category = 'main_dish' THEN 1 
                                          WHEN category = 'short_eat' THEN 2 
                                          WHEN category = 'dessert' THEN 3 
                                          ELSE 4 
                                      END
                                      LIMIT ?, 10"; // Use LIMIT to get only 10 records starting from offset
            }

            // Prepare and execute the query
            if ($stmt = $conn->prepare($selectFoodDetails)) {
                // Bind parameters based on the type of query
                if ($category === 'all' && $searchTerm === '') {
                    $stmt->bind_param("i", $offset); // Offset is an integer
                } elseif ($category !== 'all' && $searchTerm === '') {
                    $stmt->bind_param("si", $category, $offset); // Offset is an integer
                } elseif ($category === 'all' && $searchTerm !== '') {
                    $searchTerm = "%" . $searchTerm . "%"; // Add wildcard characters for LIKE search
                    $stmt->bind_param("si", $searchTerm, $offset); // Offset is an integer
                } elseif ($category !== 'all' && $searchTerm !== '') {
                    $searchTerm = "%" . $searchTerm . "%"; // Add wildcard characters for LIKE search
                    $stmt->bind_param("ssi", $category, $searchTerm, $offset); // Offset is an integer
                }
				
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $foodDetails = array(); // Initialize the array
                    while ($row = $result->fetch_assoc()) {
                        $foodDetail = array(
                            'foodNumber' => $row['food_number'],
                            'foodName' => capitalizeAfterSpaceOrSpecialChar($row['food_name']),
                            'discountPrice' => "Rs ".$row['discount_price'],
                            'nonDiscountPrice' => "Rs ".$row['non_discount_price'],
                            'category' => $row['category'],
                            'availability' => capitalizeAfterSpaceOrSpecialChar($row['availability'])
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

                        $foodDetails[] = $foodDetail; // Append this food detail to the array
                    }
                    $response['foodDetails'] = $foodDetails; // Set the foodDetails array in the response
					if ($result->num_rows < 10) {
                        $response['noMoreFoods'] = true; // Set the flag to indicate no more foods
                    }
                } else {
					if ($offset === 0) {
						if ($searchTerm === '') {
							$response['noFoods'] = true;
							$response['content'] = "No foods added to the system yet!";
						} else {
							$response['noFoods'] = true;
							$response['content'] = "No foods found with the name '$searchTerm'";
						}
					} else {
						$response['finishTable'] = true;
					}
                }
                $stmt->close();
            } else {
                $response = array(
                    'success' => false,
                    'alert' => 'Error in selectFoodDetails query.',
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
