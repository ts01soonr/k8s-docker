<?php
session_start();
if (isset($_SESSION['username2'])){
	$ani = $_SESSION['username2'];   // cat
	$len=strlen($ani);
	if( $len > 0) {
?>
<html>
<SCRIPT LANGUAGE="JavaScript" SRC="jquery.js">
</SCRIPT>
<script type="text/javascript">
	function _ask( cmd ){
		   
	  var r=confirm("Are you sure for removing this device ?" );
	  if (r==true)
	  { 
		   $.get(cmd)      	       	      
		   sleep(1000);
		   window.open("list.php"); 		 
	  }
								
	}
	function _back2task( id ){
		window.location.href="tasklist.php?cid="+id;
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
<style>
#myDIV {
	width: 800px;
	padding: 10px;
	text-align: left;
	background-color: lightblue;
}
</style> 	
<head>
<TITLE>Edit Task</TITLE> 
</head>
<body>
<center>
<h1> Edit Task </h1>
<?php
require_once('lib/soonr.php');

$id=$_GET['id'];
$query="SELECT * FROM tasks where id='$id' ";

$db = new DB();	
$db->q($query);
if ($db->num == 1) {
	$id=$db->get("id");
	$cid=$db->get("cid");
	$task=$db->get("task");
	$status=$db->get("status");
	$result=$db->get("result");
}
unset($db);
?>
<h2> <a href="taskadd.php">New Task</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="#" onclick='_ask("delete.php?id=<?php echo $id;?>")'> Delete </a>  - <a href="dm.php?id=<?php echo $id;?>">Details</a></h2>
<div id="myDIV">
<form action="taskmanage.php" method="post">
<input type="hidden" name="op" value="update"> 
Task ID: <input name="id" size="4" value="<?php echo $id; ?>"> For 
Computer ID: <input name="cid" size="4" value="<?php echo $cid; ?>"><br/> <br/>
Task & Status: 
<select id="status" name="status" >
  <option value="2" <?php if($status == '2'){echo("selected");}?>>Re-Run</option>
  <option value="1" <?php if($status == '1'){echo("selected");}?>>Running</option>
  <option value="0" <?php if($status == '0'){echo("selected");}?>>Done</option>
</select>
- <input type="Submit" value="Update">
<br><br>
<textarea rows="8" cols="80" name="task"  ID="task" ><?php echo $task;?></textarea> <br/><br/>
Result: 
<br/><br/>
<textarea rows="20" cols="80" name="result"  ID="result" ><?php echo $result;?></textarea>  <br/><br/>

<button type="button" onclick="_back2task('<?php echo $cid; ?>')">Back to Task List</button>  
</form>
</div>

</center>

</body>
</html>
<?php } } else { ?>
<html lang = "en"><head><title>login</title> <meta http-equiv="refresh" content="0;url=index.php"></head>
<body><center><h1><a href="index.php">Back to login page</a> </h1></center>
</body>
</html>
<?php } ?>
