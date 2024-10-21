<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

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

// $dd="SELECT * FROM `packages` WHERE `slug`='$slug'";
// $cc=mysqli_query($con, $dd);
// if(mysqli_num_rows($cc)>0){
//     http_response_code(201);
//     $output['code'] = 201;
//     $output['message'] = "Slug is already used";
//     echo json_encode($output);
// }else {
$dd="INSERT INTO `packages`(`name`, `price`, `type`, `cv_template`, `quiz_attempts`, `quiz_questions`, `interview_attempts`, `interview_questions`, `days`, `languages`, `comment`) VALUES ('$name','$price','$type','$cv_template','$quiz_attempts','$quiz_questions','$interview_attempts','$interview_questions','$days','$languages', '$comment')";
$cc=mysqli_query($con, $dd);
if($cc){
    http_response_code(200);
    $output['code'] = 200;
    $output['message'] = "Plan created successfuly!";
    echo json_encode($output);

}else {
    http_response_code(500);
    $output['code'] = 500;
    echo json_encode($output);
}
// }
return $output;
?>
