<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

// Getting user values
$user_id = mysqli_real_escape_string($con, $_POST['user_id']);
$article_id = mysqli_real_escape_string($con, $_POST['article_id']);

// Prepare the SQL statement
$sql = "INSERT INTO `save_articles`(`user_id`, `article_id`) VALUES ('$user_id', '$article_id')";

// Execute the query
$result = mysqli_query($con, $sql);

if (!$result) {
    $output['message'] = "Process failed. Please try again later.";
    $output['code'] = 400;
    $output['error'] = mysqli_error($con);
    echo json_encode($output);
    http_response_code(400);
} else {
    $affectedRows = mysqli_affected_rows($con);
    if ($affectedRows <= 0) {
        $output['message'] = "No rows affected. Please try again.";
        $output['code'] = 400;
        echo json_encode($output);
        http_response_code(400);
    } else {
        $output['message'] = "Article saved successfully";
        $output['code'] = 200;
        echo json_encode($output);
        http_response_code(200);
    }
}

?>
