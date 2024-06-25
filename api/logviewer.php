<? ob_start(); ?>

<html xmlns:lxslt="http://xml.apache.org/xslt" xmlns:stringutils="xalan://org.apache.tools.ant.util.StringUtils">
<head>
<script type="text/javascript">

 function timedRefresh(timeoutPeriod) {
	
		window.location = "logviewer.php#bottomlink";
	    setTimeout("location.reload(true);",timeoutPeriod);
}
</script>		
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Log viewer.</title>
</head>

<body onload="JavaScript:timedRefresh(5000);"> 
<div id="PHPDATA">  
<a href="/index.php"> home</a> --|--
<a href="/result.php"> report </a> --|--
<a href="/logmini.php"> logmini</a> --|--
<a href="/2/taskkill.php" > terminate </a> --|--
<a href="javascript:window.close();"> close</a>
</div>
<HR>  
<?php
echo "<b>------log info(log.txt),it will open test report.html when it done-----</b><br />";
if(file_exists("play_log.txt")){ 
	$file = file("play_log.txt");	
	foreach($file as $linje) {
	 echo $linje . "<br />"; 
	 if(strstr($linje,"bye OK"))
	    header("Location: play_log.txt");
	}
	
}
?>

<HR>

<a href="/logviewer.php" name="bottomlink"> reflesh</a> --|--
<a href="/index.php"> home</a> --|--
<a href="/result.php"> report </a> --|--
<a href="/logmini.php"> logmini</a> --|--
<a href="/2/taskkill.php" > terminate </a> --|--
<a href="javascript:window.close();"> close</a>
</BODY>
</html>
<? ob_flush(); ?> 