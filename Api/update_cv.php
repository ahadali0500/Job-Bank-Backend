<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');

$id = $_POST['id'];
$user_id = $_POST['user_id'];
$fullName = $_POST['fullName'];
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
$createdAt = $_POST['created_At'];

$pImage = '';
$bImage = '';
$baseDirectory = "../user_data/user_$user_id/images/";
if (!is_dir($baseDirectory)) {
    mkdir($baseDirectory, 0755, true);
}
if($_FILES["image"]["name"]){
    $imagefileinfo = pathinfo($_FILES["image"]["name"]);
    $pImage = $imagefileinfo['filename'] . "_" . time() . "." . $imagefileinfo['extension'];
    $imageDestinationPath = $baseDirectory . $pImage;
    move_uploaded_file($_FILES["image"]["tmp_name"], $imageDestinationPath);
    $output = array();  
}else{
   $pImage = $_POST['profile_image'];
}
if($_FILES["backgroundImage"]["name"]){
    $backimagefileinfo = pathinfo($_FILES["backgroundImage"]["name"]);
    $bImage = $backimagefileinfo['filename'] . "_" . time() . "." . $backimagefileinfo['extension'];
    $backImageDestinationPath = $baseDirectory . $bImage;
    move_uploaded_file($_FILES["backgroundImage"]["tmp_name"], $backImageDestinationPath);
    $output = array();  
}else{
   $bImage = $_POST['background_image'];
}

$sql_1 = "UPDATE `cv_detail` SET 
    `fullName`=?,
    `email`=?,
    `currentPosition`=?,
    `street`=?,
    `address`=?,
    `country`=?,
    `phoneNumber`=?,
    `bio`=?,
    `education`=?,
    `experienceData`=?,
    `skills`=?,
    `languages`=?,
    `image`=?,
    `background_image`=?,
    `cv_temp`=?,
    `created_At`=?
    WHERE `id`=?";

$stmt = $con->prepare($sql_1);

if ($stmt) {
    $stmt->bind_param("ssssssssssssssssi", $fullName, $email, $currentPosition, $street, $address, $country, $phoneNumber, $bio, $educationDetails, $experience, $skills, $languages, $pImage, $bImage, $cv_temp, $createdAt, $id);
    $stmt->execute();

    $output = array();

    if ($stmt->affected_rows > 0) {
        $output['code'] = 200;
        $output['message'] = "Updated successfully";
        $output['id'] = $id;
        $output['image'] = $pImage;
        $output['backImage'] = $bImage;
    } else {
        $output['code'] = 400;
        $output['error'] = $con->error;
        $output['message'] =  "No changes in the data";
    }

    $stmt->close();
} else {
    $output['code'] = 400;
    $output['message'] = "Prepare statement failed: " . $con->error;
}

echo json_encode($output);


?>