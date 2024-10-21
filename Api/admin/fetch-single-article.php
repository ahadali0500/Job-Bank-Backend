<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$id=$_POST['id'];
$dd="SELECT * FROM `articles` WHERE `id`='$id' ";
$cc=mysqli_query($con, $dd);
if(mysqli_num_rows($cc)>0){
      $row = mysqli_fetch_assoc($cc);
      $output['code'] = 200;
      $output['data'] = $row; 
    echo json_encode($output);
}else {
    $output['code'] = 404;
    $output['data'] = ""; 
    echo json_encode($output);
}
return $output;
?>
