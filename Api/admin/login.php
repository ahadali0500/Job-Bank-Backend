<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$email = $_POST['email'];
$password = $_POST['password'];
$dd="SELECT * FROM `admin` WHERE `email`='$email' AND `password`='$password'";
$cc=mysqli_query($con, $dd);
if(mysqli_num_rows($cc)>0){
    http_response_code(200);
    $output['code'] = 200;
    echo json_encode($output);

}else {
    http_response_code(404);
    $output['code'] = 404;
    echo json_encode($output);
}

return $output;
?>
