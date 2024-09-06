<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/check authentication token/check_auth_token.php';
?>

<?php
	include '/xampp/htdocs/FoodFrenzy/application/backend/php/admin and register user/user type check/admin/admin_checking.php';
?>

<?php
	ob_start();
	include '/xampp/htdocs/FoodFrenzy/configuration/php/connect to database/con_server.php';
	$includedContent = ob_get_clean();
		
	$conn_status_data = json_decode($includedContent, true);
	$conn_status = $conn_status_data['connection_status'];

	if ($conn_status === "successfully") {
		$getUserNameQuery = "SELECT first_name FROM users WHERE user_id = ?";

		if ($stmt = $conn->prepare($getUserNameQuery)) {
		$stmt->bind_param("s", $UserId);
		$stmt->execute();
		$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc(); 
				$firstName = strtoupper($row['first_name']);
			} else {
				$errorMessage = "Database user type field error: " . $conn->error; 
				redirectToErrorPage($errorMessage);        
			}
		} else {
			$errorMessage = "Database getUserNameQuery error: " . $conn->error; 
			redirectToErrorPage($errorMessage);
		}
		$stmt->close(); 
	   
	} else {    
		$errorMessage = "Database connection failed: " . $connection_status;
		redirectToErrorPage($errorMessage);        
	}
?>


<html>
<head>
    <link rel="stylesheet" type="text/css" href="/FoodFrenzy/application/frontend/css/pages/admin/home/home.css">
</head>
<body>  
	<?php
		include '/xampp/htdocs/FoodFrenzy/application/frontend/html/same/admin/side bar/side_bar.html';
	?>

	<div class="cont_container"> 
		<img src="/FoodFrenzy/storage/photos/system/logo.png" id="big_logo">
		<p>WELCOME<br/><span id="f_name"><?php echo $firstName; ?>.!</span></p>
	</div>
	
	<script src="/FoodFrenzy/application/frontend/javascript/pages/admin/home/home.js"></script>
	
</body>
</html>
