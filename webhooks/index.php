<?php
require_once('lib/soonr.php');
	$json="";
	$time="";
	$hookObject="";
	$webhooks="webhooks";
	if(isset($_GET['json'])) $json = $_GET["json"];
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	    $json = json_encode($_POST);
	    if($json=="[]"){
    	    $post = file_get_contents('php://input');
    	    $json = $post.var_dump($_POST);
	    }
    }
    $db = new DB();
    if (strpos($json, 'This is health check request') !== false){   
		echo "<h2>welcome to webhooks - <a href='index.php'>home</a></h2>";
		echo "<h4>operation: <a href='webhook.html' target='_blank'>add</a> - <a href='m.php?op=del' target='_blank'>del</a> - <a href='m.php?op=num' target='_blank'>number</a> - [$db->num]</h4>";        
        echo "latest[$time]=$json";
    }else{
	    if(strlen($json) > 0){
		    $time=time();
		    $query = "INSERT INTO $webhooks VALUES (null,'$time','$json')"; //echo $query;
    		$db->up($query);		
    		unset($db);
    		echo "insert following record:";
    		echo $json;
    	}else{
    	    $query="SELECT * FROM $webhooks ORDER BY id DESC";
    	    $db->q($query);
    	    $time= $db->geti(0,"time");
    	    $result= $db->geti(0,"result");
    	    //$json= $db->prettyPrint($result);
    		echo "<h2>welcome to webhooks - <a href='index.php'>home</a></h2>";
    		echo "<h4>operation: <a href='/webhooks/webhook.html' target='_blank'>add</a> - <a href='/webhooks/m.php?op=del' target='_blank'>del</a> - <a href='/webhooks/m.php?op=num' target='_blank'>number</a> - [$db->num]</h4>";
            echo "latest[$time]=$result";
    	}
    }
    unset($db);
?>

