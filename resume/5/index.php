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
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
      <title>Cv</title>
      <link rel="stylesheet" href="style.css">
</head>
<body>
<img id="download_Btn" style="width:50px" class="download" src="../thumbnails/download.png" >
      <div id="content" class="container">
            <header class="header">
                  <img class="img" src="<?php echo "data:image/jpeg;base64," . imageToBase64($data); ?>" alt="Profile Image" />
                  <div class="name">
                        <h1><?php echo $data["fullName"]; ?></h1>
                        <p><?php echo $data["currentPosition"]; ?></p>
                  </div>
            </header>
            <main class="main">
                  <section class="sidebar">
                        <div class="contact">
                              <h3>Contact</h3>
                              <ul>
                                    <li><i class="ri-phone-fill"></i><p><?php echo $data["phoneNumber"]; ?></p></li>
                                    <li><i class="ri-mail-fill"></i><p><?php echo $data["email"]; ?></p></li>
                                    <li><i class="ri-map-pin-2-fill"></i><p><?php echo $data["street"]; ?> <?php echo $data["address"]; ?> <?php echo $data["country"]; ?></p></li>
                              </ul>
                        </div>
                        <div class="education">
                              <h3>Education</h3>
                              <?php foreach ($education as $edu) { ?>
                                <p><?php echo $edu["schoolName"]; ?><br /><?php echo $edu["schoolLevel"]; ?></p>
                                <br>
                              <?php } ?>
                        </div>
                        <div class="languages">
                              <h3>Language</h3>
                              <ul>
                                    <?php foreach ($languages as $lang) { ?>
                                      <li><?php echo $lang["name"]; ?></li>
                                    <?php } ?>
                              </ul>
                        </div>
                        <div class="skills">
                              <h3>Skills</h3>
                              <ul>
                                    <?php foreach ($skills as $skill) { ?>
                                      <li><?php echo $skill["skill"]; ?></li>
                                    <?php } ?>
                              </ul>
                        </div>
                  </section>
                  <section class="content">
                        <div class="profile">
                              <h3>About Me</h3>
                              <p><?php echo $data["bio"]; ?></p>
                        </div>
                        <div class="experience">
                              <h3>Experience</h3>
                              <?php foreach ($experienceData as $exp) { ?>
                                <div class="exp-div">
                                      <div class="exp-div-h">
                                            <h4><?php echo $exp["experienceTitle"]; ?> (<?php echo $exp["experiencePlace"]; ?>)</h4>
                                            <p><?php echo $exp["experiencePeriod"]; ?></p>
                                            <p><?php echo $exp["experienceLocation"]; ?></p>
                                      </div>
                                      <div class="exp-div-m">
                                            <ul>
                                                  <li><?php echo $exp["experienceDescription"]; ?></li>
                                            </ul>
                                      </div>
                                </div>
                              <?php } ?>
                        </div>
                  </section>
            </main>
      </div>
    
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    
      <script>
            const download_button = document.getElementById('download_Btn');
            const content = document.getElementById('content');
    
            download_button.addEventListener('click', async function () {
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
