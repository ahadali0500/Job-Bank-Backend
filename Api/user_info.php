<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
include('connection.php');

// Check if user_id is set in the POST request
if(isset($_POST['user_id'])){
    // Sanitize the user_id input to prevent SQL injection
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);

    // Query to fetch data based on user_id
    $query = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
    $result = mysqli_query($con, $query);

    if($result){
        $data = array();
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Query failed'));
    }
} else {
    echo json_encode(array('error' => 'user_id not provided'));
}
?>
