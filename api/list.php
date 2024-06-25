<?php
session_start();
if (isset($_SESSION['username2'])){
	$ani = $_SESSION['username2'];   // cat
	$len=strlen($ani);
	if( $len > 0) {
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
	<TITLE>My Computers</TITLE>
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
	</head>
	<body>
	<center >
	<h1><a href="/index.php"> <img src="img/computers.png" /> </a> </h1>
	<?php
	require_once('lib/soonr.php');
	$s="";
	$o="";
	if(isset($_GET['s'])) $s = $_GET["s"];
	if(isset($_GET['o'])) $o = $_GET["o"];
	$db = new DB();
	$query="SELECT * FROM computers";
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){   	
		$_ip=$_POST['_ip'];
		$_port=$_POST['_port'];
		$_name=$_POST['_name'];
		$_os=$_POST['_os'];
		$_s=$_POST['_s'];
		$query= "SELECT * FROM computers where ip like '$_ip' && port like '$_port' && name like '$_name' && os like '$_os' && status like '$_s' ";
	}
	$db->q($query.sortby($s,$o));
	if($o=='d') $o='u';
	else if($o=='u') $o='d';
	else if($o=='') $o='d';
	?>
	<h2> 
	<a href="add.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - 
	<a href="tasklist.php">ALL task</a> - </a> <a href="task.php">Task</a> - <a href="list.php"> <img src="img/refresh.png" /> </a>
	</h2>
	<form action="list.php" method="post">
	<div id="myDiv">
	IP:<input type="text" name="_ip" size = "16" value="%">
	Port:<input type="text" name="_port" size = "4" value="%">
	Name:<input type="text" name="_name" size = "10" value="%">
	OS:<select name="_os">
	  <option value="%">---</option>
	  <option value="%win%">Win</option>
	  <option value="%mac%">Mac</option>
	  <option value="linux">Linux</option>
	</select>
	Status:<select name="_s">
	  <option value="%">---</option>
	  <option value="Online">Online</option>
	  <option value="Offline">Offline</option>
	</select>    <input type="submit" value="Filter"><br>
	</div><br>
	</form>
	<table border="0" cellspacing="2" cellpadding="2" style="font-size:100%;">
	<tr>
	<th><font face="Arial, Helvetica, sans-serif">ID[<?php echo $db->num;?>]</font></th>
	<th><font face="Arial, Helvetica, sans-serif">IP-Port</font></th>
	<th><font face="Arial, Helvetica, sans-serif">Secret</font></th>
	<th><font face="Arial, Helvetica, sans-serif">Name<a href='list.php?s=n<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Action</font></th>
	<th><font face="Arial, Helvetica, sans-serif">OS<a href='list.php?s=os<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">JAR<a href='list.php?s=j<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Status<a href='list.php?s=s<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Heartbeat<a href='list.php?s=t<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	</tr>

	<?php
	$i=0;
	while ($i < $db->num) {
		$id=$db->geti($i,"id");
		$ip=$db->geti($i,"ip");
		$port=$db->geti($i,"port");
		$name=$db->geti($i,"name");
		$os=$db->geti($i,"os");
		$jar=$db->geti($i,"jar")."-------------";
		$status=$db->geti($i,"status");
		$time=$db->geti($i,"time");
		$secret=$db->geti($i,"secret");
		if($i%2==1)
			 echo '<tr bgcolor="#FFFFFF">'; 
		else 
			 echo '<tr bgcolor="#99FFCC">';
		$remote= 'telnet.php?id='.$id.'&ip='.$ip.'&port='.$port.'&os='.$os; 
		$job= 'job.php?ip='.$ip.'&port='.$port; 
		if((time()-$time)>21600) $status="Offline"; // display offline it is more than 6 hours
	?>

	<td align='center' ><a href="edit.php?id=<?php echo $id;?>"><?php echo $id; ?></a></td>
	<td><?php echo $ip.":".$port; ?></td>
	<td><?php echo $secret; ?></td>
	<td><?php echo "<a href='tasklist.php?cid=".$id."'>". $name. "</a>" ; ?></td>
	<td>
	<a href="<?php echo $remote;?>"> remote </a>
	<?php if($port=='23') echo " -[23]" ; ?>
	</td>
	<td><a href="<?php echo $job;?>"><img src='img/<?php echo substr($os,0,8); ?>.jpg'></a></td>
	<td><?php echo substr($jar,0,12); ?></td>
	<td align="center"><img src='img/<?php echo $status; ?>.jpg'></td>
	<td><?php echo date('Y/m/d H:i:s', $time+3600);?></td>
	</tr>

	<?php
		$i++;
	}
	unset($db);
	?>
	</table>
	<br/>
	<a href="qr.hY4tS.html">QR-Code</a> - Local time: <?php echo date('Y/m/d H:i:s', time()+3600);?> -<a href="logout.php">logout</a>
	</center>
	</body>
	</html>

<?php } } else { ?>
<html lang = "en"><head><title>login</title> <meta http-equiv="refresh" content="0;url=index.php"></head>
<body><center><h1><a href="index.php">Back to login page</a> </h1></center>
</body>
</html>
<?php } ?>
