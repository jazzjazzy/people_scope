<?php
/**
 * Database Class, 
 * <br />
 * This class is Database abstraction layer<br /> 
 * 
 * Example:<br />
 * <br />
 * define('DB_USER','root');<br />
 * define('DB_PASS','password');<br />
 * define('DB_HOST','localhost');<br />
 * define('DB_DBASE','test_db');<br />
 * define('DB_TYPE','mysql');<br />
 * <br />
 * try{$db = new db();}<br />
 * catch(CustomException e){ echo $e->logError(); }<br />
 * <br />
 * try{$result = $db->select('select * from test_table');}<br />
 * catch(CustomException e){echo $e->queryError();}<br />
 * <br />
 * foreach($result AS $row){<br />
 * 		echo 'col1 ='.$row['col1'];<br />
 * 		echo 'col2 ='.$row['col2'];<br />
 *		echo 'col3 ='.$row['col3'];<br />
 * }<br />
 * <br />
 * 
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 */

if (isset($_SESSION['dbaccess']['server_host'])) {
	$DB_HOST = $_SESSION['dbaccess']['server_host'];
}else{
	$DB_HOST = DB_HOST;
}

if (isset($_SESSION['dbaccess']['server_database'])) {
	$DB_DBASE = $_SESSION['dbaccess']['server_database'];
}else{
	$DB_DBASE = DB_DBASE;
}

if (isset($_SESSION['dbaccess']['server_type'])) {
	$DB_TYPE = strtolower($_SESSION['dbaccess']['server_type']);
}else{
	$DB_TYPE = DB_TYPE;
}

if (isset($_SESSION['dbaccess']['server_port'])) {
	$DB_TYPE = $_SESSION['dbaccess']['server_port'];
}else{
	$DB_PORT = DB_PORT;
}

/**
 * required general tool 
 */
require_once 'tools.function.php';
/**
 * required error handler 
 */
require_once 'errorhandler.class.php';


/**
 * This class is Database abstraction layer
 * 
 * @package PeopleScope
 * @subpackage Base
 */
class db {
	
	var $dsn; 
	var $dbh;
	var $lastQuery;
	
	
	/**
	 * constructor to initiate database conection 
	 * 
	 * @return Void
	 */
	function __construct(){
		
		$this->dsn = DB_TYPE.':dbname='.DB_DBASE.';host='.DB_HOST.';port='.DB_PORT;
		try{
			try {
				$this->dbh = new PDO($this->dsn, DB_USER, DB_PASS);
			} catch (PDOException $e) {
				throw new CustomException('Connection Error '.$e->getMessage());
			}
		}catch(CustomException $e) {
			echo $e->logError();
		}
	}
	
	/**
	 * returns current DSN string used to connect ot the server 
	 * 
	 * @return String
	 */
	public function getDNSString(){
		return $this->dsn;
	}
	
	/**
	 * Will instigate a SELECT query and return and an array of responses 
	 * 
	 * @param String $sql select sql string to be executed 
	 * @return Array
	 */
	public function select($sql){
		
		try{
			$stmt = $this->query($sql);
		}catch(CustomException $e){
			throw new CustomException('SELECT : '. $e->getMessage());
		}
		
		return $stmt->fetchAll(PDO::FETCH_NAMED);
		
	}
	
	/**
	 * Will instigate a INSERT query and return the inserts autocomplete Id;
	 * 
	 * @param String $sql
	 * @return Array
	 */	
	public function insert($sql, $exec = NULL){
		
		try{
			$stmt = $this->query($sql, $exec);
		}catch(CustomException $e){
			throw new CustomException('INSERT : '. $e->getMessage());
		}
		
		return $this->dbh->lastInsertId();
		    
	}

	/**
	 * Will instigate a DELETE query and return true if no problems;
	 * 
	 * @param string $sql
	 * @return Array
	 */	
	public function delete($sql){
		
		try{
			$stmt = $this->query($sql);
		}catch(CustomException $e){
			throw new CustomException('DELETE : '. $e->getMessage());
		}
		
		return true;
	}
	
	/**
	 * Will instigate a UPDATE query and return true if no problems;
	 * 
	 * @param string $sql
	 * @return array
	 */	
	public function update($sql, $exec=NULL){

		try{
			$stmt = $this->query($sql, $exec);
		}catch(CustomException $e){
			throw new CustomException('UPDATE : '. $e->getMessage());
		}
		
		return true;
	}
	
	/**
	 * Will instegate a query and return a recordset;
	 * 
	 * @param string $sql
	 * @return array
	 */	
	public function query($sql, $exec=NULL){
		$this->lastQuery = $sql;

		try{
			$stmt = $this->dbh->prepare($sql);
		}catch(CustomException $e){
			throw new CustomException('QUERY : '. $e->getMessage());
		}

		if(empty($exec)){
			$stmt->execute();
		}else{
			$stmt->execute($exec);
		}
		
		if ($stmt->errorCode() != 00000 )
		{
			if(empty($exec)){
				$error = $stmt->errorInfo();
			}else{
				$error = $stmt->errorInfo();
				$error .= $stmt->debugDumpParams();
			}
		  	$error = $stmt->errorInfo();
		  	throw new CustomException($error[2]);
		}

		return $stmt;
	}
	
	/**
	 * Merge query template with array 
	 * 
	 * Query template and array set merger function 
	 * Will taken in the Query string template and the array of query elements
	 * and combine the to show the fully converted string used in the prepare 
	 * Note: only use as example for debugging purposes
	 *  
	 * @param string $query Query template  
	 * @param array $params Array of inputs 
	 * @return string
	 */
	public function prepareToQuery($query, $params){
		$keys = array();

	    # build a regular expression for each parameter
	    foreach ($params as $key => $value) {
	        if (is_string($key)) {
	            $keys[] = '/'.$key.'/';
	        } else {
	            $keys[] = '/[?]/';
	        }
	    }
	
	    $query = preg_replace($keys, $params, $query, 1, $count);
	
	    return $query;
	}
	
} 

?>