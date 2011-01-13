<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../config/config.php');
require_once('../classes/department.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class DepartmentTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $department;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;

	public function testSaveDepartmentDetails()
    {	
    	// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

    	$_REQUEST['name'] = "Home o00ffice";
		$_REQUEST['office_id'] = "1";
		
		
		$_REQUEST = self::$_REQUEST;
		
		//instigate classes
		self::$department = new department();
    	self::$db = new db();
    	
    	
    	//setup request for update
		self::$_REQUEST['office_id'] = "aaaa";
		$_REQUEST = self::$_REQUEST;
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$department->saveDepartmentDetails();
    	
    	self::$_REQUEST['office_id'] = "1";
    	$_REQUEST = self::$_REQUEST;
    	//update with change
    	self:: $department->updateDepartmentDetails(self::$id);
    	
    	
    	
		//Save the information  
    	self::$department->saveDepartmentDetails();
    	
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(dept_id) AS id FROM department');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM dept_id WHERE department_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['name'], $fields[0]['name'], 'this is name');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['office_id'], $fields[0]['office_id'], 'this is office_id');
		
    }
	
	public function testShowDepartmentDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[''].'/');
    	self::$department->showDepartmentDetails(self::$id);
    }
	
    public function testGetDepartmentList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[''].'/');
    	self::$department->getDepartmentList();
    	self::$department->getDepartmentList('AJAX', 'title', 'DESC', array('='=>self::$_REQUEST['']));
    }
    
	public function testCreateDepartmentDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class=\"button\" onclick=\"document.[0-9a-z].submit(); return false\">Save<\/div>/');
    	self::$department->createDepartmentDetails(self::$id);
    }
    
    public function testEditDepartmentDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[''].'/');
    	self::$department->editDepartmentDetails(self::$id);
    }
	
	public function testUpdateDepartmentDetails(){
    	
    	//setup request for update
		self::$_REQUEST['office_id'] = "office_id";
		$_REQUEST = self::$_REQUEST;
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self:: $department->updateDepartmentDetails(self::$id);
    	
    	self::$_REQUEST['name'] = "Home Office";
    	self::$_REQUEST['office_id'] = "1";
    	$_REQUEST = self::$_REQUEST;
    	//update with change
    	self:: $department->updateDepartmentDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM department WHERE department_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST['name'], $fields[0]['name'], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST['office_id'], $fields[0]['office_id'], 'Start date title2');
    	//PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
/*
    public function testDeleteDepartmentDetails(){
    	$blankval ='';
    	self::$department->deleteDepartmentDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM department WHERE department_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$department->deleteDepartmentDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM department WHERE department_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
   */
 }  