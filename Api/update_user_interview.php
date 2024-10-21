<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');

$id = $_POST['id'];
$interviewQuestions = $_POST['interview_questions'];

$sql_1 = "UPDATE `user_interview` SET `interview_questions`=? WHERE `id` =?";

$stmt = $con->prepare($sql_1);

if ($stmt) {
    $stmt->bind_param("si", $interviewQuestions, $id);
    $stmt->execute();

    $output = array();

    if ($stmt->affected_rows > 0) {
        $output['code'] = 200;
        $output['message'] = "Updated successfully";
    } else {
        $output['code'] = 400;
        $output['error'] = $conn->error;
        $output['message'] =  "No changes in the data";
    }

    $stmt->close();
} else {
    $output['code'] = 400;
    $output['message'] = "Prepare statement failed: " . $con->error;
}

echo json_encode($output);


?>