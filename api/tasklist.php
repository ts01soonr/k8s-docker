<?php
session_start();
if (isset($_SESSION['username2'])){
	$ani = $_SESSION['username2'];   // cat
	$len=strlen($ani);
	if( $len > 0) {
?>		
	<?php
	require_once('lib/soonr.php');
	$s="";
	$o="";
	$cid="";
	if(isset($_GET['s'])) $s = $_GET["s"];
	if(isset($_GET['o'])) $o = $_GET["o"];
	if(isset($_GET['cid'])) $cid = $_GET["cid"];
	$db = new DB();
	$query="SELECT t.*,c.name FROM tasks t,computers c where t.cid=c.id";
	if(isset($_GET['cid'])) $query=$query." && c.id=$cid";
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){   	
		$_id=$_POST['_id'];
		$_cid=$_POST['_cid'];
		$_task=$_POST['_task'];
		$_s=$_POST['_s'];
		$query= "SELECT t.*,c.name FROM tasks t,computers c where t.id like '$_id' && t.cid like '$_cid' && t.task like '$_task' && t.status like '$_s' && t.cid=c.id";
	}
	$db->q($query.sortby($s,$o));
	if($o=='d') $o='u';
	else if($o=='u') $o='d';
	else if($o=='') $o='d';
	$i=0;
	$isRun=0;
	while ($i < $db->num) {
		$status=$db->geti($i,"status");
		if( $status!="0" ) {
			$isRun=1;
			break;
		}
		$i++;	
	}
	$i=0;
	?>
	<html>
	<head>
	<style>
	#myDIV {
		width: 700px;
		padding: 10px;
		text-align: left;
		background-color: lightblue;
	}
	#myF {
		width: 450px;
		padding: 10px;
		text-align: left;
		background-color: lightblue;
	}
	</style>
	<TITLE>View tasks</TITLE>
	<SCRIPT LANGUAGE="JavaScript" SRC="jquery.js">
	</SCRIPT>
	<script type="text/javascript">

		var secs;
		var timerID = null;
		var timerRunning = false;
		var delay = 1000;

		function InitializeTimer(seconds) {
			// Set the length of the timer, in seconds
			secs = seconds;
			StopTheClock();
			StartTheTimer();
		}

		function StopTheClock() {
			if (timerRunning)
				clearTimeout(timerID);
			timerRunning = false;
		}

		function StartTheTimer() {
			if (secs == 0) {
				StopTheClock();
				// Here's where you put something useful that's
				// supposed to happen after the allotted time.
				// For example, you could display a message:
				window.location.href = window.location.href;
				//alert("Your page has been refreshed !!!!");
			}
			else {
				//self.status = 'Remaining: ' + secs;
				//document.getElementById("lbltime").innerText = secs + " ";
				secs = secs - 1;
				timerRunning = true;
				timerID = self.setTimeout("StartTheTimer()", delay);
			}
		}


		function _reload(){
			sleep(5000);
			var url=window.location.href
			window.location.href = url.replace("#","");	
									
		}
		function _alltask( id ){
			   
		  if (id=="") return;
		  var r=confirm("Are you sure for renew all task for this machine ? - "+ id );
		  if (r==true)
		  { 
			   $.get("taskmanage.php?op=up&status=2&cid="+id)     	       	      
			   _reload();

		  }
									
		}
		function _uptask( id ){
			   
		  var r=confirm("Are you sure for renew this task ? - "+ id );
		  if (r==true)
		  { 
			   $.get("taskmanage.php?op=up&status=2&id="+id)     	       	      
			   _reload();		 
		  }
									
		}
		function _xtask( id ){
			   
		  var r=confirm("Are you sure for removing this task ? - "+ id );
		  if (r==true)
		  { 
			   $.get("taskmanage.php?op=del&id="+id)     	       	      
			   _reload();				 
		  }
									
		}
		
		function _addtask( cid ){
			   
		  var task = prompt("Create a new task", "");
		  if (task != null)
		  { 
			   $.get("taskmanage.php?op=add&cid="+cid+"&task="+task)    	       	      
			   _reload();				 
		  }
									
		}
		function _edittask( id,cmd ){
			   
		  var task = prompt("edit task:"+id, cmd);
		  if (task != null)
		  {    
			   $.get("taskmanage.php?op=up&id="+id+"&task="+task)    	       	      
			   _reload();			 
		  }
									
		}
		function _runtaskat( id,cid ){
		  //rerun the fail task on that computer	   
		  var task = confirm("Confirm to run task:"+id+" on computer:"+cid);
		  if (task != null)
		  {    
			   $.get("taskmanage.php?op=runat&id="+id+"&cid="+cid)    	       	      
			   sleep(5000);
			   window.location.href="tasklist.php?cid="+cid;		   
		  }
									
		}
		function _jar(){
			   
		  var r=confirm("Are you upgrade all soonr.jar ? ");
		  if (r != null)
		  { 
			   $.get("taskmanage.php?op=jar")    	       	      
			   _reload();				 
		  }
									
		}	
		function sleep(milliseconds) {
		  var start = new Date().getTime();
		  for (var i = 0; i < 1e7; i++) {
			if ((new Date().getTime() - start) > milliseconds){
			  break;
			}
		   }
	  }
	</script>	 
	</head>
	<body <?php if($isRun==1) echo "onload='InitializeTimer(10)'";?> >
	<center >
	<h1><a href="/api"> <img src="img/computers.png" /> </a></h1>
	<h2> 
	<a href="addtask.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - 
	<a href="tasklist.php">All task</a> - </a> <a href="task.php">task</a> - <a href="#" onclick='_reload()'> <img src="img/refresh.png" /> </a>
	</h2>
	<form action="tasklist.php" method="post">
	<div id="myDiv">
	ID:<input type="text" name="_id" size = "16" value="%">
	Computer ID:<input type="text" name="_cid" size = "4" value="%">
	Task:<input type="text" name="_task" size = "10" value="%">
	Status:<select name="_s">
	  <option value="%">---</option>
	  <option value="2">new</option>
	  <option value="1">run</option>
	  <option value="0">done</option>
	</select>
	<input type="submit" value="Filter"> - <a href="https://lab6-us.workplace.datto.com/filelink/15c72-159e5f2-118ef14113-2" target="_blank"> Log </a> <br>
	</div><br>
	</form>
	<table border="0" cellspacing="2" cellpadding="2" style="font-size:100%;">
	<tr>
	<th><font face="Arial, Helvetica, sans-serif">ID[<?php echo $db->num;?>]</font></th>
	<th><font face="Arial, Helvetica, sans-serif">Computer<a href='tasklist.php?s=c<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Task</font></th>
	<th><font face="Arial, Helvetica, sans-serif">Result</font></th>
	<th><font face="Arial, Helvetica, sans-serif"><a href="#" onclick='_alltask("<?php echo $cid;?>")'><img src="img/play.png" />Action</a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Status<a href='tasklist.php?s=s<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>

	</tr>

	<?php
	$i=0;
	$isRun=0;
	while ($i < $db->num) {
		$id=$db->geti($i,"id");
		$cid=$db->geti($i,"cid");
		$task=$db->geti($i,"task");
		$status=$db->geti($i,"status");
		$result=$db->geti($i,"result");
		$name=$db->geti($i,"name");
		if($i%2==1)
			 echo '<tr bgcolor="#FFFFFF">'; 
		else 
			 echo '<tr bgcolor="#99FFCC">';
		
		//test result:  false	
		if( $status!=="0" ) $isRun=1;
		if (strpos($result, 'test result:  false') !== false && $status=="0") $status="00";
	?>

	<td align='center' ><a href="taskedit.php?id=<?php echo $id;?>"> [ <?php echo $i."-".$id; ?> ] </a></td>
	<td align='center' ><a href="tasklist.php?cid=<?php echo $cid;?>"><?php echo $name; ?></a></td>
	<td><?php echo substr(nl2br($task),0,120);?> </td>

	<td><?php echo substr(nl2br($result)."...",0,50); ?></td>
	<td align='center' >
		<a href="#" onclick='_addtask("<?php echo $cid;?>")'> add </a>-
	<?php if(strstr($task, PHP_EOL)){  ?>
		<a href="taskedit.php?id=<?php echo $id;?>"> edit </a>-
	<?php }else{ ?>
		<a href="#" onclick='_edittask("<?php echo $id;?>","<?php echo $task;?>")'> edit </a>-
	<?php };?>
		<a href="#" onclick='_uptask("<?php echo $id;?>")'> run </a>-
		<a href="#" onclick='_xtask("<?php echo $id;?>")'> del </a> 
	</td>
	<td align="center">
	<?php if($status === "00" && $cid==="1"){  // give option to run-run fails on that machine 
		$pos = strpos($result, "@[");
		if($pos) $cid=strtok($result, "@[");
	?>
		<a href="#" onclick='_runtaskat("<?php echo $id;?>","<?php echo $cid;?>")'> <img src='img/<?php echo $status; ?>.png'> </a>
	<?php }else{ ?>
		<img src='img/<?php echo $status; ?>.png'>
	<?php };?>	
	</td>
	</tr>

	<?php
		$i++;
	}
	unset($db);
	// if task is all completed, then no refresh
	//if($cid!=""&&$isRun==0)  header("Location:tasklist.php?cid=$cid");
	?>
	</table>

	</center>
	</body>
	</html>


<?php } } else { ?>
<html lang = "en"><head><title>login</title> <meta http-equiv="refresh" content="0;url=index.php"></head>
<body><center><h1><a href="index.php">Back to login page</a> </h1></center>
</body>
</html>
<?php } ?>