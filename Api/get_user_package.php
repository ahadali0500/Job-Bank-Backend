<?php
header("Access-Control-Allow-Origin: *"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');

$user_id = $_POST['user_id'];
$active = $_POST['active'];
$output = array();
$conn = $con->prepare("SELECT * FROM user_packages WHERE user_id=?");
$conn->bind_param("i", $user_id);
$conn->execute();
$resultUser = $conn->get_result();
$user = $resultUser->fetch_assoc();
if ($user) {
    $output['response'] = $user;
    $output['code'] = 200;
    http_response_code(200);
    echo json_encode($output);
}else{
    $output['message'] = "Not able to get user Package";
    $output['error'] = $conn->error;
    $output['code'] = 400;
    http_response_code(400);
    echo json_encode($output);
}
?>
