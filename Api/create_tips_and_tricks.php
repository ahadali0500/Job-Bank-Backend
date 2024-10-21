<?php
include('connection.php');

// Getting user values
$title = $_POST['title'];
$tips = $_POST['tips'];
$color = $_POST['color'];
$icon = $_POST['icon'];
$createdAt = date('Y-m-d H:i:s');

$conn = $con->prepare('INSERT INTO `tips_and_tricks`(`title`, `tips_and_tricks`, `icon`, `color`, `created_at`) VALUES (?, ?, ?, ?, ?)');   
    $conn->bind_param("sssss", $title, $tips, $icon, $color, $createdAt);
    $conn->execute();
   if ($conn->affected_rows <= 0) {        
	    $output['message'] = "Process failed. Please try again later.";
        $output['code'] = 400;
        $output['error'] = $conn->error;
        echo json_encode($output);
		http_response_code(400); 
    } else {
        $output['message'] = "Object created Successfully";
        $output['code'] = 200;
        echo json_encode($output);
		http_response_code(200);
    }
?>