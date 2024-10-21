<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

// Getting user values
$fullName = $_POST['fullName'];
$user_id = $_POST['user_id'];
$folder = $_POST['folder'];
$currentPosition = $_POST['currentPosition'];
$street = $_POST['street'];
$address = $_POST['address'];
$country = $_POST['country'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$bio = $_POST['bio'];
$experience = $_POST['experience'];
$educationDetails = $_POST['educationDetails'];
$skills = $_POST['skills'];
$languages = $_POST['languages'];
$cv_temp = $_POST['cv_temp'];
$createdAt = date('Y-m-d H:i:s');
$pImage = '';
$bImage = '';
$baseDirectory = "../user_data/user_$user_id/images/";

if (!is_dir($baseDirectory)) {
    mkdir($baseDirectory, 0755, true);
}
if ($_FILES["image"]["error"] != 0) {
    die("Error in file upload: " . $_FILES["image"]["error"]);
}
$im="";
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    // Extract file info
    $imageFileInfo = pathinfo($_FILES["image"]["name"]);
    $filename = $imageFileInfo['filename'];
    $extension = $imageFileInfo['extension'];

    // Create a unique file name to prevent overwriting
    $pImage = $filename . "_" . time() . "." . $extension;
    $imageDestinationPath = $baseDirectory . $pImage;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageDestinationPath)) {
        $im = "File uploaded successfully to {$imageDestinationPath}";
    } else {
        $im = "Failed to move the uploaded file.";
    }
}
if($_FILES["backgroundImage"]["name"]){
    $backimagefileinfo = pathinfo($_FILES["backgroundImage"]["name"]);
    $bImage = $backimagefileinfo['filename'] . "_" . time() . "." . $backimagefileinfo['extension'];
    $backImageDestinationPath = $baseDirectory . $bImage;
    move_uploaded_file($_FILES["backgroundImage"]["tmp_name"], $backImageDestinationPath);
    $output = array();  
}
$conn = $con->prepare('INSERT INTO `cv_detail`(`user_id`, `fullName`, `email`, `currentPosition`, `street`, `address`, `country`, `phoneNumber`, `bio`, `education`, `experienceData`, `skills`, `languages`, `image`, `background_image`, `cv_temp`, created_At) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');   
    $conn->bind_param("sssssssssssssssss", $user_id,$fullName,$email,$currentPosition,$street,$address,$country,$phoneNumber,$bio,$educationDetails,$experience,$skills,$languages,$pImage,$bImage,$cv_temp,$createdAt);
    $conn->execute();
   if ($conn->affected_rows <= 0) {        
	    $output['message'] = "Process failed. Please try again later.";
        $output['code'] = 400;
        $output['error'] = $conn->error;
        // $output['data'] = [$user_id,$fullName,$email,$currentPosition,$street,$address,$country,$phoneNumber,$bio,$educationDetails,$experience,$skills,$languages,$pImage,$bImage,$folder,$createdAt];
        echo json_encode($output);
		http_response_code(400); 
    } else {
        $output['message'] = "Resume created Successfully";
        $output['code'] = 200;
        $output['im'] = $im;
        $output['id'] =  $con->insert_id;
        $output['image'] = $pImage;
        $output['backImage'] = $bImage;
        echo json_encode($output);
		http_response_code(200);
    }

?>