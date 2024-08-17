<?php
require_once('lib/soonr.php');
$id="";
$op="";
if(isset($_GET['id'])) $id = $_GET["id"];
if(isset($_GET['op'])) $op = $_GET["op"];
if ($_SERVER['REQUEST_METHOD'] == 'POST'){ 
	if(isset($_POST['id'])) $id = $_POST["id"];
	if(isset($_POST['op'])) $op = $_POST["op"];
}	
$db = new DB();	

if($op=="list"){
	echo "pending";
}else if($op=="add"){
	$json="";
	$time="";
	if(isset($_GET['json'])) $json = $_GET["json"];
	if ($_SERVER['REQUEST_METHOD'] == 'POST') $json = json_encode($_POST);
	if(strlen($json) > 0){
		$time=time();
		$db = new DB();
		$query = "INSERT INTO webhooks VALUES (null,'$time','$json')";
		$db->up($query);		
		echo $query;
	}			
}else if($op=="num"){
		//
	$query="SELECT * FROM webhooks";
	$db->q($query);
	echo $db->num;
}else if($op=="latest"){
		//
	$query="SELECT * FROM webhooks ORDER BY id DESC";
	$db->q($query);
	echo $db->geti(0,"result");
    
}else if($op=="del"){
    $query="DELETE FROM webhooks WHERE id>0";
	if($id!="")	$query="DELETE FROM webhooks WHERE id='$id'";
	$db->up($query);
	echo "delete record";
}else{
    echo "0000";
}
unset($db);
?>
