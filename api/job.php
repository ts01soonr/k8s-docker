<?php   ob_start();   session_start();
if (!isset($_SESSION['username2'])) header("Location:index.php");

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
?>
<html>
<head>
<style>
#myDIV {
    width: 400px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
#myF {
    width: 400px;
    padding: 10px;
    text-align: left;
    background-color: lightblue;
}
</style> 
<TITLE>Job</TITLE>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: 'exec.php',
            dataType: 'text',
            data: $('form').serialize(),
            beforeSend: function() {
                $('#submit').attr('disabled', true);
                $('#myDIV').html('<span class="wait"><img src="/ajax-loader.gif" alt="" /></span>');
            },
            complete: function() {
                $('#submit').attr('disabled', false);
                $('.wait').remove();
            }, 
            success: function ( data ) {
            	//var txt=data.text;
              //alert(data);
               $('#myDIV').html(data);
            }
          });

        });

      });
    </script> 
</head>
<body>
<center>
<h1> run it on single device</h1>
<h2> <a href="add.php">Add</a> - <a href="<?php echo $remote; ?>">Remote</a> - <a href="check.php"> Check</a> - <a href="job.php">Job</a> - <a href="list.php"> List</a> - <a href="task.php">Task</a>  - <a href="res">Setup</a> </h2>
<div id="myF">
<form >
IP address: <input type="text" name="ip" value = "<?php  echo $ip;?>"> - Port: <input type="text" name="port" size="5" value = "<?php  echo $port;?>"><br><br>
Command:<br> <textarea rows="10" cols="50" name="cmd"  ID="cmd" ><?php echo $cmd;?></textarea>  <br/><br/>
<input type="checkbox" name="s" value="1" <?php if($s=='1') echo "checked"; ?> >Save output in <a href="results.php" target="_blank">DB</a><br/> <br/>
<input type="Reset" value="Reset"> - <input id="submit" name="submit" type="Submit" value="Submit">
</form>
</div>
<br>
<div id="myDIV"><?php  echo date('Y/m/d H:i:s', time());?></div>
</center>
</body>
</html>