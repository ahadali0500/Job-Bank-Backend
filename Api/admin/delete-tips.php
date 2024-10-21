<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('../connection.php');

$id = $_POST['id']; 

$sql = "DELETE FROM `tips_and_tricks` WHERE `id` = ?";

$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id); 
    $stmt->execute();

    $output = array();

    if ($stmt->affected_rows > 0) {
        $output['code'] = 200;
        $output['message'] = "Tips and Tricks deleted successfully";
    } else {
        $output['code'] = 500;
        $output['message'] = "Deletion failed: No rows affected";
    }

    $stmt->close();
} else {
    $output['code'] = 500;
    $output['message'] = "Prepare statement failed: " . $con->error;
}

echo json_encode($output);
?>
