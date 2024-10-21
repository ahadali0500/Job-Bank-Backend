<?php
include "../connection.php";
$sql = "SELECT * FROM `cv_detail` WHERE `id`='221'";
$dd = mysqli_query($con, $sql);
$data = mysqli_fetch_assoc($dd);

$education = json_decode($data["education"], true);
$experienceData = json_decode($data["experienceData"], true);
$skills = json_decode($data["skills"], true);
$languages = json_decode($data["languages"], true);

function imageToBase64($data) {
    $vv = "https://www.desired-techs.com/job-bank/user_data/user_" . $data['user_id'] . "/images/" . $data['image'];
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
    <link rel="stylesheet" href="resume.css">
    <title>Resume</title>
</head>
<body>
    <main>
        <div id="content" class="container">
            <div class="left">
                <div class="profile-img">
                    <img src="<?php echo 'data:image/jpeg;base64,' . imageToBase64($data); ?>" alt="Profile Image">
                </div>
                
                <div class="contact-info">
                    <h3>CONTACT</h3>
                    <ul>
                        <li<?php echo $data["phoneNumber"]; ?></li>
                        <li><?php echo $data["email"]; ?></li>
                        <li><?php echo $data["street"] . ', ' . $data["address"] . ', ' . $data["country"]; ?></li>
                    </ul>

                    <h3>EDUCATION</h3>
                    <?php foreach ($education as $edu) { ?>
                    <p><?php echo $edu['schoolName'] ?> <br> <?php echo $edu['schoolLevel'] ?></p>
                    <?php } ?>
                   
                    <h3>SKILLS</h3>

                    <ul class="ul">
                                <?php foreach ($skills as $edu) { ?>
                        <li class="li li2"><?php echo $edu['skill'] ?></li>
                                <?php } ?>
                    </ul>

                    <h3>LANGUAGES</h3>
                    <ul class="ul">
                            <?php foreach ($languages as $edu) { ?>
                        <li class="li"><?php echo $edu['name'] ?></li>
                            <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="right">
                <div class="profile-title">
                    <h1><span><?php echo $data["fullName"]; ?></span></h1>
                    <p><?php echo $data["currentPosition"]; ?></p>
                    <div class="line"></div>
                </div>

                <div class="profile-content">
                    <h3>PROFILE</h3>
                    <p><?php echo $data["bio"]; ?></p>

                    <h3>Work Experience</h3>
                     <?php foreach ($experienceData as $edu) { ?>
                     <div class="heading">
                        <p style="float: left; font-weight: 600;"><?php echo $edu["experienceTitle"]; ?></p>
                        <p style="float: right;"><?php echo $edu["experiencePlace"]; ?></p>
                        <br>
                        <p><?php echo $edu["experiencePeriod"]; ?></p>
                        <p><?php echo $edu["experienceLocation"]; ?></p>
                        <div>
                            <ul>
                                <li><?php echo $edu["experienceDescription"]; ?></li>
                            </ul>
                        </div>
                    </div>
             <?php } ?>     
                   
                </div>
            </div>
        </div>
    </main>

    <button id="download_Btn">
        Download Table Data
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        const download_button = document.getElementById('download_Btn');
        const content = document.getElementById('content');

        download_button.addEventListener('click', async function () {
            const filename = 'resume.pdf';

            try {
                const opt = {
                    margin: 1,
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
