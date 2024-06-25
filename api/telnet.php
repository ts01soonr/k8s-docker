<?php   ob_start();   session_start();
if (!isset($_SESSION['username2'])) header("Location:index.php");
?>

<HTML> 
<HEAD> 
<style>
#myDIV {
    width: 600px;
    padding: 10px;
    text-align: left;
    background-color: lightblue; 
}

#myF {
    width: 420px;
    padding: 10px;
    text-align: center;
    background-color: lightblue;
}
</style>
<TITLE>AEM</TITLE> 
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="lib/my.js"></script>
</HEAD> 
</BODY>
<center>

<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
require_once('lib/soonr.php');
$t=ini_get("default_socket_timeout")*10;
$id='';
$ip='';
$port='';
$os='';
$cmd='';
$res='';
$refresh='telnet.php';
$job='job.php';
if(isset($_GET['id'])) $id = $_GET["id"];
if(isset($_GET['ip'])) $ip = $_GET["ip"];
if(isset($_GET['port'])) $port = $_GET["port"];
if(isset($_GET['os'])) $os = $_GET["os"];
if(isset($_GET['cmd'])) $cmd = $_GET["cmd"];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){ 
    $id=$_POST['id'];
	$ip=$_POST['ip'];
	$port=$_POST['port'];
	$cmd=$_POST['cmd'];
	$os =$_POST['os'];
	$tel = new Telnet($ip,$port,$t);
	$res = $tel->SR($cmd)."<br/>";
    unset($tel);
}
$os=substr($os,0,3); // win or mac
if( strlen($id) > 0 )
	$refresh = $refresh.'?id='.$id.'&ip='.$ip.'&port='.$port.'&os='.$os;
if( strlen($ip) > 0 && strlen($port))
	$job = $job.'?ip='.$ip.'&port='.$port;
?>
<h1> <a href= "<?php echo $refresh ;?>"><img src="img/connection.jpg" /></a></h1>
<h2> <a href="add.php">Add</a> - <a href="check.php">Check</a> - <a href="<?php echo $job; ?>">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>

<?php
if( strlen($id) > 0 ) {
echo " - <a href='edit.php?id=".$id."'> Edit </a>" ;
echo " - <a href='dm.php?id=".$id."'> Details </a> " ;

}
?>

 </h2>
<div id="myF">
<form id="fm" >
<input type="hidden" name="id" value = "<?php echo $id;?>">
IP_Port: <input type="text" name="ip" size="28" value = "<?php echo $ip;?>"> - <input type="text" name="port" size="6" value = "<?php echo $port;?>"> <br/> <br/>
Operation:
<select onchange='change(this)' id="action2agent" >
  <option value="" selected>-select-</option>
  <?php if($os == 'win') echo "<option value='ps all'>AEM monitor</option>"; ?>
  <option value="version aeb">version aeb</option>
  <option value="checkagent aeb">checkagent aeb</option>
  <option value="start aeb">start aeb</option>
  <option value="stop aeb">stop aeb</option>
  <option value="version awp">version awp</option>
  <option value="checkagent awp">checkagent awp</option>
  <option value="start awp">start awp</option>
  <option value="stop awp">stop awp</option>
</select>  - 
Remote C:
<select onchange='change(this)' id="action" >
  <option value="" selected>-select-</option>
  <option value="os">check os</option>
  <option value="sleep">sleep</option>
    <option value="wakeup">wakeup</option>
  <option value="logout">logout</option>
  <option value="shutdown">shutdown</option>
  <option value="reboot">reboot</option>
</select>

<br/><br/>
<textarea rows="10" cols="50" name="cmd"  ID="cmd" ><?php echo $cmd;?></textarea>  <br/><br/>

<input type="Reset" value="Reset"> - <input id="submit" name="submit" type="button" value="Submit" onClick='submitfrm()' >

<br/>
<div id="URLdiv" style="display:none;">
<br/>
<a href="https://archive.soonr.io/" target="_blank">URL</a>: <input type="text" name="URL" id="URL" size ="50"> <br/>
</div> 
<br/>
<div id="TeamKey" style="display:none;">
TeamKey: <input type="text" name="TK" id="TK" size ="22"> [optional] <br/> 
</div>
<br/>
<div id="extra" style="display:none;">
InstallType: <input type="text" name="SRV" id="SRV" size ="5"> -
Ver: <input type="text" name="VER" id="VER" size ="5" > [optional]<br/><br/>

</div>
AEM's 
<select id="op" onclick= "change3()" >
  <option value="" selected>---select---</option>
  <option value="i">Install</option>
  <option value="x">Uninstall</option>
  <option value="d">Download</option>
  <option value="di">Down&Install</option>
</select> -

<select id="agent" >
  <option value="" selected>-agent-</option>
  <option value="a">AEB</option>
  <option value="b">BSA</option>
  <option value="f">FSA</option>
  <option value="w">AWP</option>
</select> - <b><?php echo "$os";?></b>

<select id="os" name="os" <?php if(strlen($os) > 0) echo "style='display:none;'" ;?> >
  <option value="" >-OS-</option>
  <option value="win" <?php if($os == 'win'){echo("selected");}?>>Win</option>
  <option value="mac" <?php if($os == 'mac'){echo("selected");}?>>Mac</option>
</select> 
- <input type="button" value=">>" onClick='change2(this)' >
</form> 
</div></br>
<div id="myDIV"><?php echo $res;?></div>

<a href="add.php">Add</a> - <a href="check.php">Check</a> - <a href="<?php echo $job; ?>">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>

<?php
if( strlen($id) > 0 ) {
echo " - <a href='edit.php?id=".$id."'> Edit </a>" ;
echo " - <a href='dm.php?id=".$id."'> Details </a>" ;

}
?>
</center>
</BODY>
</html>