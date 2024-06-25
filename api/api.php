<?php
   ob_start();
   session_start();
   $un="";
   $pwd="";
   if(isset($_GET['key'])) {
      if ($_GET['key'] == 'Kq6Yb2By.hY4tS') {
        $_SESSION['valid'] = true;
        $_SESSION['password2'] = 'gylle';
        $_SESSION['username2'] = 'soonr';
        header("Location:list.php");              
      }
   }
   // error_reporting(E_ALL);
   // ini_set("display_errors", 1);
?>

<html lang = "en"> 
    <head>
    <TITLE>HB login</TITLE> 
    </head>
    <body >
      
      <h2>Input your login:</h2> 
      <div >
         
         <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				
               if ($_POST['username'] == 'soonr' && 
                  $_POST['password'] == 'gylle') {
                  $_SESSION['valid'] = true;
                  $_SESSION['password2'] = 'gylle';
                  $_SESSION['username2'] = 'soonr';
                  header("Location:list.php");              
               }else {
                  $msg = 'Wrong username or password';
               }
            }
         ?>
      </div> <!-- /container -->
      
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post" name="login">
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            <input type = "text" class = "form-control" name = "username" ></br>
            <input type = "text" class = "form-control" name = "password" ></br>
            <button  type = "submit" name = "login">Login</button>
         </form>		
      Click here to clean <a href = "/logout.php" tite = "Logout">Session.</a>
      </div> 
      
   </body>
</html>