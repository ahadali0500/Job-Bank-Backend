<?php
header("Access-Control-Allow-Origin: *"); // Change this to your allowed origin(s) in production
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include ('connection.php');

$result=array();
$id = $_POST['user_id'];

$stmt = $con->prepare("SELECT * FROM user_history where user_id = ?");
$stmt->bind_param("i",$id);

if($stmt->execute()){
   $stmt=$stmt->get_result();
   while($row = $stmt->fetch_assoc()){
      array_push($result, $row);
   }             
   http_response_code(200);
   echo json_encode($result);
}else{
    $output['message'] = "Unable to fetch packages";
    echo json_encode($output);
    http_response_code(400);
}
        

?>