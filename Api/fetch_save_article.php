<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

$result = array();
$id = $_POST['user_id'] ?? ''; // Default to empty string if not set
$limit = $_POST['limit'] ?? 6;  // Default limit
$page = $_POST['page'] ?? 1;    // Default page number

// Sanitize the user input
$id = mysqli_real_escape_string($con, $id);
$limit = mysqli_real_escape_string($con, $limit);
$page = mysqli_real_escape_string($con, $page);

// Calculate the offset
$offset = ($page - 1) * $limit;

// Modified SQL Query with JOIN and pagination
$query = "SELECT s.*, a.* FROM save_articles s
          INNER JOIN articles a ON s.article_id = a.id
          WHERE s.user_id = '$id'
          LIMIT $limit OFFSET $offset";

$response = mysqli_query($con, $query);

if ($response) {
    while ($row = mysqli_fetch_assoc($response)) {
        array_push($result, $row);
    }
    // Count total articles for pagination purposes
    $countQuery = "SELECT COUNT(*) as total FROM save_articles s
                   INNER JOIN articles a ON s.article_id = a.id
                   WHERE s.user_id = '$id'";
    $countResult = mysqli_query($con, $countQuery);
    $total = mysqli_fetch_assoc($countResult)['total'];

    http_response_code(200);
    echo json_encode([
        "code" => 200,
        "data" => $result,
        "total" => $total,
        "page" => $page,
        "limit" => $limit
    ]);
} else {
    $output['message'] = "Unable to fetch Articles";
    echo json_encode($output);
    http_response_code(400);
}
?>
