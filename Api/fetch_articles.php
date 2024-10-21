<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include('connection.php');

$limit = 6;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM articles LIMIT $limit OFFSET $offset";
$queryResult = $con->query($sql);
$result = array();

if ($queryResult) {
    while ($row = $queryResult->fetch_assoc()) {
        if($_POST['user_id']!="null"){
            $article_id = $row['id'];
            $ccx = "SELECT * FROM save_articles WHERE `user_id`='$user_id' AND `article_id`='$article_id'";
            $dx = mysqli_query($con, $ccx);
            $row['save'] = mysqli_num_rows($dx) > 0 ? 1 : 0;
        }else{
            $row['save'] = 0;

        }
       
        $result[] = $row;
    }
    http_response_code(200);
    echo json_encode(array("code" => 200, "data" => $result, "total" => mysqli_num_rows($con->query("SELECT * FROM articles"))));
} else {
    echo json_encode(array("code" => 400, "message" => "Unable to fetch articles", "data" => null));
    http_response_code(400);
}
?>
