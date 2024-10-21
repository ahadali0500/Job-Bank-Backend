<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');

$user_id = $_POST['user_id'];
$interview_questions = $_POST['interview_questions'];
$topic = $_POST['topic'];
$createdAt = date('Y-m-d H:i:s');

$conn = $con->prepare('INSERT INTO `user_interview`(`user_id`, `topic`, `interview_questions`, `created_at`) VALUES (?, ?, ?, ?)');   
    $conn->bind_param("ssss", $user_id, $topic, $interview_questions, $createdAt);
    $conn->execute();
   if ($conn->affected_rows <= 0) {        
	    $output['message'] = "Process failed. Please try again later.";
        $output['code'] = 400;
        $output['error'] = $conn->error;
        echo json_encode($output);
		http_response_code(400); 
    } else {
        $interview_question_id = $conn->insert_id;
        $output['message'] = "Interview saved Successfully";
        $output['code'] = 200;
        $output['interview_question_id'] = $interview_question_id;  // Add the ID to output
        echo json_encode($output);
		http_response_code(200);
    }

?>