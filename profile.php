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

      <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something">

      <?php
         if($userLoggedIn != $username){
            echo '<div class="profile_info_bottom">';
            echo $logged_in_user_obj->getMutualFriends($username) . "Mutual friends";
            echo "</div>";
         }
      ?>

   </div>


   <div class="profile_main_column column">
      <div class="posts_area"></div>

      <img id="loading" src="assets/images/icons/loading.gif">
   </div>



   <!-- Modal -->
   <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
       <div class="modal-content">

         <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">Post Something</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>

         <div class="modal-body">
           <p>This will appear on the user's profile page and also their newsfeed for your friends to see</p>

           <form action="" method="POST" class="profile_post">
              <div class="form_group">
                 <textarea class="form-control" name="post_body"></textarea>
                 <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                 <input type="hidden" name="user_to" value="<?php echo $username; ?>">
              </div>
           </form>

         </div>

         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
           <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
         </div>
       </div>
     </div>
   </div>


   <script>
      let userLoggedIn = '<?php echo $userLoggedIn ?>';
      let profileUsername = '<?php echo $username; ?>';

      $(document).ready(function(){

         $('#loading').show();

         // first ajax request for loading post
         $.ajax({
            url: "includes/handlers/ajax_load_profile_post.php",
            type: "POST",
            data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
            cache: false,

            success: function(data){
               $('#loading').hide();
               $('.posts_area').html(data);
            }
         });
      });

      $(window).scroll(function(){
         let height = $('.posts_area').height();
         let scroll_top = $(this).scrollTop();
         let page = $('.posts_area').find('.nextPage').val();
         let noMorePosts = $('.posts_area').find('.noMorePosts').val();

         if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
            $('#loading').show();
            let ajaxReq = $.ajax({
               url: "includes/handlers/ajax_load_profile_post.php",
               type: "POST",
               data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
               cache: false,

               success: function(response){
                  $('.posts_area').find('.nextPage').remove(); // remove current .nextPage
                  $('.posts_area').find('.noMorePosts').remove(); // remove current .nextPage


                  $('#loading').hide();
                  $('.posts_area').append(response);
               }
            });


         }  // end if 

         return false;

      });   // end

   </script>
   
   </div>

</body>
</html>