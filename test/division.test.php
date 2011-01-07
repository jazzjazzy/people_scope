
<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";
define('DB_USER','root');
define('DB_PASS','password');
define('DB_HOST','localhost');
define('DB_DBASE','client_100');
define('DB_TYPE','mysql');
define('DB_PORT', 3306);

require_once 'PHPUnit/Framework.php';
require_once('../config/config.php');
require_once('../classes/division.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class DivisionTest extends PHPUnit_Framework_TestCase
{
    public function testDivision()
    {	

    	$division = new division();
    	try{
    		$division->createDivisionDetails();
    	}catch (CustomException $e) {
			return;
		}
    	$this->fail('An expected exception has not been raised.');
    }
}