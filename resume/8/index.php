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
    if ($data["image"] == "") {
        $vv =
            "https://www.desired-techs.com/job-bank/resume/thumbnails/blank.jpg";
    } else {
        $vv =
            "https://www.desired-techs.com/job-bank/user_data/user_" .
            $data["user_id"] .
            "/images/" .
            $data["image"];
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
    <title>Resume</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img id="download_Btn" style="width:50px" class="download" src="https://desired-techs.com/job-bank/resume/thumbnails/download.png" >
    <div id="content" class="container">
        <div class="left-column">
            <div class="profile-picture">
                <img src="<?php echo "data:image/jpeg;base64," .
                    imageToBase64($data); ?>" alt="Profile Image">
            </div>
            <div class="contact-info">
                <p><b>Email: </b><?php echo $data["email"]; ?></p>
                <p><b>Phone: </b><?php echo $data["phoneNumber"]; ?></p>
                <p><b>Location: </b><?php echo $data["street"] .
                    ", " .
                    $data["address"] .
                    ", " .
                    $data["country"]; ?></p>
            </div>
            <div class="skills">
                <h3 class="section-title">SKILLS</h3>
                <ul>
                     <?php foreach ($skills as $skill) { ?>
                        <li><?php echo $skill["skill"]; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="languages">
                <h3 class="section-title">LANGUAGES</h3>
                <ul>
                   <?php foreach ($languages as $lg) { ?>
                        <li><?php echo $lg["name"]; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="interests">
                <h3 class="section-title">EDUCATION</h3>
                <ul>
                     <?php foreach ($education as $edu) { ?>
                        <li><?php echo $edu["schoolName"]; ?> <?php echo $edu[
     "schoolLevel"
 ]; ?><br></li>
                   <?php } ?>
                </ul>
            </div>
        </div>
        <div class="right-column">
             <h1 class="title"><?php echo $data["fullName"]; ?></h1>
            <h3 class="pos"><?php echo $data["currentPosition"]; ?></h3>
            <p><?php echo $data["bio"]; ?></p>
            <div class="work-experience">
                <h3 class="section-title">WORK EXPERIENCE</h3>
               <?php foreach ($experienceData as $experience) { ?>

                <div class="job">
                    <h4><?php echo $experience["experienceTitle"]; ?>   - <?php echo $experience["experiencePlace"]; ?> </h4>
                    <?php echo $experience["experiencePeriod"]; ?></p>

                    <p><?php echo $experience["experienceLocation"]; ?></p>
                    <ul>
                        <li><?php echo $experience["experienceDescription"]; ?></li>
                    </ul>
                </div>
                                <?php } ?>


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
                    html2canvas: { scale: 1.5 },
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
