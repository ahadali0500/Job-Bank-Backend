<?php
include('../connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$dd="SELECT * FROM `articles`";
$cc=mysqli_query($con, $dd);
$articles=mysqli_num_rows($cc);

$dd2="SELECT * FROM `packages`";
$cc2=mysqli_query($con, $dd2);
$packages=mysqli_num_rows($cc2);

$dd3="SELECT * FROM `tips_and_tricks`";
$cc3=mysqli_query($con, $dd3);
$tips_and_tricks=mysqli_num_rows($cc3);

$dd4="SELECT * FROM `users`";
$cc4=mysqli_query($con, $dd4);
$users=mysqli_num_rows($cc4);

    $output['packages'] = $packages;
    $output['articles'] = $articles;
    $output['tips_and_tricks'] = $tips_and_tricks;
    $output['users'] = $users; 
    echo json_encode($output);
    ?>
