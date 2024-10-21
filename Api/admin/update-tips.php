<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
$id = $_POST['id'];
$title = $_POST['title'];
$slug = $_POST['slug'];
$icon = $_POST['icon'];
$color = $_POST['color'];
$tips_and_tricks = $_POST['tips_and_tricks'];
// Check if the record with the provided slug exists
$dd = "SELECT * FROM `tips_and_tricks` WHERE `slug` = '$slug' AND `id`!='$id'";
$cc = mysqli_query($con, $dd);
if (!mysqli_num_rows($cc) > 0) {
    $pIcon = "";
    $baseDirectory = "../../images/tips_and_tricks/";
    if (!is_dir($baseDirectory)) {
        mkdir($baseDirectory, 0755, true);
    }
    if ($_FILES["icon"]["name"]) {
        $imagefileinfo = pathinfo($_FILES["icon"]["name"]);
        $pIcon =
            $imagefileinfo["filename"] .
            "_" .
            time() .
            "." .
            $imagefileinfo["extension"];
        $imageDestinationPath = $baseDirectory . $pIcon;
        move_uploaded_file(
            $_FILES["icon"]["tmp_name"],
            $imageDestinationPath
        );
        $output = [];
    } else {
        $pIcon = $_POST['currentIcon'];
    }


    // Update the record if it exists
    $dd = "UPDATE `tips_and_tricks` SET `title` = '$title', `tips_and_tricks` = '$tips_and_tricks', `slug` = '$slug', `color` = '$color', `icon` = '$pIcon' WHERE `id` = '$id'";
    $cc = mysqli_query($con, $dd);
    if ($cc) {
        http_response_code(200);
        $output['code'] = 200;
        $output['message'] = "Tips and Tricks updated successfully!";
        echo json_encode($output);
    } else {
        http_response_code(500);
        $output['code'] = 500;
        $output['message'] = "Failed to update Tips and Tricks.";
        echo json_encode($output);
    }
} else {
    // Return a 404 if the record does not exist
    http_response_code(404);
    $output['code'] = 404;
    $output['message'] = "Tips and Tricks not found.";
    echo json_encode($output);
}
return $output;
?>
