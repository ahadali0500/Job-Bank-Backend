<?php
         $dbhost = "localhost";
         $dbuser = "uju6v9ktwy87h";
         $dbpass = "m2GbK@j?22bo";
         $dbname = "dbw3yex8omtzp5";
         $con = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
        
         if(!$con) {
            die('Could not connect: ' . mysqli_error());
         }
?>
