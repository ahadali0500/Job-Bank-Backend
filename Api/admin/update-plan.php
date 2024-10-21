<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];
$type = $_POST['type'];
$cv_template = $_POST['cv_template'];
$quiz_attempts = $_POST['quiz_attempts'];
$quiz_questions = $_POST['quiz_questions'];
$interview_attempts = $_POST['interview_attempts'];
$interview_questions = $_POST['interview_questions'];
$days = $_POST['days'];
$languages = $_POST['languages'];
$comment = $_POST['comment'];


    $dd = "UPDATE `packages` SET `name` = '$name', `price` = '$price', `type` = '$type', `cv_template` = '$cv_template', `quiz_attempts` = '$quiz_attempts', `quiz_questions` = '$quiz_questions', `interview_attempts` = '$interview_attempts', `interview_questions` = '$interview_questions', `days` = '$days', `languages` = '$languages', `comment` = '$comment' WHERE `id` = '$id'";
    $cc = mysqli_query($con, $dd);

    if ($cc) {
        http_response_code(200);
        $output['code'] = 200;
        $output['message'] = "Plans updated successfully!";
        echo json_encode($output);
    } else {
        http_response_code(500);
        $output['code'] = 500;
        $output['message'] = "Failed to update Plan.";
        echo json_encode($output);
    }

return $output;
?>
