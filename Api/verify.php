<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
include('connection.php');
if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];
    $conn = $con->prepare('SELECT `email` FROM `users_verification` WHERE `verification_code` = ?');
    $conn->bind_param("s", $verificationCode);
    $conn->execute();
    $conn->store_result();
    if ($conn->num_rows > 0) {
        $conn->bind_result($email);
        $conn->fetch();
        $updateConn = $con->prepare('UPDATE `users` SET `is_verified` = 1 WHERE `email` = ?');
        $updateConn->bind_param("s", $email);
        $updateConn->execute();
        $deleteConn = $con->prepare('DELETE FROM `users_verification` WHERE `verification_code` = ?');
        $deleteConn->bind_param("s", $verificationCode);
        $deleteConn->execute();
        $connUser = $con->prepare("SELECT * FROM users WHERE email=?");
        $connUser->bind_param("s", $email);
        $connUser->execute();
        $resultUser = $connUser->get_result();
        $user = $resultUser->fetch_assoc();
        if ($user) {
            $user_id = $user['user_id'];
            $type = 'Monthly';
            $name = 'Free';
            $connPackage = $con->prepare("SELECT * FROM packages WHERE type=? AND name=?");
            $connPackage->bind_param("ss", $type,$name);
            $connPackage->execute();
            $packageResult = $connPackage->get_result();
            $package = $packageResult->fetch_assoc();
            if ($package) {
                $CurrentDate = date('Y-m-d H:i:s');
                $FutureDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + 30 days'));
                $active = 1;
                $packages = $package;
                $connInsert = $con->prepare('INSERT INTO `user_packages`(`user_id`, `package_id`, `package_name`, `days`, `quiz_attempts`, `quiz_questions`, `interview_attempts`, `interview_questions`, `cv_templates`, `active`, `start_date`, `expire_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?)');   
                $connInsert->bind_param("ssssssssssss", $user_id, $packages['id'], $packages['name'], $packages['days'], $packages['quiz_attempts'], $packages['quiz_questions'], $packages['interview_attempts'], $packages['interview_questions'], $packages['cv_template'], $active, $CurrentDate, $FutureDate);
                $connInsert->execute();
                $inserted_id = $con->insert_id;

               $connInsertHistory = "INSERT INTO user_history (user_id, package_id, transction_id, price, user_package_id, package_name, days, quiz_attempts, quiz_questions, interview_attempts, interview_questions, cv_templates, active, start_date, expire_date) VALUES ('$user_id', '{$packages['id']}', '', '', '$inserted_id', '{$packages['name']}', '{$packages['days']}', '{$packages['quiz_attempts']}', '{$packages['quiz_questions']}', '{$packages['interview_attempts']}', '{$packages['interview_questions']}', '{$packages['cv_template']}', '$active', '$CurrentDate', '$FutureDate')";
                mysqli_query($con,$connInsertHistory);

                if ($connInsert->affected_rows <= 0) {    
                    echo 'Process failed. Please try again later.';                
                } else {
                    $packageId = $connInsert->insert_id;
                    $updateIdConn = $con->prepare('UPDATE `users` SET `package` = ? WHERE `user_id` = ?');
                    $updateIdConn->bind_param("si", $packageId, $user_id);
                    $updateIdConn->execute();
                    if ($updateIdConn->affected_rows > 0) {
                         http_response_code(200);
                         echo json_encode(array("code" => 200, "message" => "Email verified successfully. You can now login."));
                    }else{
                        http_response_code(200);
                        echo json_encode(array("code" => 200, "message" => "Process failed. Please try again later"));
                    }            
                }   
            } 
        }
    } else {
        http_response_code(200);
        echo json_encode(array("code" => 200, "message" => "Invalid verification code."));
    }

    $conn->close();
} else {
    http_response_code(200);
    echo json_encode(array("code" => 200, "message" => "Verification code not provided."));
}
?>
