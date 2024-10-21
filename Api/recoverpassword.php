<?php
header("Access-Control-Allow-Origin:*"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');



$code = $_POST['code'];
$password = $_POST['password'];

$sql = "SELECT * FROM `codeRecoverPassword` WHERE `code`='$code'";
$queryResult = mysqli_query($con,$sql);

if (mysqli_num_rows($queryResult)>0) {
           
            $data=mysqli_fetch_assoc($queryResult);
            $email=$data['email'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $dd="UPDATE `users` SET `password`='$hashedPassword' WHERE `email`='$email' ";
            $cc=mysqli_query($con, $dd);

            $cv="DELETE FROM `codeRecoverPassword` WHERE `code`='$code'";
            mysqli_query($con, $cv);

            echo json_encode(array("code" => 200, "message" => "Password recovery successfully!"));
            
    
       
    
    
    http_response_code( (mysqli_num_rows($queryResult) > 0) ? 200 : 400 );
    // Update response code based on query result
} else {
    echo json_encode(array("code" => 400, "message" => "try again later"));
}



?>
