<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once "check_session.php";
include "connection.php";

        $name = $_POST["name"];
        $phone = $_POST["phone"];
        // $email = $_POST["email"];
        $profileImage = $_POST["profile_image"];
        $dateOfBirth = $_POST["date_of_birth"];
        $address = $_POST["address"];
        $id = $_POST["user_id"];

        $pImage = "";
        $baseDirectory = "../user_data/user_$id/images/";
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
            $pImage = $profileImage;
        }

        $sql_1 = "UPDATE `users` SET 
        `name`=?,
        `profile_image`=?,
        `phone`=?,
        `date_of_birth`=?,
        `address`=?
        WHERE `user_id`=?";

        $stmt = $con->prepare($sql_1);

        if ($stmt) {
            $stmt->bind_param(
                "sssssi",
                $name,
                $pImage,
                $phone,
                $dateOfBirth,
                $address,
                $id
            );
            $stmt->execute();

            $output = [];

            if ($stmt->affected_rows > 0) {
                $output["code"] = 200;
                $output["message"] = "Data updated successfully";
                $output["profile_image"] = $pImage;
            } else {
                $output["code"] = 400;
                if ($_FILES["image"]["name"]) {
                    $output["in"] = [
                        $name,
                        $pImage,
                        $phone,
                        $dateOfBirth,
                        $address,
                        $id,
                    ];
                } else {
                    $output["in"] = "imageNotfound";
                }
                $output["error"] = $conn->error;
                $output["message"] = "No changes in the data";
            }

            $stmt->close();
        } else {
            $output["code"] = 400;
            $output["message"] = "Prepare statement failed: " . $con->error;
        }


echo json_encode($output);

?>
