<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$text = mysqli_real_escape_string($con, $_POST['text']);
$slug = mysqli_real_escape_string($con, $_POST['slug']);
$title = mysqli_real_escape_string($con, $_POST['title']);
$subscription = $_POST['subscription'];
$created_at = date('Y-m-d H:i:s');

$dd="SELECT * FROM `articles` WHERE `slug`='$slug'";
$cc=mysqli_query($con, $dd);
if(mysqli_num_rows($cc)>0){
    http_response_code(201);
    $output['code'] = 201;
    $output['message'] = "Slug is already used";
    echo json_encode($output);
}else {
    // Create the user_data directory if it doesn't exist
    $pImage = '';
    $baseDirectory = "../../images/articles/";
    if (!is_dir($baseDirectory)) {
        mkdir($baseDirectory, 0755, true);
    }
    if ($_FILES["image"]["name"]) {
        $imagefileinfo = pathinfo($_FILES["image"]["name"]);
        $pImage = $imagefileinfo['filename'] . "_" . time() . "." . $imagefileinfo['extension'];
        $imageDestinationPath = $baseDirectory . $pImage;
        move_uploaded_file($_FILES["image"]["tmp_name"], $imageDestinationPath);
        $output = array();
    }
    $dd="INSERT INTO `articles`(`title`, `slug`, `text`, `image`, `subscription`, `created_at`) VALUES ('$title','$slug','$text','$pImage','$subscription','$created_at')";
    $cc=mysqli_query($con, $dd);
    if($cc){
        http_response_code(200);
        $output['code'] = 200;
        $output['message'] = "Article created successfuly!";
        echo json_encode($output);

    }else {
        http_response_code(500);
        $output['code'] = 500;
        $output['message'] = "Failed!" . mysqli_error($con);
        echo json_encode($output);
    }
}
return $output;
?>
