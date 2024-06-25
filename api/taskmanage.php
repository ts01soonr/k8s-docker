<?php
require_once('lib/soonr.php');
$id="";
$cid="";
$task="";
$status="";
$result="";
$op="";
$start="";
$end="";
if(isset($_GET['id'])) $id = $_GET["id"];
if(isset($_GET['cid'])) $cid = $_GET["cid"];
if(isset($_GET['task'])) $task = $_GET["task"];
if(isset($_GET['status'])) $status = $_GET["status"];
if(isset($_GET['result'])) $result = $_GET["result"];
if(isset($_GET['op'])) $op = $_GET["op"];
if(isset($_GET['start'])) $start = $_GET["start"];
if(isset($_GET['end'])) $end = $_GET["end"];
if ($_SERVER['REQUEST_METHOD'] == 'POST'){ 

	if(isset($_POST['id'])) $id = $_POST["id"];
	if(isset($_POST['cid'])) $cid = $_POST["cid"];
	if(isset($_POST['task'])) $task = $_POST["task"];
	if(isset($_POST['status'])) $status = $_POST["status"];
	if(isset($_POST['result'])) $result = $_POST["result"]; 
	if(isset($_POST['op'])) $op = $_POST["op"];
	if(isset($_POST['start'])) $start = $_POST["start"];
	if(isset($_POST['end'])) $end = $_POST["end"];
}	
	$db = new DB();	
	if($op=="list"){
		//http://dk.35cloud.com/api/taskmanage.php?op=list&cid=8&status=2
		$query="SELECT * FROM tasks where cid=$cid && status=$status";
		$db->q($query);
		$i=0;
		while ($i < $db->num) {	// Get private task first
			$cmd=$db->geti($i,"task");
			$tid=$db->geti($i,"id");			
			echo $tid."::".$cmd.">>";
			$i++;
		}
		if( $i == 0) {			// if there no own task, then get public task from Master
			$db->q("SELECT * FROM tasks where cid=1 && status=$status");
			if( 0 < $db->num ) {
				$cmd=$db->geti($i,"task");
				$tid=$db->geti($i,"id");			
				echo $tid."::".$cmd;
				$db->up("UPDATE tasks SET status='1' WHERE id='$tid'");
			}
		}
		$time=time();
		$query = "UPDATE computers SET time='$time', status='Online' WHERE id='$cid' || id='1'";
		$db->up($query);
	}else if($op=="up"){
			//http://dk.35cloud.com/api/taskmanage.php?op=up&cid=8&status=1
			$up=" ";
			if($status!="") $up=$up."status='$status' ";
			if($status=="2") $up=$up.",result='...' ";			
			if($task!="") $up=$up."task='$task' ";
			if($cid!="")	$query = "UPDATE tasks SET".$up."WHERE cid='$cid'";
			if($id!="")	$query = "UPDATE tasks SET".$up."WHERE id='$id'";
			echo $query;
			//echo "update Record:".$cid;
			$db->up($query);
	}else if($op=="update"){
			//http://dk.35cloud.com/api/taskmanage.php?op=update&cid=8&status=1
			$up=" ";
			$up=$up."cid='$cid', ";
			$up=$up."status='$status', ";
			$up=$up."task='$task', ";
			$up=$up."result='$result' ";
			//if($cid!="")	$query = "UPDATE tasks SET".$up."WHERE cid='$cid'";
			$query = "UPDATE tasks SET".$up."WHERE id='$id'";
		    //echo $query; // Only update item when it is different
			//echo "Update Record for ".$cid;
			if($id=="") $query="INSERT INTO tasks VALUES (null,'$cid','$task','2','')";
			$db->up($query);
			echo "<center><h1>Record is Updated, <a href='tasklist.php?cid=$cid'>[Back]</a></h1></center>";
			//header("Location:tasklist.php?cid=$cid");
			
	}else if($op=="runat"){			
			$up=" ";
			$up=$up."status='2' ";											
			if($id!="" && $cid!="")	$query = "UPDATE tasks SET".$up.",result='...',cid='$cid' WHERE id='$id'";	// run task on different computer		
			echo $query;
			//echo "update Record:".$cid;
			$db->up($query);
	}else if($op=="save"){
			//http://dk.35cloud.com/api/taskmanage.php?op=save&id=1&result="we2r323"
			//echo $result;
			$up=" ";
			$up=$up."status='0', ";
			$up=$up."result='$result' ";
			$query = "UPDATE tasks SET".$up."WHERE id='$id'";
		  //echo $query; // Only update item when it is different
			echo "update Record:".$id;
			if($result!="" && $id!="") $db->up($query);
			if($start!="" && $end!=""){
			    $insert = "INSERT INTO results VALUES (null,'$cid','$id','$start','$task','$result','$end')";
			    $db->up($insert);
				echo "save result to db";
			}
	}else if($op=="add"){
			//http://dk.35cloud.com/api/taskmanage.php?op=add&cid=8&task=who			
			$insert = "INSERT INTO tasks VALUES (null,'$cid','$task','2','')";
			if($cid!="" && $task!="")  $db->up($insert);
			echo "add task ( $task ) to $cid";
			echo "<center><h1><a href='tasklist.php?cid=$cid'>[Back]</a></h1></center>";			
	}else if($op=="view"){
			//
			$query="SELECT * FROM tasks";
			$db->q($query);
			$i=0;
			while ($i < $db->num) {
			$tid=$db->geti($i,"id");	
			$cid=$db->geti($i,"cid");
			$cmd=$db->geti($i,"task");
			$status=$db->geti($i,"status");	
			$out=$db->geti($i,"result");			
			echo "$tid:[on computer-$cid]>$cmd-$status</br></br>";
			echo "$out<p>";
			echo "-----------------------------</br>";
			$i++;
			}
	}else if($op=="stop"){
			if($cid=="") exit;
			$time=time();
			$query = "UPDATE computers SET time='$time', status='Offline' WHERE id='$cid'";
			$db->up($query);
			echo $query. " to offline $cid";
	}else if($op=="del"){
			//
			if($cid!="")	$query="DELETE FROM tasks WHERE cid='$cid'";
			if($id!="")	$query="DELETE FROM tasks WHERE id='$id'";
			$db->up($query);
			echo "delete record";
	}else if($op=="jar"){
			//
			if($status=="") $status="2";
			$query = "UPDATE tasks SET status='$status' WHERE task like '%jar update%'";
			$db->up($query);
			echo "invoke job to upgrade jar";
	}else if($op=="result"){
			//http://dk.35cloud.com/api/taskmanage.php?op=result&cid=8
			if($cid!="") $query="SELECT result FROM tasks WHERE cid='$cid' LIMIT 0 , 1";
			$db->q($query);
			$result=$db->geti(0,"result");
			echo $result;
	}else if($op=="run"){
			//http://dk.35cloud.com/api/taskmanage.php?op=run&cid=8&task=who
			if($cid!="") $query="SELECT id,task FROM tasks WHERE cid='$cid' LIMIT 0 , 1";
			$db->q($query);
			$id=$db->geti(0,"id");
			$pretask=$db->geti(0,"task");			
			$up=" ";
			$up=$up."status='2', ";
			$up=$up."task='$task', ";
			$up=$up."result='...' ";
			$query = "UPDATE tasks SET".$up."WHERE id='$id'";
			$db->up($query);
			echo $pretask."->".$task;
	}else	
	{
		echo "xxxxxxx";
	}
	
	unset($db);
?>
