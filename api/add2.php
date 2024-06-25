<html>
<head>
<style>
#myDIV {
    width: 400px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style> 
<TITLE>Add</TITLE> 
</head>
<body>
<center>
<h1> Add ...</h1>
<h2> <a href="add2.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> </h2>
<div id='myDiv'>
<form action="add.php" method="post">
IP address: <input type="text" name="ip"><br>
Port: <input type="text" name="port"><br>
HostName: <input type="text" name="name"><br>
OS: <input type="text" name="os"><br>
JAR: <input type="text" name="jar"><br>
Status: <input type="text" name="status"><br>
TimeStamp: <input type="text" name="time"><br>
Secret: <input type="text" name="secret"><br>
<input type="Submit">
</form>
</div>
</center>
</body>
</html>