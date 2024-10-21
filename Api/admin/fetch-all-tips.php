<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$dd="SELECT * FROM `tips_and_tricks`";
$cc=mysqli_query($con, $dd);
if(mysqli_num_rows($cc)>0){
      $tips_and_tricks = [];
      while ($row = mysqli_fetch_assoc($cc)) {
         $tips_and_tricks[] = $row;
      }
      $output['code'] = 200;
      $output['data'] = $tips_and_tricks; 
    echo json_encode($output);
}else {
    $output['code'] = 404;
    $output['data'] = ""; 
    echo json_encode($output);
}
return $output;
?>
