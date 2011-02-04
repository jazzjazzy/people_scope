<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/employmenttype.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class EmploymenttypeTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $employmenttype;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	
	/**
	 * @group Employmenttype
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['employmenttype'] = "Full Time";
		
		
		
		self::$fieldtest = 'employmenttype';
		self::$newfieldval = 'Full-Time' ;
		
	   	//instigate classes
		self::$employmenttype = new employmenttype();
    	self::$db = new db();
	}
	
	/**
	 * @group Employmenttype
	 */
	public function testSaveEmploymenttypeDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$employmenttype->saveEmploymenttypeDetails();
    	
    	$_REQUEST[self::$fieldtest] = self::$_REQUEST[self::$fieldtest];

		//Save the information  
    	self::$employmenttype->saveEmploymenttypeDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(employmenttype_id) AS id FROM employmenttype');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM employmenttype WHERE employmenttype_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['employmenttype'], $fields[0]['employmenttype'], 'this is employmenttype');
		
    }
	
    /**
	 * @group Employmenttype
	 */
	public function testShowEmploymenttypeDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$employmenttype->showEmploymenttypeDetails(self::$id);
    }
	
    /**
	 * @group Employmenttype
	 */
    public function testGetEmploymenttypeList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$employmenttype->getEmploymenttypeList();
    	self::$employmenttype->getEmploymenttypeList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group Employmenttype
	 */
	public function testCreateEmploymenttypeDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	self::$employmenttype->createEmploymenttypeDetails(self::$id);
    }
    
    /**
	 * @group Employmenttype
	 */
    public function testEditEmploymenttypeDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	self::$employmenttype->editEmploymenttypeDetails(self::$id);
    }
	
    /**
	 * @group Employmenttype
	 */
	public function testUpdateEmploymenttypeDetails(){
    	
    	//setup request for update
		$_REQUEST = self::$_REQUEST;
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$employmenttype->updateEmploymenttypeDetails(self::$id);
    	
    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;
    	
    	//update with change
    	self::$employmenttype->updateEmploymenttypeDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM employmenttype WHERE employmenttype_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST[''], $fields[0][''], 'Start date title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group Employmenttype
	 */
    public function testDeleteEmploymenttypeDetails(){
    	$blankval ='';
    	self::$employmenttype->deleteEmploymenttypeDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM employmenttype WHERE employmenttype_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$employmenttype->deleteEmploymenttypeDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM employmenttype WHERE employmenttype_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumEmploymenttypeTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group Employmenttype
	 */
  public function testWebEmploymenttypeDetails()
  {

  }
}
 