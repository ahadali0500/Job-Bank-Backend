<?php
include('connection.php');

// Getting user values
$title = $_POST['title'];
$text = $_POST['text'];
$image = $_POST['image'];
$createdAt = date('Y-m-d H:i:s');

$pImage = '';
$baseDirectory = "../images/articles/";
if (!is_dir($baseDirectory)) {
    mkdir($baseDirectory, 0755, true);
}
if($_FILES["image"]["name"]){
    $imagefileinfo = pathinfo($_FILES["image"]["name"]);
    $pImage = $imagefileinfo['filename'] . "_" . time() . "." . $imagefileinfo['extension'];
    $imageDestinationPath = $baseDirectory . $pImage;
    move_uploaded_file($_FILES["image"]["tmp_name"], $imageDestinationPath);
    $output = array();  
}

$conn = $con->prepare('INSERT INTO `articles`(`title`, `text`, `image`, `created_at`) VALUES (?, ?, ?, ?)');   
    $conn->bind_param("ssss", $title, $text, $pImage ,$createdAt);
    $conn->execute();
   if ($conn->affected_rows <= 0) {        
	    $output['message'] = "Process failed. Please try again later.";
        $output['code'] = 400;
        $output['error'] = $conn->error;
        echo json_encode($output);
		http_response_code(400); 
    } else {
        $output['message'] = "Article created Successfully";
        $output['code'] = 200;
        echo json_encode($output);
		http_response_code(200);
    }

?>