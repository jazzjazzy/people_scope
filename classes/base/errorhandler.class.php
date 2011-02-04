<?php
/**
 * CustomException Class,
 * <br />
 * Generates a custom exception<br />
 *
 * Example:<br />
 *<br />
 * throw an exception <br />
 * <br />
 * if(empty($value)){<br />
 * 		throw new CustomException('value can not be empty');<br />
 * }<br />
 *
 * try/catch<br />
 * <br />
 * try{<br />
 * 		$db->query('SELECT * FROM notable ');<br />
 * }catch(CustomException $e){<br />
 * 		echo $e->logError();<br />
 * }<br />
 *
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 */

/**
 * Generates a custom exception
 *
 * @package PeopleScope
 * @subpackage Base
 */
class CustomException extends Exception{
	
	public function messageError(){
		$str = $this->getMessage()." File: ".$this->getFile()." line: ".$this->getLine();
		return $str;
	}
	
	/**
	 * Generate a formated excption error
	 *
	 * @return string Html Format
	 */
	public function logError(){

		$str = "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; padding:10px; color:#000; background-color: #ffcccc; width:100%; border:solid 1px #000\"><h4>CustomException Error Information</h4>".
			"<div> Message :: ".$this->getMessage()."</div>
			<div>File ::".$this->getFile()."</div>
			<div>Line ::".$this->getLine()."</div></pre>
			</div>";
		
		if(DEBUG){
			$trace = "<pre>".print_r($this->getTrace(),1)."</pre>";
			$str .= "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 8px; padding:10px; background-color: #3399ff; width:100%; border:solid 1px #000\"><h4>Error Back Trace</h4><pre>".
					htmlentities (print_r($trace,1),  ENT_COMPAT) .
					"</pre></div>"; 
		}	
		
		return $str;

	}
	/**
	 * Will append the query being used to the begining of a logError output
	 *
	 * This is used to create an exception error for query execution, to produce
	 * error that also have the query displayed with in the error output
	 *
	 * @param $query Sql query that was being used at the time of execution
	 */
	 public function queryError($query){
		$lines = explode("\n",$query);
		$count = 1;
		$lineQuery = '';
		foreach ($lines AS $line){
			$lineQuery .= str_pad($count++, 3)."|\t".$line."\n";
		}
		echo "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color:#000; padding:10px; background-color: #eecc00; width:100%; border:solid 1px #000\"><h2>SQL Statement</h2><pre>".$lineQuery."</div>";
		echo $this->logError();
	}
}

set_error_handler("exception_error_handler");
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	throw new CustomException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler("myErrorHandler", E_ALL);

function exception_handler($exception) {
	echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}
	
	switch ($errno) {
		case E_ERROR:
		case E_CORE_ERROR :
		case E_USER_ERROR: 
			$str = "<div style=\"border: green 1px solid; font-family: Arial, Helvetica, sans-serif; font-size: 15px; color:#000; padding:10px; background-color: #8AE234; width:100%; border:solid 3px red\"><h4>PHP ERROR:: Error Information[".$errno."]</h4>".
			"<div> Message :: <strong>".$errstr."</strong></div><br />
			<div>File :: ".$errfile."</div>
			<div>Line :: ".$errline."</div></pre>
			</div>";
			if(DEBUG){
				$trace = "<pre>".print_r(debug_backtrace(),1)."</pre>";
				$str .= "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 8px; padding:10px; background-color: #3399ff; width:100%; border:solid 1px #000\"><h4>Error Back Trace</h4><pre>".
						htmlentities (print_r($trace,1),  ENT_COMPAT) .
						"</pre></div>"; 
			}
			return ($str);
			break;
		case E_WARNING:
		case E_CORE_WARNING:
		case E_USER_WARNING:
			$str = "<div style=\"border: green 3px solid; font-family: Arial, Helvetica, sans-serif; font-size: 15px; color:#000; padding:10px; background-color: #8AE234; width:100%; border:solid 3px green\"><h4>PHP WARNING:: Error Information[".$errno."]</h4>".
			"<div> Message :: <strong>".$errstr."</strong></div><br />
			<div>File :: ".$errfile."</div>
			<div>Line :: ".$errline."</div></pre>
			</div>";
			if(DEBUG){
				$trace = "<pre>".print_r(debug_backtrace(),1)."</pre>";
				$str .= "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 8px; padding:10px; background-color: #3399ff; width:100%; border:solid 1px #000\"><h4>Error Back Trace</h4><pre>".
						htmlentities (print_r($trace,1),  ENT_COMPAT) .
						"</pre></div>"; 
			}
			break;
		case E_NOTICE:
		case E_CORE_NOTICE:
		case E_USER_NOTICE:
			$str = "<div style=\"border: green 1px solid; font-family: Arial, Helvetica, sans-serif; font-size: 15px; color:#000; padding:10px; background-color: #8AE234; width:100%; border:solid 3px green\"><h4>PHP NOTICE:: Error Information[".$errno."]</h4>".
			"<div> Message :: <strong>".$errstr."</strong></div><br />
			<div>File :: ".$errfile."</div>
			<div>Line :: ".$errline."</div></pre>
			</div>";
			if(DEBUG){
				$trace = "<pre>".print_r(debug_backtrace(),1)."</pre>";
				$str .= "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 8px; padding:10px; background-color: #3399ff; width:100%; border:solid 1px #000\"><h4>Error Back Trace</h4><pre>".
						htmlentities (print_r($trace,1),  ENT_COMPAT) .
						"</pre></div>"; 
			}
			break;

		default:
			$str = "<div style=\"border: green 1px solid; font-family: Arial, Helvetica, sans-serif; font-size: 15px; color:#000; padding:10px; background-color: #8AE234; width:100%; border:solid 3px green\"><h4>PHP OTHER:: Error Information[".$errno."]</h4>".
			"<div> Message :: <strong>".$errstr."</strong></div><br />
			<div>File :: ".$errfile."</div>
			<div>Line :: ".$errline."</div></pre>
			</div>";
			if(DEBUG){
				$trace = "<pre>".print_r(debug_backtrace(),1)."</pre>";
				$str .= "<div style=\"font-family: Arial, Helvetica, sans-serif; font-size: 8px; padding:10px; background-color: #3399ff; width:100%; border:solid 1px #000\"><h4>Error Back Trace</h4><pre>".
						htmlentities (print_r($trace,1),  ENT_COMPAT) .
						"</pre></div>"; 
			}
			break;
	}
	echo $str;
	/* Don't execute PHP internal error handler */
	return true;
}



