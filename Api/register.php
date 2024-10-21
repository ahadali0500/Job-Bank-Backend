<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');

// Getting user values
$fullName = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$createdAt = date('Y-m-d H:i:s');
$phone = '';
$dateOfBirth = '1950-01-01 00:00:00';
$address = '';
$package = 'Free';
$isVerified = 0;

// Define the base directory for user_data
$baseDirectory = "../user_data/user_$email/images/";

// Create the user_data directory if it doesn't exist

$pImage = '';
$baseDirectory = "../user_data/user_$email/images/";
if (!is_dir($baseDirectory)) {
    mkdir($baseDirectory, 0755, true);
}
if ($_FILES["image"]["name"]) {
    $imagefileinfo = pathinfo($_FILES["image"]["name"]);
    $pImage = $imagefileinfo['filename'] . "_" . time() . "." . $imagefileinfo['extension'];
    $imageDestinationPath = $baseDirectory . $pImage;
    move_uploaded_file($_FILES["image"]["tmp_name"], $imageDestinationPath);
    $output = array();
}

try {
    // Checking if email exists
    $conn = $con->prepare('SELECT `email` FROM `users` WHERE `email`=?');
    $conn->bind_param("s", $email);
    $conn->execute();
    $conn->store_result();

    if ($conn->num_rows > 0) {
        $output['message'] = "This email is already registered with another account, if it is yours, please login instead";
        $output['code'] = 400;
        echo json_encode($output);
        http_response_code(400);
    } else {
        $conn = $con->prepare('INSERT INTO `users`(`name`, `email`, `password`, `created_At`, `profile_image`, `is_verified`, `phone`, `date_of_birth`, `address`, `package`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $conn->bind_param("ssssssssss", $fullName, $email, $hashedPassword, $createdAt, $pImage, $isVerified, $phone, $dateOfBirth, $address, $package);
        $conn->execute();
        if ($conn->affected_rows <= 0) {
            $output['message'] = "Registration failed. Please try again later.";
            $output['code'] = 400;
            echo json_encode($conn->error);
            http_response_code(400);
        } else {
            $verificationCode = generateVerificationCode();
            $conn = $con->prepare('INSERT INTO `users_verification`(`email`, `verification_code`) VALUES (?, ?)');
            $conn->bind_param("ss", $email, $verificationCode);
            $conn->execute();
            // Send the verification email
            $from = "Job Bank <noreply@desired-techs.com>";
            $headers = "From: $from\r\n";
            $headers .= "Reply-To: $from\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $subject = "Account Verification";
            $message = '
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Email Template</title>
                        <style>
                            body {
                                margin: 0;
                                padding: 0;
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                            }
                            .container {
                                width: 100%;
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #ffffff;
                                border: 1px solid #dddddd;
                            }
                            .header {
                                background-color: #007bff;
                                color: #ffffff;
                                padding: 20px;
                                text-align: center;
                                font-size: 24px;
                                font-weight: bold;
                            }
                            .content {
                                padding: 20px;
                                font-size: 16px;
                                color: #333333;
                            }
                            .content h1 {
                                font-size: 22px;
                                margin-bottom: 20px;
                            }
                            .content p {
                                line-height: 1.5;
                                margin-bottom: 20px;
                            }
                            .button {
                                display: block;
                                width: 200px;
                                margin: 0 auto;
                                padding: 10px;
                                text-align: center;
                                background-color: #003366;
                                color: #ffffff;
                                text-decoration: none;
                                border-radius: 5px;
                                font-size: 16px;
                                margin-top: 20px;
                            }
                        </style>
                    </head>
                    <body>
                    <div class="container">
                        <center><img style="width:180px" src="https://job-tech.vercel.app/image/logo/updatedlogo.png">
                        </center>                        
                        <div class="content">
                        <h1>Hello '.$fullName.',</h1>
                        <p style="color: black;" >Thank you for registering! Please click the button below to verify your email address.</p>
                        <a style="color: #ffffff;" href="https://job-tech.vercel.app/auth/verify/' .$verificationCode . '" class="button">Verify Email</a>
                        </div>
                    </div>
                </body>
                </html>
                ';
            if (mail($email, $subject, $message, $headers)) {
                // echo "Email sent successfully";
            } else {
                echo "Failed to send email";
                error_log(error_get_last()['message']); // Log the error message
            }
            if ($conn->affected_rows <= 0) {
                $output['message'] = "Failed to send verification email";
                $output['code'] = 400;
                echo json_encode($output);
                http_response_code(400);
            } else {
                $conn = $con->prepare("SELECT * FROM users WHERE email=?");
                $conn->bind_param("s", $email);
                $conn->execute();
                $result = $conn->get_result();
                $user = $result->fetch_assoc();
                unset($user['password']);
                $output['user_detail'] = $user;
                $output['code'] = 200;
                echo json_encode($output);
                http_response_code(200);
            }
        }
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

function generateVerificationCode()
{
    return bin2hex(random_bytes(16));
}
?>
