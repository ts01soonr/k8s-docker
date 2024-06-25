<?php   ob_start();   session_start(); if (!isset($_SESSION['username2'])) header("Location:index.php"); ?>
<html>
<head>
<TITLE>Details</TITLE> 
<style>
#myDIV {
    width: 500px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style> 
</head>
<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
require_once('lib/soonr.php');

$id='';
$ip='';
$port='';
if(isset($_GET['id'])) $id = $_GET["id"];
if(isset($_GET['ip'])) $ip = $_GET["ip"];
if(isset($_GET['port'])) $port = $_GET["port"];
$db = new DB();

$query="SELECT * FROM computers where id='$id' ";
$db->q($query);
if ($db->num == 1) {
	$ip=$db->get("ip");
	$port=$db->get("port");
	$name=$db->get("name");
	$os=$db->get("os");
	$jar=$db->get("jar");
	$status=$db->get("status");
	$time=$db->get("time");
	
	$url='check.php?ip='.$ip.'&port='.$port.'&id='.$id;
	$out="IP-Port: $ip $port<br>Host: $name<br>OS: $os<br>Jar: $jar<br>status: $status<br>Last connection:". date('Y/m/d H:i:s', $time) ."<br><hr><br>";
}	

unset($db);
?>
<body>
<center>

<h1> Device - M</h1>
<h2> <h2> <a href="add.php">Add</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a> -

<a href="edit.php?id=<?php echo $id;?>"> Edit</a> -
<a href="telnet.php?<?php echo 'id='.$id.'&ip='.$ip.'&port='.$port.'&os='.$os;?>"><img src='img/<?php echo $os; ?>.jpg' title = 'remote' /></a></h2>
<div id="myDIV">
<?php echo $out;?>
</div>
<h3>Connection : <a href="<?php echo $url;?>"> Check </a>

</center>
</body>
</html>
