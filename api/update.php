
<html>
<head>
<TITLE>update</TITLE> 
</head>
<body>
<h1> Update - Record</h1>
<?php
require_once('lib/soonr.php');
$id=$_POST['id'];
$ip=$_POST['ip'];
$port=$_POST['port'];
$name=$_POST['name'];
$os=$_POST['os'];
$jar=$_POST['jar'];
$status=$_POST['status'];
$time=$_POST['time'];
$db = new DB();	
$query="UPDATE computers SET ip='$ip', port='$port', name='$name', os='$os', jar='$jar', status='$status', time='$time' WHERE id='$id'";
$db->up($query);
unset($db);
echo "Record Updated";

?>

<h2> <a href="add.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> </h2>


</body>
</html>
