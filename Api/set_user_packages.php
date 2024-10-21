<?php
include('connection.php');

// Getting user values
$user_id = $_POST['user_id'];
$days = $_POST['days'];
$quiz_attempts = $_POST['quiz_attempts'];
$quiz_questions = $_POST['quiz_questions'];
$interview_attempts = $_POST['interview_attempts'];
$interview_questions = $_POST['interview_questions'];
$cv_templates = $_POST['cv_templates'];
$active = $_POST['active'];
$start_date = $_POST['start_date'];
$expire_date = $_POST['expire_date'];

$connInsert = $con->prepare('INSERT INTO `user_packages`(`user_id`, `days`, `quiz_attempts`, `quiz_questions`, `interview_attempts`, `interview_questions`, `cv_templates`, `active`, `start_date`, `expire_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? ,?)');   
        $connInsert->bind_param("ssssssssss", $user_id, $days, $quiz_attempts, $quiz_questions, $interview_attempts, $interview_questions, $cv_templates, $active, $start_date, $expire_date);
        $connInsert->execute();
        if ($connInsert->affected_rows <= 0) {        
            $output['message'] = "Process failed. Please try again later.";
            $output['code'] = 400;
            $output['error'] = $conn->error;
            echo json_encode($output);
            http_response_code(400); 
        } else {
            $output['message'] = "User Package Add Successfully";
            $output['code'] = 200;
            echo json_encode($output);
            http_response_code(200);
        }
?>