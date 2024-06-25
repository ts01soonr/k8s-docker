
<html>
<head>
<style>
#myDIV {
    width: 500px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
#myF {
    width: 500px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style>
<TITLE>Results</TITLE>
<SCRIPT LANGUAGE="JavaScript" SRC="jquery.js">
</SCRIPT>
<script type="text/javascript">
	function _ask( cmd ){
		   
	  var r=confirm("Are you sure for removing this record ?" );
	  if (r==true)
	  { 
		   $.get(cmd)      	       	      
		   sleep(1000);
		   window.open("results.php"); 		 
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
<body>
<center >
<h1><a href="/index.php"> <img src="img/computers.png" /> </a></h1>
<?php
require_once('lib/soonr.php');
$db = new DB();
$query="SELECT * FROM results";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){   	
	$_ip=$_POST['_ip'];
	$_port=$_POST['_port'];
	$_command=$_POST['_command'];
	$query= $query." where ip like '$_ip' && port like '$_port' && command like '$_command'";
	//echo $query;
}
$query= $query ." ORDER BY id DESC LIMIT 0 , 30";
$db->q($query);
?>
<h2> <a href="add.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - 
<a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> - <a href="results.php"> <img src="/refresh.png" /> </a> </h2>
<form action="results.php" method="post">
<div id="myDiv">
IP:<input type="text" name="_ip" size = "16" value="%">
Port:<input type="text" name="_port" size = "4" value="%">
Command:<input type="text" name="_command" size = "10" value="%"> &nbsp;  <input type="submit" value="Filter"><br>
</div><br>
</form>
<table border="0" cellspacing="2" cellpadding="2" style="font-size:100%;">
<tr>
<th><font face="Arial, Helvetica, sans-serif">ID[<?php echo $db->num;?>]</font></th>
<th><font face="Arial, Helvetica, sans-serif">IP</font></th>
<th><font face="Arial, Helvetica, sans-serif">Port</font></th>
<th ><font face="Arial, Helvetica, sans-serif">Command</font></th>
<th><font face="Arial, Helvetica, sans-serif">Output</font></th>
<th><font face="Arial, Helvetica, sans-serif">Start</font></th>
<th><font face="Arial, Helvetica, sans-serif">End</font></th>
<th><font face="Arial, Helvetica, sans-serif">Action</font></th>
</tr>

<?php
$i=0;
while ($i < $db->num) {
	$id=$db->geti($i,"id");
	$ip=$db->geti($i,"ip");
	$port=$db->geti($i,"port");
	$command=$db->geti($i,"command");
	$start=$db->geti($i,"start");
	$out=$db->geti($i,"output")."-------------";
	$end=$db->geti($i,"end");
	if(strlen($command)>20) $command= substr($command,0,17)."...";
	if($i%2==1)
		 echo '<tr bgcolor="#FFFFFF">'; 
	else 
		 echo '<tr bgcolor="#99FFCC">';
	//$remote= 'telnet.php?id='.$id.'&ip='.$ip.'; 
?>

<td align='center' ><a href="result.php?id=<?php echo $id;?>"><?php echo $id; ?></a></td>
<td><?php echo $ip; ?></td>
<td><?php echo $port; ?></td>
<td><?php echo nl2br($command); ?></td>
<td><?php echo substr($out,0,40)."..."; ?></td>
<td><?php echo date('H:i:s', $start)?></td>
<td><?php echo date('H:i:s', $end)?></td>
<td><a href="#" onclick='_ask("result.php?del=1&id=<?php echo $id;?>")'> Delete </a></td>
</tr>

<?php
	$i++;
}
unset($db);
?>

</table>
</center>
</body>
</html>
