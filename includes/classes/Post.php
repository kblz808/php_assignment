<?php
	class Post{
		private $user_obj;
		private $con;

		public function __construct($con, $user){
			$this->con = $con;
			$this->user_obj = new User($con, $user);
		}

		public function submitPost($body, $user_to){
			$body = strip_tags($body);	// remove html tags
			$body = mysqli_real_escape_string($this->con, $body);

			// replace new line breaks
			$body = str_replace('\r\n', '\n', $body);
			$body = nl2br($body);

			$check_empty = str_replace(' ', '', $body);	// remove spaces
			// $check_empty = preg_replace('/\s+/', '', $body);

			// check if not empty
			if($check_empty != ""){

				// date added
				$date_added = date("Y-m-d H:i:s");

				// get username
				$added_by = $this->user_obj->getUsername();

				// if user is on own profile
				if($user_to == $added_by){
					$user_to = "none";
				}

				// insert post
				$query = mysqli_query($this->con, "INSERT INTO posts VALUES(NULL, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', 0)");

				$return_id = mysqli_insert_id($this->con);

				// insert notification

				// update post count for user
				$num_posts = $this->user_obj->getNumPosts();
				$num_posts++;

				$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
			}
		}
	}
?>