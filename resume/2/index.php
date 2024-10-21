<?php
include "../connection.php";
$id = $_GET["id"];
$sql = "SELECT * FROM `cv_detail` WHERE `id`='$id'";
$dd = mysqli_query($con, $sql);
$data = mysqli_fetch_assoc($dd);

$education = json_decode($data["education"], true);
$experienceData = json_decode($data["experienceData"], true);
$skills = json_decode($data["skills"], true);
$languages = json_decode($data["languages"], true);

function imageToBase64($data)
{
    if($data["image"]==""){
       $vv = "https://www.desired-techs.com/job-bank/resume/thumbnails/blank.jpg";
    }else{
        $vv ="https://www.desired-techs.com/job-bank/user_data/user_" .$data["user_id"] ."/images/" .$data["image"];
    }
    $imageData = file_get_contents($vv);
    $base64Image = base64_encode($imageData);
    return $base64Image;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Resume</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <img id="download_Btn" style="width:50px" class="download" src="../thumbnails/download.png">
    <div id="content" class="container">
        <div class="main">
            <div class="left">
                    <div class="header">
                <div class="headImg">
                <img src="<?php echo "data:image/jpeg;base64," . imageToBase64($data); ?>" alt="Profile Image">
                </div></div>
                <section class="about">
                    <h3>About Me</h3>
                    <p><?php echo $data["bio"]; ?></p>
                </section>
                <section class="contact">
                    <h3>General</h3>
                    <p><i class="fa-solid fa-envelope"></i> <?php echo $data["email"]; ?></p>
                    <p><i class="fa-solid fa-phone-volume"></i> <?php echo $data["phoneNumber"]; ?></p>
                    <p><i class="fa-solid fa-house"></i> <?php echo $data["street"]; ?> <?php echo $data["address"]; ?> <?php echo $data["country"]; ?></p>
                </section>
                <section class="language">
                    <h3>Language</h3>
                    <?php foreach ($languages as $edu) { ?>
                        <p><?php echo $edu["name"]; ?></p>
                    <?php } ?>
                </section>
                 <section class="skills">
                    <h3>Skills Summary</h3>
                    <?php foreach ($skills as $edu) { ?>
                        <p><?php echo $edu["skill"]; ?></p>
                    <?php } ?>
                </section>
            </div>
            <div class="right">
                    <div class="header">

                <div class="header-text">
                    <h1><?php echo $data["fullName"]; ?></h1>
                    <h2><?php echo $data["currentPosition"]; ?></h2>
                </div>
                </div>
                <section class="experience">
                    <h3>Experience</h3>
                    <?php foreach ($experienceData as $edu) { ?>
                        <h4><?php echo $edu["experienceTitle"]; ?> - <?php echo $edu["experiencePlace"]; ?></h4>
                        <p><?php echo $edu["experiencePeriod"]; ?></p>
                        <p><?php echo $edu["experienceLocation"]; ?></p>
                        <br>
                        <p><?php echo $edu["experienceDescription"]; ?></p>
                        <br><br>
                    <?php } ?>     
                </section>
                <section class="education">
                    <h3>Education</h3>
                    <?php foreach ($education as $edu) { ?>
                        <p style="margin-bottom:5px" ><h4><?php echo $edu["schoolName"]; ?></h4> <?php echo $edu["schoolLevel"]; ?></p>

                    <?php } ?>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        const downloadButton = document.getElementById('download_Btn');
        const content = document.getElementById('content');

        downloadButton.addEventListener('click', async function () {
            const filename = 'resume.pdf';
            try {
                const opt = {
                    margin: 0,
                    filename: filename,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                await html2pdf().set(opt).from(content).save();
            } catch (error) {
                console.error('Error:', error.message);
            }
        });
    </script>
</body>
</html>
