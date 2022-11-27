<?php
	if(isset($_POST['login_button'])){
		$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);	// check if email is valid
		$_SESSION['log_email'] = $email;	// store email in session variable

		$password = md5($_POST['log_password']);

		$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
		$check_login_query = mysqli_num_rows($check_database_query);

		if($check_login_query == 1){
			// fetch user values from databse
			$row = mysqli_fetch_array($check_database_query);	// results returned from the query
			$username = $row['username'];

			// reopen closed account
			$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
			if(mysqli_num_rows($user_closed_query) == 1){
				$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
			}

			// clear session variables
	   		$_SESSION['reg_fname'] = "";
	   		$_SESSION['reg_lname'] = "";
	   		$_SESSION['reg_email'] = "";
	   		$_SESSION['reg_email2'] = "";
	   		$_SESSION['log_email'] = "";

			// store username and redirect page
			$_SESSION['username'] = $username;
			header("Location: index.php");
			exit();
			
		}else{
			array_push($error_array, "Email or password was incorrect<br>");
		}
	}
?>