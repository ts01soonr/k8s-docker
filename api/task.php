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
		width: 550px;
		padding: 10px;
		text-align: left;
		background-color: lightblue;
	}
	#myF {
		width: 460px;
		padding: 10px;
		text-align: left;
		background-color: lightblue;
	}
	</style>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<TITLE>MultiTask</TITLE>
	<SCRIPT LANGUAGE="JavaScript" SRC="jquery.js">
	</SCRIPT>
	<script type="text/javascript">
		function _ask( cmd ){	   
		  var r=confirm("Are you sure for removing this device ?" );
		  if (r==true)
		  { 
			   $.get(cmd)      	       	      
			   sleep(1000);
			   //window.open("list.php"); 		 
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
		function runcmd( ){
			var inputObj = document.getElementById( "cmd" );
			var checkboxes = document.getElementsByName("ips");
			var save = document.getElementById("save");
			var command = inputObj.value;
			//var cmd=command.replace("\n","%0A");
			var cmd=command.split("\n").join("%0A");
			var url ='exec.php?';
			if(cmd.length > 0) { 
				var r=confirm("Do you want to run task on those selected devices ?" );
				if (r!=true) return;
				var j=0;
				for (var i=0, n=checkboxes.length;i<n;i++) 
				{
					if (checkboxes[i].checked) 
					{
						j++;
						var p = checkboxes[i].value+'&cmd='+cmd;
						if(save.checked) p = p + '&s=1';
						$.get(url+p) 		
						sleep(200);					
					}
				}
				alert ("'" + command +"'  has been pushed on " + j + " devices");
				
			} else 
				alert ( '!!! please input your command !!!' ) ;
			
		}
		function toggle(source) {
		  checkboxes = document.getElementsByName('ips');
		  for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = source.checked;
		  }
		}
	</script>	 
	</head>
	<body>
	<center >
	<h1><a href="/index.php"> <img src="img/computers.png" /> </a></h1>
	<?php 
	require_once('lib/soonr.php');
	$s="";
	$o="";
	if(isset($_GET['s'])) $s = $_GET["s"];
	if(isset($_GET['o'])) $o = $_GET["o"];
	$db = new DB();
	$query="SELECT * FROM computers";
	$cmd = "";
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
	<h2> <a href="add.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - 
	<a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> - <a href="task.php"> <img src="/refresh.png" /> </a> 
	</h2>

	<form action="task.php" method="post">
	<div id="myDiv">
	IP:<input type="text" name="_ip" size = "15" value="%">
	Port:<input type="text" name="_port" size = "4" value="%">
	Name:<input type="text" name="_name" size = "4" value="%">
	<select name="_os">
	  <option value="%">OS</option>
	  <option value="%win%">Win</option>
	  <option value="%mac%">Mac</option>
	</select>
	<select name="_s">
	  <option value="%">Status</option>
	  <option value="Online">Online</option>
	  <option value="Offline">Offline</option>
	</select> <input type="submit" value="Filter"><br>
	</div><br>
	<div id="myF">
	run Command on multi devices:<br> <textarea rows="4" cols="50" name="cmd"  ID="cmd" ><?php echo $cmd;?></textarea>  <br/>
	<input type="checkbox" name="save" value="1" id="save">Save output in <a href="results.php" target="_blank">DB</a><br/><br/>
	<input type="button" value="Play" onClick='runcmd()' > - <button type="reset" value="Reset">Reset</button> 
	</div>
	<br>
	<table border="0" cellspacing="2" cellpadding="2" style="font-size:100%;">
	<tr>
	<th><input type="checkbox" onClick="toggle(this)" /><?php  echo $db->num; ?></th>
	<th><font face="Arial, Helvetica, sans-serif">IP</font></th>
	<th><font face="Arial, Helvetica, sans-serif">Port</font></th>
	<th><font face="Arial, Helvetica, sans-serif">Name<a href='task.php?s=n<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">OS<a href='task.php?s=os<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Status<a href='task.php?s=s<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	<th><font face="Arial, Helvetica, sans-serif">Jar<a href='task.php?s=j<?php echo "&o=$o" ?>'><img src="img/arrows.png" /> </a></font></th>
	</tr>

	<?php 
	$i=0;
	while ($i < $db->num) {
	$id=$db->geti($i,"id");
	$ip=$db->geti($i,"ip");
	$port=$db->geti($i,"port");
	$name=$db->geti($i,"name");
	$os=$db->geti($i,"os");
	$jar=$db->geti($i,"jar")."------";
	$status=$db->geti($i,"status");
	if($i%2==1)
		 echo '<tr bgcolor="#FFFFFF">'; 
	else 
		 echo '<tr bgcolor="#99FFCC">';
	$remote= 'telnet.php?id='.$id.'&ip='.$ip.'&port='.$port.'&os='.$os;
	?>

	<td><input type="checkbox" name="ips" value="<?php  echo 'ip='.$ip.'&port='.$port?>"/></td>
	<td><?php  echo $ip?></td>
	<td><?php  echo $port; ?></td>
	<td><?php  echo "<a target='_blank' href='dm.php?id=".$id."'>". $name. "</a>" ; ?></td>
	<td><a target='_blank' href="<?php  echo $remote;?>"><img src='img/<?php  echo $os; ?>.jpg'></a></td>
	<td align="center"><img src='img/<?php  echo $status; ?>.jpg'></td>
	<td><?php  echo substr($jar,0,10); ?>..</td>
	</tr>

	<?php 
	$i++;
	}
	unset($db);
	?>

	</form>

	</center>
	</body>
	</html>
<?php } } else { ?>
<html lang = "en"><head><title>login</title> <meta http-equiv="refresh" content="0;url=index.php"></head>
<body><center><h1><a href="index.php">Back to login page</a> </h1></center>
</body>
</html>
<?php } ?>