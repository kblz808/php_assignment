<?php
   require 'config/config.php';

   if(isset($_SESSION['username'])){
      $userLoggedIn = $_SESSION['username'];
      $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
      $user = mysqli_fetch_array($user_details_query);
   }else{
      header("Location: register.php");
   }
?>

<html>
<head>
   <script
   src="https://code.jquery.com/jquery-3.6.1.min.js"
   integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
   crossorigin="anonymous"></script>
   <script src="assets/js/bootstrap.js"></script>
   <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

   <div class="top_bar">

      <div class="logo">
         <a href="index.php">Social-app</a>
      </div>

      <nav>
         <a href="#"><?php echo $user['first_name']; ?></a>
         <a href="index.php">Home</a>
         <a href="#">Message</a>
         <a href="#">Notification</a>
         <a href="#">User</a>
         <a href="#">Settings</a>
         <a href="/includes/handlers/logout.php">Logout</a>
      </nav>

   </div>

   <div class="wrapper">