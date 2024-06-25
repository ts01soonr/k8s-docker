
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
    width: 400px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style> 	
<head>
<TITLE>Edit </TITLE> 
</head>
<body>
<center>
<h1> Edit </h1>
<?php
require_once('lib/soonr.php');

$id=$_GET['id'];
$query="SELECT * FROM computers where id='$id' ";

$db = new DB();	
$db->q($query);
if ($db->num == 1) {
	$ip=$db->get("ip");
	$port=$db->get("port");
	$name=$db->get("name");
	$os=$db->get("os");
	$jar=$db->get("jar");
	$status=$db->get("status");
	$time=$db->get("time");
	$secret=$db->get("secret");
}
unset($db);
?>
<h2> <a href="add.php">Add</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="#" onclick='_ask("delete.php?id=<?php echo $id;?>")'> Delete </a>  - <a href="dm.php?id=<?php echo $id;?>">Details</a>
-<a href="telnet.php?<?php echo 'id='.$id.'&ip='.$ip.'&port='.$port.'&os='.$os;?>"><img src='img/<?php echo $os; ?>.jpg' title = 'remote' /></a></h2>
<div id="myDIV">
<form action="update.php" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>">
IP: <input type="text" name="ip" value="<?php echo $ip; ?>"><br>
Port: <input type="text" name="port" value="<?php echo $port; ?>"><br>
HostName: <input type="text" name="name" value="<?php echo $name; ?>"><br>
OS: <input type="text" name="os" value="<?php echo $os; ?>"><br>
JAR: <input type="text" name="jar" size="30" value="<?php echo $jar;?>"><br>
Status: <input type="text" name="status" value="<?php echo $status; ?>"><br>
TimeStamp: <input type="text" name="time" value="<?php echo $time; ?>"><br> <br>
Secret: <input type="text" name="secret" value="<?php echo $secret; ?>"><br> <br>
<input type="Submit" value="Update">
</form>
</div>

</center>

</body>
</html>
