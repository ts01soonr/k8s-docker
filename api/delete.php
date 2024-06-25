
<html>
<head>
<TITLE>delete</TITLE> 
</head>
<body>
<h1> Delete</h1>
<?php
require_once('lib/soonr.php');

$id=$_GET['id'];
$db = new DB();	
$query="DELETE FROM computers WHERE id='$id'";
$db->up($query);
unset($db);
echo $id."Record delete";
?>

<h2> <a href="add.php">Add</a> - <a href="list.php">List</a> </h2>
</body>
</html>
