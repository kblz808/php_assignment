<?php
   include("includes/header.php");


   if(isset($_GET['profile_username'])){
      $username = $_GET['profile_username'];
      $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
      $user_array = mysqli_fetch_array($user_details_query);

      $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
   }

   if(isset($_POST['remove_friend'])){
      $user = new User($con, $userLoggedIn);
      $user->removeFriend($username);
   }
   if(isset($_POST['add_friend'])){
      $user = new User($con, $userLoggedIn);
      $user->sendRequest($username);
   }
   if(isset($_POST['respond_request'])){
      header("Location: requests.php");
   }
?>


   <style>
      .wrapper{
         margin-left: 0px;
         padding-left: 0px;
      }
   </style>



   <div class="profile_left">
      <img src="<?php echo $user_array['profile_pic']; ?>">

      <div class="profile_info">
         <p><?php echo "Pots: " . $user_array['num_posts']; ?></p>
         <p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
         <p><?php echo "Friends: " . $num_friends; ?></p>
      </div>

      <form action="profile.php?profile_username=<?php echo $username; ?>" method="POST">
         <?php
            $profile_user_obj = new User($con, $username);

            if($profile_user_obj->isClosed()){
               header("Location: user_closed.php");
            }

            $logged_in_user_obj = new User($con, $userLoggedIn);

            // button depending on status of friend
            if($userLoggedIn != $username){
               // if its friend
               if($logged_in_user_obj->isFriend($username)){
                  echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
                  // if received request
               }else if($logged_in_user_obj->didReceiveRequest($username)){
                  echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
                  // if sent request
               }else if($logged_in_user_obj->didSendRequest($username)){
                  echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
               }else{
                  echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
               }
            }

         ?>
      </form>
   </div>

   <div class="main_column column">
      <?php echo $username ?>
   </div>

   </div>
</body>
</html>