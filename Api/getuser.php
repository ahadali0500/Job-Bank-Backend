<?php
include('connection.php');

require_once '../jwt/vendor/autoload.php';
use \Firebase\JWT\JWT;

$id = $_POST['id'];
$output = array();

$conn = $con->prepare("SELECT * FROM users WHERE user_id=?");
$conn->bind_param("s", $id);
$conn->execute();
$result = $conn->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $output['user_detail'] = $user;
    $output['token'] = $jwt;
    $output['code'] = 200;
    http_response_code(200);
    echo json_encode($output);
} else {    
    http_response_code(400);
    $output['message'] = "Invalid";
    $output['code'] = 400;
    echo json_encode($output);
}

return $output;
?>
