<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

// Initialize result array
$result = [];

// Check if 'slug' is provided in the query string
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    http_response_code(400); // Bad request
    echo json_encode(['message' => 'Slug parameter is required']);
    exit;
}

// Escaping the input for security (simple way)
$slug = mysqli_real_escape_string($con, $_GET['slug']);

// Construct SQL query
$sql = "SELECT * FROM articles WHERE `slug` = '$slug'";

$queryResult = $con->query($sql);

// Check if the query was successful
if ($queryResult) {
    if ($queryResult->num_rows > 0) {
        $row = $queryResult->fetch_assoc();
        http_response_code(200);
        echo json_encode($row);
    } else {
        http_response_code(404); // Not found
        echo json_encode(['message' => 'No article found for the given slug']);
    }
} else {
    http_response_code(500); // Internal server error
    echo json_encode(['message' => 'Error executing query']);
}

$con->close();
?>
