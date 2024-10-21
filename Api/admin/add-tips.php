<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$title = $_POST['title'];
$slug = $_POST['slug'];
$color = $_POST['color'];
$tips_and_tricks = $_POST['tips_and_tricks'];

$dd="SELECT * FROM `tips_and_tricks` WHERE `slug`='$slug'";
$cc=mysqli_query($con, $dd);
if(mysqli_num_rows($cc)>0){
    http_response_code(201);
    $output['code'] = 201;
    $output['message'] = "Slug is already used";
    echo json_encode($output);
}else {
    $pIcon = '';
    $baseDirectory = "../../images/tips_and_tricks/";
    if (!is_dir($baseDirectory)) {
        mkdir($baseDirectory, 0755, true);
    }
    if ($_FILES["icon"]["name"]) {
        $imagefileinfo = pathinfo($_FILES["icon"]["name"]);
        $pIcon = $imagefileinfo['filename'] . "_" . time() . "." . $imagefileinfo['extension'];
        $imageDestinationPath = $baseDirectory . $pIcon;
        move_uploaded_file($_FILES["icon"]["tmp_name"], $imageDestinationPath);
        $output = array();
    }

    $dd="INSERT INTO `tips_and_tricks`(`title`, `slug`, `tips_and_tricks`, `color`, `icon`) VALUES ('$title','$slug','$tips_and_tricks','$color','$pIcon')";
    $cc=mysqli_query($con, $dd);
    if($cc){
        http_response_code(200);
        $output['code'] = 200;
        $output['message'] = "Tips and Tricks created successfuly!";
        echo json_encode($output);

    }else {
        http_response_code(500);
        $output['code'] = 500;
        echo json_encode($output);
    }
}
return $output;
?>
