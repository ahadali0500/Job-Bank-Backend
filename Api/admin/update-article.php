<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$text = mysqli_real_escape_string($con, $_POST['text']);
$slug = mysqli_real_escape_string($con, $_POST['slug']);
$title = mysqli_real_escape_string($con, $_POST['title']);
$id = $_POST["id"];
$image = $_POST["image"];
$subscription = $_POST['subscription'];


// Check if the record with the provided slug exists
$dd = "SELECT * FROM `articles` WHERE `slug` = '$slug' AND `id`!='$id' ";
$cc = mysqli_query($con, $dd);

if (!mysqli_num_rows($cc) > 0) {
    $pImage = "";
    $baseDirectory = "../../images/articles/";
    if (!is_dir($baseDirectory)) {
        mkdir($baseDirectory, 0755, true);
    }
    if ($_FILES["image"]["name"]) {
        $imagefileinfo = pathinfo($_FILES["image"]["name"]);
        $pImage =
            $imagefileinfo["filename"] .
            "_" .
            time() .
            "." .
            $imagefileinfo["extension"];
        $imageDestinationPath = $baseDirectory . $pImage;
        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $imageDestinationPath
        );
        $output = [];
    } else {
        $pImage = $_POST['profileImage'];
    }
    // Update the record if it exists
    $dd = "UPDATE `articles` SET `title` = '$title', `slug` = '$slug', `text` = '$text', `image` = '$pImage', `subscription` = '$subscription' WHERE `id` = '$id'";
    $cc = mysqli_query($con, $dd);

    if ($cc) {
        http_response_code(200);
        $output['code'] = 200;
        $output['message'] = "Articles updated successfully!";
        echo json_encode($output);
    } else {
        http_response_code(500);
        $output['code'] = 500;
        $output['message'] = "Failed to update Articles.";
        echo json_encode($output);
    }
} else {
    // Return a 404 if the record does not exist
    http_response_code(404);
    $output['code'] = 404;
    $output['message'] = "Articles not found.";
    echo json_encode($output);
}

return $output;
?>
