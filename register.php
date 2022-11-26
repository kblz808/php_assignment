<?php
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
   $error_array = "";	// error messages

   if(isset($_POST['register_button'])){
   	// registeration form values

   	// first-name
   	$fname = strip_tags($_POST['reg_fname']);
   	$fname = str_replace(' ', '', $fname);
   	$fname = ucfirst(strtolower($fname));	// capitalize only the first letter

   	// last-name
   	$lname = strip_tags($_POST['reg_lname']);
   	$lname = str_replace(' ', '', $lname);
   	$lname = ucfirst(strtolower($lname));	// capitalize only the first letter

   	// email
   	$em = strip_tags($_POST['reg_email']);
   	$em = str_replace(' ', '', $em);
   	$em = ucfirst(strtolower($em));	// capitalize only the first letter

   	// email-2
   	$em2 = strip_tags($_POST['reg_email2']);
   	$em2 = str_replace(' ', '', $em2);
   	$em2 = ucfirst(strtolower($em2));	// capitalize only the first letter

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
   				echo "email already in use";
   			}
   		}else{
   			echo "invalid format";
   		}
   	}else{
   		echo "email doesnt match";	
   	}


   	// validate name
   	if(strlen($fname) > 25 || strlen($fname) < 2){
   		echo "invalid first name";
   	}

   	if(strlen($lname) > 25 || strlen($lname) < 2){
   		echo "invalid last name";
   	}


   	// validate password
   	if($password != $password2){
   		echo "password doesnt match";
   	}else{
   		if(preg_match('/[^A-Za-z0-9]/', $password)){
   			echo "password can only contain english characters and numbers";
   		}
   	}

   	if(strlen($password) > 30 || strlen($password) < 5){
   		echo "password must be between 5 and 30 characters";
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

		<input type="text" name="reg_fname" placeholder="First Name" required>
		<br>
		<input type="text" name="reg_lname" placeholder="Last Name" required>
		<br>

		<input type="email" name="reg_email" placeholder="Email" required>
		<br>
		<input type="email" name="reg_email2" placeholder="Confirm Email" required>
		<br>		

		<input type="password" name="reg_password" placeholder="Password" required>
		<br>
		<input type="password" name="reg_password2" placeholder="Confirm Password" required>
		<br>

		<input type="submit" name="register_button" value="Register">

	</form>

</body>
</html>