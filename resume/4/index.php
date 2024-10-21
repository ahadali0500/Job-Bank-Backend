<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resume</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
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
        if($data["image"] == ""){
           $vv = "https://www.desired-techs.com/job-bank/resume/thumbnails/blank.jpg";
        } else {
            $vv ="https://www.desired-techs.com/job-bank/user_data/user_" .$data["user_id"] ."/images/" .$data["image"];
        }    
        $imageData = file_get_contents($vv);
        $base64Image = base64_encode($imageData);
        return $base64Image;
    }
    ?>
    <img id="download_Btn" style="width:50px" class="download" src="../thumbnails/download.png" >
    <div id="content" class="container">
      <div class="left-column">
        <div class="photo">
          <img src="<?php echo "data:image/jpeg;base64," . imageToBase64($data); ?>" alt="Profile Image" />
        </div>
        <div class="section">
          <h3>Contact</h3>
          <p><?php echo $data["email"]; ?></p>
          <p><?php echo $data["street"]; ?></p>
          <p><?php echo $data["address"]; ?></p>
        </div>
        <div class="section">
          <h3>Education</h3>
          <?php foreach ($education as $edu) { ?>
            <p><?php echo $edu["schoolName"]; ?><br /><?php echo $edu["schoolLevel"]; ?></p>
          <?php } ?>
        </div>
        <div class="section">
          <h3>Skills</h3>
          <?php foreach ($skills as $edu) { ?>
            <p><?php echo $edu["skill"]; ?></p>
          <?php } ?>
        </div>
        <div class="section">
          <h3>Languages</h3>
          <?php foreach ($languages as $edu) { ?>
            <p><?php echo $edu["name"]; ?></p>
          <?php } ?>
        </div>
      </div>
      <div class="right-column">
        <div class="header">
          <h1><?php echo $data["fullName"]; ?></h1>
          <p><?php echo $data["currentPosition"]; ?></p>
        </div>
        <div class="section">
          <h3>PROFILE</h3>
          <div class="job">
            <p><?php echo $data["bio"]; ?></p>
          </div>
        </div>
        <div class="section">
          <h3>Work Experience</h3>
          <?php foreach ($experienceData as $edu) { ?>
            <div class="job">
              <div class="title">
                <h4><?php echo $edu["experienceTitle"]; ?></h4>
                <p><?php echo $edu["experiencePlace"]; ?></p>
              </div>
              <p><?php echo $edu["experiencePeriod"]; ?></p>
              <p><?php echo $edu["experienceLocation"]; ?></p>
              <p><?php echo $edu["experienceDescription"]; ?></p>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
      const download_button = document.getElementById("download_Btn");
      const content = document.getElementById("content");

      download_button.addEventListener("click", async function () {
        const filename = "resume.pdf";

        try {
          const opt = {
            margin: 0,
            filename: filename,
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2 },
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
