<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('connection.php');

if (!$con) {
    echo json_encode(['message' => 'Database connection error']);
    http_response_code(500);
    exit;
}

$stmt = $con->prepare("SELECT * FROM resume_templates WHERE status = ?");
if (!$stmt) {
    echo json_encode(['message' => 'Failed to prepare statement: ' . htmlspecialchars($con->error)]);
    http_response_code(500);
    exit;
}

$status = 1;
$stmt->bind_param("i", $status);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    http_response_code(200);
    echo json_encode($data);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to execute query: ' . htmlspecialchars($stmt->error)]);
}
?>
