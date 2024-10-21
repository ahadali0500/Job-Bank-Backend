<?php
header("Access-Control-Allow-Origin: *"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

// Getting user values
$id = $_POST['id'];
$quiz_attempts = $_POST['quiz_attempts'];
$interview_attempts = $_POST['interview_attempts'];

$out="UPDATE `user_history` SET  `quiz_attempts`='$quiz_attempts', `interview_attempts`='$interview_attempts'  WHERE user_package_id = '$id' ORDER BY id DESC LIMIT 1";
$DDD=mysqli_query($con,$out);

$sql_1 = "UPDATE `user_packages` SET 
    `quiz_attempts`=?,
    `interview_attempts`=? 
    WHERE `id` =?";

$stmt = $con->prepare($sql_1);

if ($stmt) {
    $stmt->bind_param("ssi", $quiz_attempts, $interview_attempts,$id);
    $stmt->execute();

    $output = array();

    if ($stmt->affected_rows > 0) {
        $output['message'] = "Updated successfully";
    } else {
        $output['code'] = 400;
        $output['error'] = [ $package_id, $package_name, $days, $quiz_attempts, $quiz_questions, $interview_attempts, $interview_questions, $cv_templates, $active, $start_date, $expire_date, $id];
        $output['message'] =  "No changes in the data";
    }
} else {
    $output['code'] = 400;
    $output['message'] = "Prepare statement failed: " . $con->error;
}
echo json_encode($output);
?>