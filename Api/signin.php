<?php
include('connection.php');

require_once '../jwt/vendor/autoload.php';
use \Firebase\JWT\JWT;

$email = $_POST['email'];
$password = $_POST['password'];
$output = array();

$conn = $con->prepare("SELECT * FROM users WHERE email=?");
$conn->bind_param("s", $email);
$conn->execute();
$result = $conn->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if ($user['is_verified'] == 1) { // Check if the account is verified
        if (password_verify($password, $user['password'])) {

            $key = '1a3LM3W966D6QTJ5BJb9opunkUcw_d09NCOIJb9QZTsrneqOICoMoeYUDcd_NfaQyR787PAH98Vhue5g938jdkiyIZyJICytKlbjNBtebaHljIR6-zf3A2h3uy6pCtUFl1UhXWnV6madujY4_3SyUViRwBUOP-UudUL4wnJnKYUGDKsiZePPzBGrF4_gxJMRwF9lIWyUCHSh-PRGfvT7s1mu4-5ByYlFvGDQraP4ZiG5bC1TAKO_CnPyd1hrpdzBzNW4SfjqGKmz7IvLAHmRD-2AMQHpTU-hN2vwoA-iQxwQhfnqjM0nnwtZ0urE6HjKl6GWQW-KLnhtfw5n_84IRQ';
            $jwt = JWT::encode(
                array(
                    'data'	=> array(
                        "user_id" => $user['user_id'],
                        "email" => $user['email'],
                        "phone" => $user['phone'],
                    )
                ),
                $key,
                'HS256'
            );
            

            //$user_ids=$user['is_verified'];
            // $dd="INSERT INTO `token`(`user_id`, `token`) VALUES ('$user_ids','$jwt')";
            // mysqli_query($conn,$dd);

            $output['user_detail'] = $user;
            $output['token'] = $jwt;
            $output['code'] = 200;
            http_response_code(200);
            echo json_encode($output);
        } else {
            http_response_code(400);
            $output['message'] = "Invalid email or password";
            $output['code'] = 400;
            echo json_encode($output);
        }
    } else {
        // Account not verified
        http_response_code(400);
        $output['message'] = "Account not verified. Please check your email for verification instructions.";
        $output['code'] = 400;
        echo json_encode($output);
    }
} else {
    // User not found
    http_response_code(400);
    $output['message'] = "Invalid email or password";
    $output['code'] = 400;
    echo json_encode($output);
}

return $output;
?>
