<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
require_once('lib/soonr.php');
$t=2;
$id='';
$ip='';
$port='';
$status='';
$res = 'check & update connection : ' ;

if(isset($_GET['id'])) $id = $_GET["id"];
if(isset($_GET['ip'])) $ip = $_GET["ip"];
if(isset($_GET['port'])) $port = $_GET["port"];
if (strlen($ip)>0 && strlen($port)>0){ 
	$tel = new Telnet($ip,$port,$t);	
	if($tel->isOK()) $status='Online';
	else $status='Offline';
	unset($tel);
	{
		$db = new DB();
		$query="SELECT * FROM computers where ip='$ip' && port='$port'";
		$db->q($query);
		$dbs=$db->get("status");
		$query = "UPDATE computers SET";
		if ( $status != $dbs) 
			$query = $query." status='".$status."',";
		$query = $query ." time='".time()."' where ip='$ip' && port='$port'";
		$db->q($query);	
		unset($db);
	}		
}
if ( strlen($id)>0 )	header("Location: dm.php?id=".$id);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){  // handle post form for check one connection or all connections.

	if(isset($_POST['ip'])) $ip=$_POST['ip'];
	if(isset($_POST['port'])) $port=$_POST['port'];	
	if (strlen($ip)>0 && strlen($port)>0){ 
		$query="SELECT * FROM computers where ip like '$ip' && port like '$port'";
		$db = new DB();
		$db->q($query);
		$res ="records: ".$db->num ."</br>";
		$i=0;
		$j=0;
		$size=$db->num;
		while ($i < $size) {
			$_id=$db->geti($i,'id');
			$_ip=$db->geti($i,'ip');
			$_port=$db->geti($i,'port');
			$dbs=$db->geti($i,"status");
			$tel = new Telnet($_ip,$_port,$t);
			if($tel->isOK()) $status='Online';
			else $status='Offline';
			unset($tel);		
			
			//$query = "UPDATE computers SET";
			if ( $status != $dbs) {
				$query = "UPDATE computers SET status='".$status."', time='".time()."' where id='".$_id."' ";
				//echo $query;
				$db->up($query);
				$j++;
				$res = $res. $_ip.":".$_port."'s connection is updated from ".$dbs." to ".$status."</br>";
			}	
			$i++;
		}
		unset($db);
		$res = $res. " Status update: ".  $j ." out of ". $size . " records";
	}
	else $res = "Hey, your input is empty, using % for multi search !!!" ;
    //if( $j > 0)  header("Location: list.php");
}
?>
<HTML> 
<HEAD>
<style>
#myDIV {
    width: 500px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style> 
<TITLE>Connection</TITLE> 	 
</HEAD> 
</BODY>
<center>
<h1> Check & Update - Connection</h1>
<h2> <a href="add.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> - 
<a href="check.php"><img src='/refresh.png' align='BOTTOM' alt='refresh'></a> </h2>
</h2>

<form action="check.php" method="post">
IP address: <input type="text" name="ip" value="<?php echo $ip; ?>">
Port: <input type="text" name="port" size="4" value="<?php echo $port; ?>"> - <input type="Submit"> <br>
</form>
<div id="myDIV"><?php echo $res;?></div>
</BODY>
</center>
</html>