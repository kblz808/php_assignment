<?php
   require 'config/config.php';
   // $con = mysqli_connect("localhost", "phpmyadmin", "some_pass", "social");
   // if(mysqli_connect_errno()){
   //    echo "failed to connect" . mysqli_connect_errno();
   // }

   $query = mysqli_query($con, "INSERT INTO test VALUES(NULL, 'max')");
?>

<html>
   <head>
      <title></title>
   </head>

   <body>
        hello world
   </body>
</html>
