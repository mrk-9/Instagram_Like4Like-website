<?php
   include("config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      
      $sql = "SELECT id FROM users WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count) {
         // session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         
         header('Location:memberships.html');
      }else {
         echo '<script language="javascript">';
         echo 'alert("This user is not registered on this website.");';
         echo "location.replace('log-in.html');";
         echo '</script>';
      }
   }
?>