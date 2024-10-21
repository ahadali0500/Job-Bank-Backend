<?php
include('connection.php');

// Getting user values
$user_id = $_POST['user_id'];
$price = $_POST['price'];
$days = $_POST['days'];
$start_date = $_POST['start_date'];
$expire_date = $_POST['expire_date'];
$interview_attempt = $_POST['interview_attempt'];
$quiz_attempt = $_POST['quiz_attempt'];
$package_name = $_POST['package_name'];
$package_id = $_POST['package_id'];
$createdAt = date('Y-m-d H:i:s');

$conn = $con->prepare('INSERT INTO `user_packages_history`(`user_id`, `price`, `days`, `start_date`, `expire_date`, `interview_attempt`, `quiz_attempt`, `package_name`, `package_id`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');   
   $conn->bind_param("ssssssssss", $user_id, $price, $days, $start_date, $expire_date, $interview_attempt, $quiz_attempt, $package_name ,$package_id, $createdAt);
   $conn->execute();
   if ($conn->affected_rows <= 0) {        
	    $output['message'] = "Process failed. Please try again later.";
        $output['code'] = 400;
        $output['error'] = $conn->error;
        echo json_encode($output);
		http_response_code(400); 
    } else {
        $output['message'] = "Data Saved Successfully";
        $output['code'] = 200;
        echo json_encode($output);
		http_response_code(200);
    }

echo json_encode($output);
?>