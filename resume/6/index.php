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
    <title>Resume</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <img id="download_Btn" style="width:50px" class="download" src="../thumbnails/download.png" >
    <section id="content" class="main-section">
       <div class="left-part">
            <div class="photo-container">
                <img src="<?php echo "data:image/jpeg;base64," . imageToBase64($data); ?>" alt="Profile Image">
            </div>
            <div class="contact-container">
                <h2 class="title">CONTACT</h2>
                <div class="contact-list">
                    <i class="bi bi-telephone-fill"></i>
                    <p><?php echo $data["phoneNumber"]; ?></p>
                </div>
                <div class="contact-list">
                    <i class="bi bi-envelope"></i>
                    <p><?php echo $data["email"]; ?></p>
                </div>
                <div class="contact-list">
                    <i class="bi bi-geo-alt"></i>
                    <p><?php echo $data["street"] . ", " . $data["address"] . ", " . $data["country"]; ?></p>
                </div>
            </div>
            <div class="education-container">
                <h2 class="title">EDUCATION</h2>
                <?php foreach ($education as $edu) { ?>
                    <div class="course">
                        <h3 class="education-title"><?php echo $edu["schoolName"]; ?></h3>
                        <p class="college-name"><?php echo $edu["schoolLevel"]; ?></p>
                    </div>
                <?php } ?>
            </div>
            <div class="skills-container">
                <h2 class="title">Languages</h2>
                <div class="skill">
                    <?php foreach ($languages as $lg) { ?>
                        <p><?php echo $lg["name"]; ?></p>
                    <?php } ?>
                </div>
            </div>
             <div class="skills-container">
                <h2 class="title">Skills</h2>
                <div class="skill">
                    <?php foreach ($skills as $skill) { ?>
                        <p><?php echo $skill["skill"]; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
      <div class="right-part">
            <div class="banner">
                <h1 class="firstname"><?php echo $data["fullName"]; ?></h1>
                <p class="position"><?php echo $data["currentPosition"]; ?></p><br>
                <h2 class="title text-left">PROFILE</h2>
                <p style="color:black" class="work-text"><?php echo $data["bio"]; ?></p>
            </div>
            <div class="work-container">
                <h2 class="title text-left">WORK EXPERIENCE</h2>
                <?php foreach ($experienceData as $experience) { ?>
                    <div class="work">
                        <div class="job-date">
                            <p><b><?php echo $experience["experienceTitle"]; ?></b></p>
                            <p><b><?php echo $experience["experiencePlace"]; ?></b></p>
                        </div>
                        <p style="font-size: 14px;" class="date"><?php echo $experience["experienceLocation"]; ?></p>
                        <p style="font-size: 14px;" class="date"><?php echo $experience["experiencePeriod"]; ?></p>
                        <p style="margin-top:8px" class="work-text"><?php echo $experience["experienceDescription"]; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
      const download_button = document.getElementById("download_Btn");
      const content = document.getElementById("content");

      download_button.addEventListener("click", async function () {
        const filename = "resume.pdf";

        try {
          const opt = {
            margin: [0, 0], // Set both top and bottom margins to zero
            filename: filename,
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
          };
          await html2pdf().set(opt).from(content).save();
        } catch (error) {
          console.error("Error:", error.message);
        }
      });
    </script>
</body>

</html>
