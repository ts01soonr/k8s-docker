<?php
 
class DB{
	
  public $rc;
  public $result;
  public $num;
  public $isOK=false;
  public function __construct()
  {
    //example-db-setting
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
function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }
}
//$t = new Telnet('ghost10','23');
//echo $t->SR('ps fwef')."<br/>";
//unset($t);
?>