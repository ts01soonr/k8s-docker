<?php
   session_start();
   unset($_SESSION["username2"]);
   unset($_SESSION["password2"]);
   
   echo 'You have cleaned session';
?>
<html lang = "en"><head><title>Logout</title> <meta http-equiv="refresh" content="1;url=index.php"></head>
<body><center><h1><a href="index.php">Back to login page</a> </h1></center>
</body>
</html>