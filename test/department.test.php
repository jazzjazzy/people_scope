<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Framework.php';

require_once('../config/config.php');
require_once('../classes/department.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class DivisionTest extends PHPUnit_Framework_TestCase
{

	
    public function testUserStore()
    {	
    	
    	$_SESSION['user']['client_id'] = '10000000';
    	
    	
    	$_REQUEST['name'] = "";
		$_REQUEST['office_id'] = "";
		
		
		$department = new department();
    	
    	$department->saveDepartmentDetails();
    	
    	$db = new db();
    	$id = $db->select('SELECT MAX(dept_id) AS id FROM department');
    	$fields = $db->select('SELECT * FROM department WHERE dept_id = '.$id[0]['id']);
    	
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['name'], $fields[0]['name'], 'this is name');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['office_id'], $fields[0]['office_id'], 'this is office_id');
		
    	
		$_REQUEST['name'] = "";
		
		
    	
    	$advertisment->updateAdvertismentDetails($id[0]['id']);
    	
    	$fields = $db->select("SELECT * FROM advertisment WHERE advertisment_id = ".$id[0]['id'] );

    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['title'], $fields[0]['title'], 'this is title2');
    	
    }
 }  