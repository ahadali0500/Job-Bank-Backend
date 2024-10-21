<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include "connection.php";

$output = [];

// Function to add days to the current date
function addDaysToCurrentDate($days) {
    $date = new DateTime();
    $date->modify("+$days days");
    return $date->format('Y-m-d H:i:s');
}

try {
    // Getting user values and sanitize them
    $user_id = isset($_POST["user_id"]) ? mysqli_real_escape_string($con, $_POST["user_id"]) : null;
    $type = isset($_POST["type"]) ? mysqli_real_escape_string($con, $_POST["type"]) : null;
    $id = isset($_POST["id"]) ? mysqli_real_escape_string($con, $_POST["id"]) : null;

    if (!$user_id || !$type || !$id) {
        throw new Exception("Missing required parameters.");
    }

    if ($type == "Free") {
        $cvv = "SELECT * FROM `packages` WHERE `id`=1";
        $cvb = mysqli_query($con, $cvv);
        $bita = mysqli_fetch_assoc($cvb);

        if (!$bita) {
            throw new Exception("Package not found.");
        }

        $price = '';
        $transction_id = '';
        $days = $bita["days"];
        $quiz_attempts = $bita["quiz_attempts"];
        $quiz_questions = $bita["quiz_questions"];
        $interview_attempts = $bita["interview_attempts"];
        $interview_questions = $bita["interview_questions"];
        $cv_templates = $bita["cv_template"];
        $active = 1;
        $start_date = date('Y-m-d H:i:s');
        $expire_date = addDaysToCurrentDate($bita["days"]);
        $package_id = $bita["id"];
        $package_name = $bita["name"];

        // Update user_packages table
        $sql_1 = "UPDATE `user_packages` SET 
            `package_id` = '$package_id', 
            `package_name` = '$package_name', 
            `days` = '$days', 
            `quiz_attempts` = '$quiz_attempts', 
            `quiz_questions` = '$quiz_questions', 
            `interview_attempts` = '$interview_attempts', 
            `interview_questions` = '$interview_questions', 
            `cv_templates` = '$cv_templates', 
            `active` = '$active', 
            `start_date` = '$start_date', 
            `expire_date` = '$expire_date' 
            WHERE `id` = '$id'";

        if (!mysqli_query($con, $sql_1)) {
            throw new Exception("Failed to update user_packages: " . mysqli_error($con));
        }

        if (mysqli_affected_rows($con) <= 0) {
            throw new Exception("No changes in the data or failed to update user_packages: " . mysqli_error($con));
        }

        $unactive = 0;
        $sql_2 = "UPDATE `user_history` SET `active` = '$unactive' WHERE `user_id` = '$user_id'";
        if (!mysqli_query($con, $sql_2)) {
            throw new Exception("Update query for user_history failed: " . mysqli_error($con));
        }

        // if (mysqli_affected_rows($con) <= 0) {
        //     throw new Exception("Failed to update user_history: No rows affected.");
        // }

        // Insert new record into user_history table
        $sql_3 = "INSERT INTO `user_history`(`user_id`, `transction_id`, `price`, `package_id`, `user_package_id`, `package_name`, `days`, `quiz_attempts`, `quiz_questions`, `interview_attempts`, `interview_questions`, `cv_templates`, `active`, `start_date`, `expire_date`) 
                  VALUES ('$user_id', '$transction_id', '$price', '$package_id', '$id', '$package_name', '$days', '$quiz_attempts', '$quiz_questions', '$interview_attempts', '$interview_questions', '$cv_templates', '$active', '$start_date', '$expire_date')";

        if (!mysqli_query($con, $sql_3)) {
            throw new Exception("Failed to insert into user_history: " . mysqli_error($con));
        }

        $output["code"] = 200;
        $output["message"] = "Updated successfully";
    } elseif ($type == "Purchase") {
        $cbvc = "UPDATE `user_packages` SET `active`=0 WHERE `user_id`='$user_id'";
        if (!mysqli_query($con, $cbvc)) {
            throw new Exception("Failed to update user_packages for purchase: " . mysqli_error($con));
        }

        $cbvc = "UPDATE `user_history` SET `active`=0 WHERE `user_id`='$user_id'";
        if (!mysqli_query($con, $cbvc)) {
            throw new Exception("Failed to update user_history for purchase: " . mysqli_error($con));
        }

        $output["code"] = 200;
        $output["message"] = "Updated successfully";
    } else {
        throw new Exception("Invalid request type.");
    }
} catch (Exception $e) {
    $output["code"] = 500;
    $output["message"] = $e->getMessage();
}

echo json_encode($output);
?>
