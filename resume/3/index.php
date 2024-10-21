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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="resume.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Resume</title>
</head>

<body>
  <img id="download_Btn" style="width:50px" class="download" src="../thumbnails/download.png">
  <main>
    <div id="content" class="container">
      <div class="left">
        <div class="profile-img">
          <img src="<?php echo "data:image/jpeg;base64," . imageToBase64($data); ?>" alt="Profile Image">
        </div>
        <div class="contact-info">
          <h3>CONTACT</h3>
          <ul>
            <li><i class="fa-solid fa-envelope" style="margin-right: 10px;"></i> <?php echo $data["email"]; ?></li>
            <li><i class="fa-solid fa-phone-volume" style="margin-right: 13px;"></i> <?php echo $data["phoneNumber"]; ?></li>
            <li><i class="fa-solid fa-house" style="margin-right: 10px;"></i> <?php echo $data["street"]; ?> <?php echo $data["address"]; ?> <?php echo $data["country"]; ?></li>
          </ul>
          
          <h3>EDUCATION</h3>
          <?php foreach ($education as $edu) { ?>
            <p><?php echo $edu["schoolName"]; ?><br><?php echo $edu["schoolLevel"]; ?></p>
          <?php } ?>
          
          <h3>SKILLS</h3>
          <ul class="ul">
            <?php foreach ($skills as $edu) { ?>
              <li class="li li2"><?php echo $edu["skill"]; ?></li>
            <?php } ?>
          </ul>
          
          <h3>LANGUAGES</h3>
          <ul class="ul">
            <?php foreach ($languages as $edu) { ?>
              <li class="li"><?php echo $edu["name"]; ?></li>
            <?php } ?>
          </ul>
        </div>
      </div>

      <div class="right">
        <div class="profile-title">
          <h1><?php echo $data["fullName"]; ?></h1>
          <p><?php echo $data["currentPosition"]; ?></p>
          <div class="line"></div>
        </div>

        <div class="profile-content">
          <h3>PROFILE</h3>
          <p><?php echo $data["bio"]; ?></p>
          
          <h3>Professional Experience</h3>
          <?php foreach ($experienceData as $edu) { ?>
            <div class="heading">
              <p style="float: left; font-weight: 600;"><?php echo $edu["experienceTitle"]; ?> - <?php echo $edu["experiencePlace"]; ?></p>
              <br>
              <p><?php echo $edu["experiencePeriod"]; ?></p>
              <p><?php echo $edu["experienceLocation"]; ?></p>
              <div>
                <p><?php echo $edu["experienceDescription"]; ?></p>
              </div>
            </div>
            <br><br>
          <?php } ?>
        </div>
      </div>
    </div>
  </main>
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
