<?php   ob_start();   session_start();
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    //header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    //exit;
}
if (isset($_SESSION['username2'])) header("Location:list.php");
?>
<html lang = "en">
<head>
<title>Demo System Login</title> 
<style>         
	body {
		padding-top: 20px;
		padding-bottom: 20px;
		background-color: #ADABAB;
	} 
	.form-signin {
		max-width: 330px;
		padding: 15px;
		margin: 0 auto;
		color: #017572;
        text-align: center;
	}
	.form-signin .form-signin-heading,.form-signin .checkbox {
		margin-bottom: 10px;
	}
	.form-signin .checkbox {
		font-weight: normal;
	}
	.form-signin .form-control {
		position: relative;
		height: auto;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		padding: 10px;
		font-size: 30px;
	}
	.form-signin .form-control:focus {
		z-index: 2;
	}
	.form-signin input[type="email"] {
		margin-bottom: -1px;
		border-bottom-right-radius: 0;
		border-bottom-left-radius: 0;
		border-color:#017572;
	}
	.form-signin input[type="password"] {
		margin-bottom: 10px;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-color:#017572;
		}
	h1{
		text-align: center;
		color: #017572;
	}
</style>
</head>
<body>
	<h1><img src="img/computers.png" /><h1> 
	<?php
            $msg = '';
			if (isset($_POST['login']) && !empty($_POST['username'])&& !empty($_POST['password'])) {
				if (strcasecmp($_POST['username'], 'root') == 0 && strcasecmp($_POST['password'], 'test') == 0) {
					$_SESSION['valid'] = true;
					$_SESSION['password2'] = 'gylle';
					$_SESSION['username2'] = 'soonr';
					header("Location:list.php");
				}else {
					$msg = 'Wrong username or password';
				}
			}
	?>

	<div class = "container"> 
		<h2>Demo System Login</h2>
		<form class = "form-signin" role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post" name = "login">
		<h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
		<input type = "input" class = "form-control" name = "username" placeholder = "username " required autofocus></br></br>
		<input type = "password" class = "form-control" name = "password" placeholder = "password" required> </br>
		<button class = "form-control" type = "reset" name = "reset">Clean</button>  -  <button class = "form-control" type = "submit" name = "login">Login</button> 
		</form>
	</div>
</body>
</html>