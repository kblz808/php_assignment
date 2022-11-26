<?php
	session_start();

   $con = mysqli_connect("localhost", "phpmyadmin", "some_pass", "social");

   if(mysqli_connect_errno()){
      echo "failed to connect" . mysqli_connect_errno();
   }

   // variables
   $fname = "";
   $lname = "";
   $em = "";
   $em2 = "";
   $password = "";
   $password2 = "";
   $date = "";
   $error_array = array();	// error messages
   $profile_pic = "";


   if(isset($_POST['register_button'])){
   	// registeration form values

   	// first-name
   	$fname = strip_tags($_POST['reg_fname']);
   	$fname = str_replace(' ', '', $fname);
   	$fname = ucfirst(strtolower($fname));	// capitalize only the first letter
   	$_SESSION['reg_fname'] = $fname;	// store into session

   	// last-name
   	$lname = strip_tags($_POST['reg_lname']);
   	$lname = str_replace(' ', '', $lname);
   	$lname = ucfirst(strtolower($lname));	// capitalize only the first letter
   	$_SESSION['reg_lname'] = $lname;	// store into session

   	// email
   	$em = strip_tags($_POST['reg_email']);
   	$em = str_replace(' ', '', $em);
   	$em = ucfirst(strtolower($em));	// capitalize only the first letter
   	$_SESSION['reg_email'] = $em;	// store into session

   	// email-2
   	$em2 = strip_tags($_POST['reg_email2']);
   	$em2 = str_replace(' ', '', $em2);
   	$em2 = ucfirst(strtolower($em2));	// capitalize only the first letter
   	$_SESSION['reg_email2'] = $em2;	// store into session

   	// password
   	$password = strip_tags($_POST['reg_password']);

   	// password-2
   	$password2 = strip_tags($_POST['reg_password2']);

   	// date
   	$date = date("Y-m-d");	// current date


   	// validate email
   	if($em == $em2){
   		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
   			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

   			// check if email exists
   			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

   			// count number of rows returned
   			$num_rows = mysqli_num_rows($e_check);

   			if($num_rows > 0){
   				array_push($error_array, "Email already in use<br>");
   			}
   		}else{
   			array_push($error_array, "Invalid email format<br>");
   		}
   	}else{
   		array_push($error_array, "Emails don't match<br>");
   	}


   	// validate name
   	if(strlen($fname) > 25 || strlen($fname) < 2){
   		array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
   	}
   	if(strlen($lname) > 25 || strlen($lname) < 2){
   		array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
   	}


   	// validate password
   	if($password != $password2){
   		array_push($error_array, "Your passwords do not match<br>");
   	}else{
   		if(preg_match('/[^A-Za-z0-9]/', $password)){
   			array_push($error_array, "Your password can only contain english characters or numbers<br>");
   		}
   	}
   	if(strlen($password) > 30 || strlen($password) < 5){
   		array_push($error_array, "Your password must be between 5 and 30 characters<br>");
   	}


   	if(empty($error_array)){
   		$password = md5($password);	// encrypt password

   		// generate username
   		$username = strtolower($fname . "_" . $lname);
   		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

   		// while name exists chage the user name till we find unused one
   		$i = 0;
   		while(mysqli_num_rows($check_username_query) != 0){
   			$i++;
   			$username = $username . "_" . $i;
   			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
   		}


   		// profile picture
   		$rand = rand(1, 2);
   		if($rand == 1){
   			$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
   		}else if($rand == 2){
   			$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
   		}


   		// save values in database
   		$query = mysqli_query($con, "INSERT INTO users VALUES(NULL, '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', 0, 0, 'no', ',')");


   		// success message
   		array_push($error_array, "<span style='color: #14C800;'>You're all set! Goahead and login </span><br>");


   		// clear session variables
   		$_SESSION['reg_fname'] = "";
   		$_SESSION['reg_lname'] = "";
   		$_SESSION['reg_email'] = "";
   		$_SESSION['reg_email2'] = "";
   	}
   }

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

	<form action="register.php" method="POST">

		<!-- name -->
		<input type="text" name="reg_fname" placeholder="First Name" value="<?php 
			if(isset($_SESSION['reg_fname'])){
				echo $_SESSION['reg_fname'];
			}
		?>" required>
		<br>
		<?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>";?>

		<input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
			if(isset($_SESSION['reg_lname'])){
				echo $_SESSION['reg_lname'];
			}
		?>" required>
		<br>
		<?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>";?>
		<!-- name -->



		<!-- email -->
		<input type="email" name="reg_email" placeholder="Email" value="<?php 
			if(isset($_SESSION['reg_email'])){
				echo $_SESSION['reg_email'];
			}
		?>" required>
		<br>

		<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
			if(isset($_SESSION['reg_email2'])){
				echo $_SESSION['reg_email2'];
			}
		?>" required>
		<br>
		<?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
			else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
			else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>";
		?>
		<!-- email -->



		<!-- password -->
		<input type="password" name="reg_password" placeholder="Password" required>
		<br>
		
		<input type="password" name="reg_password2" placeholder="Confirm Password" required>
		<br>
		<?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>";
			else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
			else if(in_array("Your password must be between 5 and 30 characters<br>", $error_array)) echo "Your password must be between 5 and 30 characters<br>";
		?>
		<!-- password -->



		<input type="submit" name="register_button" value="Register">

		<br>

		<?php if(in_array("<span style='color: #14C800;'>You're all set! Goahead and login </span><br>", $error_array)) echo "<span style='color: #14C800;'>You're all set! Goahead and login </span><br>";
		?>

	</form>

</body>
</html>