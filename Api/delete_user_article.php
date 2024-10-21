<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

// Getting user values
$user_id = $_POST['user_id'];
$article_id = $_POST['article_id'];

// Simple SQL Query
// Note: this example does not safely handle user input
$sql = "DELETE FROM `save_articles` WHERE `user_id` = '$user_id' AND `article_id` = '$article_id'";
$result = mysqli_query($con, $sql);

if ($result) {
    if (mysqli_affected_rows($con) > 0) {
        $output['message'] = "Article unsaved Successfully";
        $output['code'] = 200;
        echo json_encode($output);
        http_response_code(200);
    } else {
        $output['message'] = "Process failed. No changes made.";
        $output['code'] = 400;
        echo json_encode($output);
        http_response_code(400);
    }
} else {
    $output['message'] = "Process failed. Please try again later.";
    $output['code'] = 400;
    $output['error'] = mysqli_error($con);
    echo json_encode($output);
    http_response_code(400);
}
?>
