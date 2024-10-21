<?php
header("Access-Control-Allow-Origin: *"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

$output = array();

try {
    // Getting user values
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $price = $_POST['price'];
    $transction_id = $_POST['transction_id'];
    $days = $_POST['days'];
    $quiz_attempts = $_POST['quiz_attempts'];
    $quiz_questions = $_POST['quiz_questions'];
    $interview_attempts = $_POST['interview_attempts'];
    $interview_questions = $_POST['interview_questions'];
    $cv_templates = $_POST['cv_templates'];
    $active = $_POST['active'];
    $start_date = $_POST['start_date'];
    $expire_date = $_POST['expire_date'];
    $package_id = $_POST['package_id'];
    $package_name = $_POST['package_name'];

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

     $unactive=0;
    $sql_2 = "UPDATE `user_history` SET `active` = '$unactive' WHERE `user_id` = '$user_id'";
    if (!mysqli_query($con, $sql_2)) {
        throw new Exception("Update query for user_history failed: " . mysqli_error($con));
    }

    if (mysqli_affected_rows($con) <= 0) {
        throw new Exception("Failed to update user_history: " . mysqli_error($con));
    }

    // Insert new record into user_history table
    $sql_3 = "INSERT INTO `user_history`(`user_id`, `transction_id`, `price`, `package_id`, `user_package_id`, `package_name`, `days`, `quiz_attempts`, `quiz_questions`, `interview_attempts`, `interview_questions`, `cv_templates`, `active`, `start_date`, `expire_date`) 
              VALUES ('$user_id', '$transction_id', '$price', '$package_id', '$id', '$package_name', '$days', '$quiz_attempts', '$quiz_questions', '$interview_attempts', '$interview_questions', '$cv_templates', '$active', '$start_date', '$expire_date')";
    
    if (!mysqli_query($con, $sql_3)) {
        throw new Exception("Failed to insert into user_history: " . mysqli_error($con));
    }

    $output['code'] = 200;
    $output['message'] = "Updated successfully";

} catch (Exception $e) {
    error_log($e->getMessage());
    $output['code'] = 500;
    $output['message'] = $e->getMessage();
}

mysqli_close($con);
echo json_encode($output);
?>
