
<html>
<head>
<TITLE>Result</TITLE> 
<style>
#myDIV {
    width: 500px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style> 
<style>
#myDIV2 {
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
$del='';
$div='';
$output='';
$pre='#';
$next='#';
$num=0;
if(isset($_GET['id'])) $id = $_GET["id"];
if(isset($_GET['del'])) $del = $_GET["del"];
$db = new DB();
if($del=='1'){
	$query="DELETE FROM results WHERE id='$id'";
    $db->up($query);
	unset($db);
}
else{
	$query="SELECT * FROM results where id='$id' ";
	$db->q($query);
	$num=$db->num;
	if ($num == 1) {
		$ip=$db->get("ip");
		$port=$db->get("port");
		$command=$db->get("command");
		$start=$db->get("start");
		$end=$db->get("end");
		$output=$db->get("output");		
		$url='check.php?ip='.$ip.'&port='.$port.'&id='.$id;
		$div="[IP:]$ip<br>[Port:] $port<br>[Command:]<br> ".nl2br($command)."<br>[Start:] ".date('Y/m/d H:i:s', $start)."<br>[End:] ".date('Y/m/d H:i:s', $end)."<br><hr>";
		$pre="result.php?id=".($id-1);
		$next="result.php?id=".($id+1);
	}
	unset($db);	
	if( $num == 0 ) header("Location: results.php");
}


?>
<body>
<center>

<h1> Details of output from DB</h1>
<h2> <h2> <a href="add.php">Add</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a> </h2>
<div id="myDIV">
<?php echo $div;?>
</div>
<br>
<div id="myDIV2">
[Output:]<br>
<?php echo $output;?>
</div>
<h3><a href="<?php echo $pre;?>"> Prev </a>  -  <a href="results.php"> Full Records </a>  -  <a href="<?php echo $next;?>"> Next </a>

</center>
</body>
</html>
