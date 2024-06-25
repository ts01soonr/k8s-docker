<?php
 
/**
 * A simple class
 *
 * This is the long description for this class,
 * which can span as many lines as needed. It is
 * not required, whereas the short description is
 * necessary.
 *
 * It can also span multiple paragraphs if the
 * description merits that much verbiage.
 *
 * @author Jason Lengstorf <jason.lengstorf@ennuidesign.com>
 * @copyright 2010 Ennui Design
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 * #php7.4
 */

//echo $_SESSION['username2'];
//if($_SESSION['username2'] !=="soonr") header('Location:/index.php');

class Telnet{
  /**
   * A public variable
   *
   * @var string stores data for the class
   */
  public $ip;
  public $port;
  public $msg;
  public $S;  // socket
  public $rc;
  /**
   * Sets $foo to a new value upon class instantiation
   *
   * @param string $val a value required for the class
   * @return void
   */
  public function __construct($ip,$port,$tm)
  {
      $this->ip = $ip;
	  $this->port = $port;
	  $this->rc = @fsockopen($ip, $port, $errno, $errstr,$tm); 
	  if (is_resource($this->rc)){
		stream_set_timeout($this->rc, $tm);	
		$this->msg = $this->read()."<br/><br/>";
	  }
	  else		 
		$this->msg = "!!! It seems there is no connection to ".$ip.":".$port." !!!! <br/>";
  }
  public function __destruct()
  {
	  if (is_resource($this->rc)) fclose($this->rc); 
  }
 
  public function isOK()
  {
	  return (bool) is_resource($this->rc);
  }
  public function send($var)
  {
	  if (is_resource($this->rc))
		fputs($this->rc, $var."\r\n");
  }
  public function read()   // read single line
  {
	  if (is_resource($this->rc))
		return fgets($this->rc, 128);
  }
  public function reads()   // read all line
  {
	 
	  $line= $this->read();
	  $msg= $line."<br/>";;
	  while (strlen($line)) {
		  
		$line = $this->read();	
		$msg = $msg . $line ."<br/>";
		
	  }
      return $this->msg.$msg;	  
  }
  
  public function dis()
  {
	  $this->send('bye');
  }
  public function SR($var)     // combine reader and writer
  {
	  $this->send($var);
	  $this->dis();
	  return $this->reads();
  }
}

class DB{
	
  public $rc;
  public $result;
  public $num;
  public $isOK=false;
  public function __construct()
  {
	  $servername = "mysql";
	  $username = "root";
	  $password = "test";
	  $dbname = "myDb";
	  $this -> isOK = false;
	  $mysqli = new mysqli($servername, $username, $password, $dbname );
	  // Check connection
	  //echo "construct";
	  if ($mysqli -> connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
		exit();
	  }	else {
		$this -> rc =  $mysqli;
		//echo "connect ok";
		$this -> isOK = true;
	  }
  }
  public function __destruct()
  {
	  //echo "XXXX";
	  $this -> isOK = false;
	  $this -> rc -> close();
  }
  public function isOK()
  {
	  return $this -> isOK;
  }
  public function q($query)  // query;
  {
		if ( $result = $this -> rc -> query( $query ) ) {
		  //echo "---result--" . $result -> num_rows;
		  $this->num = $result -> num_rows;
		  $this -> result = $result;
		  return $result;
		}
  }
  public function up($query)  // update result;
  {
	  	$this -> rc -> query( $query );		
  }
  public function get($v)
  {
		if($this->num==1){
			$this -> result -> data_seek(0); 
			return $this -> result -> fetch_array()[$v];
		}
		return '-null-';
  }
  public function geti($i,$v)
  {
		$this -> result -> data_seek($i); 
		return $this -> result -> fetch_array()[$v];
  }  

}
// sortby
function sortby($s,$o) {
	
    switch($s){	
		case 't':
			if($o=='') return " ORDER BY time DESC";
			if($o=='d') return " ORDER BY time DESC";
			if($o=='u') return " ORDER BY time ASC";
			return " ORDER BY time";
		case 'n':
			if($o=='') return " ORDER BY name DESC";
			if($o=='d') return " ORDER BY name DESC";
			if($o=='u') return " ORDER BY name ASC";
			return " ORDER BY name";
		case 'j':
			if($o=='') return " ORDER BY jar DESC";
			if($o=='d') return " ORDER BY jar DESC";
			if($o=='u') return " ORDER BY jar ASC";
			return " ORDER BY jar";
		case 's':
			if($o=='') return " ORDER BY status DESC";
			if($o=='d') return " ORDER BY status DESC";
			if($o=='u') return " ORDER BY status ASC";
			return " ORDER BY status";	
		case 'os':
			if($o=='') return " ORDER BY os DESC";
			if($o=='d') return " ORDER BY os DESC";
			if($o=='u') return " ORDER BY os ASC";
			return " ORDER BY os";	
		default:
			return " ";
	}
}	
//$t = new Telnet('ghost10','23');
//echo $t->SR('ps fwef')."<br/>";
//unset($t);
?>
