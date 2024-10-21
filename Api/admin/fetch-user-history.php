<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$id=$_POST['id'];
$dd="SELECT * FROM `user_history` WHERE `user_id`='$id' ";
$cc=mysqli_query($con, $dd);
if(mysqli_num_rows($cc)>0){
       $dt=[];
      while($row = mysqli_fetch_assoc($cc)){
        $dt[]=$row; 
      }
      $output['code'] = 200;
      $output['data'] = $dt; 
    echo json_encode($output);
}else {
    $output['code'] = 404;
    $output['data'] = ""; 
    echo json_encode($output);
}
return $output;
?>
