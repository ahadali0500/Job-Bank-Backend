<?php
header("Access-Control-Allow-Origin: *"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');



$email = $_POST['email'];
$sql = "SELECT * FROM `users` WHERE `email`='$email'";
$queryResult = mysqli_query($con,$sql);

if (mysqli_num_rows($queryResult)>0) {
            $verificationCode = generateVerificationCode();
            $conn = $con->prepare('INSERT INTO `codeRecoverPassword`(`email`, `code`) VALUES (?, ?)');
            $conn->bind_param("ss", $email, $verificationCode);
            $conn->execute();
            $from = "Job Bank <noreply@desired-techs.com>";
            $headers = "From: $from\r\n";
            $headers .= "Reply-To: $from\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $subject = "Recover Passsword";
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
                                <p style="color: black;">We received a request to reset your password. If you didnt make this request, you can ignore this email.</p>
                                <p style="color: black;">Otherwise, click the button below to set a new password and regain access to your account:</p>
                                <a style="color: #ffffff;" href="https://job-tech.vercel.app/auth/recover-password/' .$verificationCode . '" class="button">Recover password</a>
                            </div>
                        </div>
                </body>
                </html>
                ';
            if (mail($email, $subject, $message, $headers)) {
                echo json_encode(array("code" => 200, "message" => "Recovery link has been sent successfully!"));
            } else {
                echo json_encode(array("code" => 400, "message" => "Failed to send email. Please try again later."));
                error_log(error_get_last()['message']); // Log the error message
            }
    

    http_response_code( (mysqli_num_rows($queryResult) > 0) ? 200 : 400 );
    // Update response code based on query result
} else {
    echo json_encode(array("code" => 400, "message" => "Email not found in our records!"));
}

function generateVerificationCode()
{
    return bin2hex(random_bytes(16));
}

?>
