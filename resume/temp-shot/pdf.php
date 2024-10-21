<?php  
include('../connection.php');
$sql="SELECT * FROM `cv_detail` WHERE `id`='149'";
$dd=mysqli_query($con,$sql);
$data=mysqli_fetch_assoc($dd);


$education = json_decode($data['education'], true);
$experienceData = json_decode($data['experienceData'], true);
$skills = json_decode($data['skills'], true);
$languages = json_decode($data['languages'], true);

?>
<?php
require_once __DIR__ . '../../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John Doe - Resume</title>
    <link href="style.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <header class="center">
            <h1><?php echo $data['fullName']; ?></h1>
            <p><?php echo $data['currentPosition']; ?></p>
        </header>
        <section class="about">
            <h2>About Me</h2>
            <p><?php echo $data['bio']; ?></p> </section>
        <section class="personal-info">
            <h2>Personal Information</h2>
            <p>Email: <?php echo $data['email']; ?></p>
            <p>Street: <?php echo $data['street']; ?></p>
            <p>Address: <?php echo $data['address']; ?></p>
            <p>Country: <?php echo $data['country']; ?></p>
            <p>phone Number: <?php echo $data['phoneNumber']; ?></p>
            
            
        </section>
        <section class="education">
            <h2>Education</h2>
            <?php 
                foreach ($education as $edu) { ?>
                    <div class="job">
                        <p class="date"><?php echo $edu['schoolName'] ?> <?php echo $edu['schoolLevel'] ?></p>
                    </div>
             <?php   }
            ?>      
           
        </section>


        <section class="experience">
            <h2>Experience</h2>
            <?php 
                foreach ($experienceData as $edu) { ?>
                    <div class="job">
                        <h4 class="date"><?php echo $edu['experienceTitle'] ?> - <?php echo $edu['experiencePlace'] ?></h4>
                        <p><?php echo $edu['experienceDescription'] ?></p>
                        <p><?php echo $edu['experiencePeriod'] ?> - <?php echo $edu['experienceLocation'] ?></p>
                    </div>
             <?php   }
            ?> 
        </section>
        <section class="skills">
            <h2>Skills</h2>
            <ul>
             <?php 
                foreach ($skills as $edu) { ?>
                                    <li><?php echo $edu['skill'] ?> <strong><?php echo $edu['level'] ?></strong></li>
             <?php   }
            ?> 
            </ul>
        </section>
        <section class="languages">
            <h2>Languages</h2>
            <ul>
            <?php 
    foreach ($languages as $language) {
        echo "<li>" . htmlspecialchars($language) . "</li>";  // Display each language safely
    }

?>

          
            </ul>
        </section>
    </div>
</body>
</html>
');
$mpdf->Output();