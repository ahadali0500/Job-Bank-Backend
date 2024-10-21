<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');

// Getting user values
$user_id = $_POST['user_id'];
$totalQuestions = $_POST['total_questions'];
$correctAnswers = $_POST['correct_answers'];
$score = $_POST['score'];
$quizDetail = $_POST['quiz_detail'];
$quizTitle = $_POST['quiz_title'];
$createdAt = $_POST['created_at'];

$conn = $con->prepare('INSERT INTO `quiz_history`(`user_id`, `total_questions`, `correct_answers`, `score`, `quiz_detail`, `quiz_title`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?)');   
    $conn->bind_param("sssssss", $user_id,$totalQuestions,$correctAnswers,$score,$quizDetail,$quizTitle,$createdAt);
    $conn->execute();
   if ($conn->affected_rows <= 0) {        
	    $output['message'] = "Process failed. Please try again later.";
        $output['code'] = 400;
        $output['error'] = $conn->error;
        echo json_encode($output);
		http_response_code(400); 
    } else {
        $output['message'] = "Quiz Save Successfully";
        $output['code'] = 200;
        echo json_encode($output);
		http_response_code(200);
    }

?>