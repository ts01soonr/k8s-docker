<?php 
require_once('lib/soonr.php');
$t=36000; // 10 hours socket session
$res=".....";
$res2=".....";
$ip='';
$port='';
$cmd='';
$s=''; // s=1, save records
if(isset($_GET['ip'])) $ip = $_GET["ip"];
if(isset($_GET['port'])) $port = $_GET["port"];
if(isset($_GET['cmd'])) $cmd = $_GET["cmd"];
if(isset($_GET['s'])) $s = $_GET["s"];
if ($_SERVER['REQUEST_METHOD'] == 'POST'){   	
	$ip=$_POST['ip'];
	$port=$_POST['port'];
	$cmd=$_POST['cmd'];
	if(isset($_POST['s'])) 
		$s=$_POST['s'];
} 
if( $ip!='' && $port!='' && $cmd!=''){
	$tel = new Telnet($ip,$port,$t);
	$start= time();	
	$res = $tel->SR($cmd);
	if (strpos($res, "'") !== false) 
		$res=str_replace("'", "`", $res);
	if($s=='1'){  //save result
		$db = new DB();
		$end= time();
		$query = "INSERT INTO results VALUES ('','$ip','$port','$start','$cmd','$res','$end')";
		$db->up($query);
		unset($db);
	}
	unset($tel);
}else
	$res= ' missing paramaters ';
echo $res;
?>
