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
<h2> <a href="add.php">Add</a> - <a href="telnet.php">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> </h2>
<div id='myDiv'>
<form action="taskmanage.php" method="post" name="task">
Operation: <input type="text" name="op"><br>
Task Id: <input type="text" name="id"><br>
Computer Id: <input type="text" name="cid"><br>
Task: <input type="text" name="task"><br>
Status: <input type="text" name="status"><br>
Start: <input type="text" name="start"><br>
End: <input type="text" name="end"><br>
Result: <br> <input rows="20" cols="40" name="result"  ID="result" ></input>  <br/><br/>
<input type="Submit" name="submit">
</form>
</div>
</center>
</body>
</html>