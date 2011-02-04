<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/advertisement.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class AdvertisementTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $advertisement;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	/**
	 * @group Advertisement
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['title'] = "This is a TEST";
		self::$_REQUEST['catagory_id'] = "2";
		self::$_REQUEST['template_id'] = "1";
		self::$_REQUEST['office_id'] = "1";
		self::$_REQUEST['dept_id'] = "1";
		self::$_REQUEST['role_id'] = "1";
		self::$_REQUEST['state_id'] = "2";
		self::$_REQUEST['store_location_id'] = "1";
		self::$_REQUEST['storerole_id'] = "1";
		self::$_REQUEST['start_date'] = "12/12/2010";
		self::$_REQUEST['end_date'] = "12/12/2011";
		self::$_REQUEST['discription'] = "This is a discription";
		self::$_REQUEST['requirments'] = "This is a requirment";
		self::$_REQUEST['upload_resume'] = "1";
		self::$_REQUEST['cover_letter'] = "1";
		self::$_REQUEST['status'] = "1";
		self::$_REQUEST['employmenttype_id'] = "1";
		self::$_REQUEST['create_by'] = "1";
		self::$_REQUEST['modify_by'] = "1";
		self::$_REQUEST['delete_by'] = "1";
		
		
		
		self::$fieldtest = 'title';
		self::$newfieldval = 'This is a New Test' ;
		
	   	//instigate classes
		self::$advertisement = new advertisement();
    	self::$db = new db();
	}
	
	/**
	 * @group Advertisement
	 */
	public function testSaveAdvertisementDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		self::$_REQUEST['start_date'] = "22-12-2010";
		$_REQUEST = self::$_REQUEST;
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$advertisement->saveAdvertisementDetails();
    	
    	self::$_REQUEST['start_date'] = "12/12/2010";
    	$_REQUEST = self::$_REQUEST;

		//Save the information  
    	self::$advertisement->saveAdvertisementDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(advertisement_id) AS id FROM advertisement');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM advertisement WHERE advertisement_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['title'], $fields[0]['title'], 'this is title');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['catagory_id'], $fields[0]['catagory_id'], 'this is catagory_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['template_id'], $fields[0]['template_id'], 'this is template_id');
		/*PHPUnit_Framework_Assert::assertEquals($_REQUEST['office_id'], $fields[0]['office_id'], 'this is office_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['dept_id'], $fields[0]['dept_id'], 'this is dept_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['role_id'], $fields[0]['role_id'], 'this is role_id');*/
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['state_id'], $fields[0]['state_id'], 'this is state_id');
		/*PHPUnit_Framework_Assert::assertEquals($_REQUEST['store_location_id'], $fields[0]['store_location_id'], 'this is store_location_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['storerole_id'], $fields[0]['storerole_id'], 'this is storerole_id');*/
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['start_date'], databaseToUI($fields[0]['start_date']), 'this is start_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['end_date'], databaseToUI($fields[0]['end_date']), 'this is end_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['discription'], $fields[0]['discription'], 'this is discription');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['requirments'], $fields[0]['requirments'], 'this is requirments');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['upload_resume'], $fields[0]['upload_resume'], 'this is upload_resume');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['cover_letter'], $fields[0]['cover_letter'], 'this is cover_letter');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['status'], $fields[0]['status'], 'this is status');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['employmenttype_id'], $fields[0]['employmenttype_id'], 'this is employmenttype_id');
		/*PHPUnit_Framework_Assert::assertEquals($_REQUEST['create_by'], $fields[0]['create_by'], 'this is create_by');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['modify_by'], $fields[0]['modify_by'], 'this is modify_by');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['delete_by'], $fields[0]['delete_by'], 'this is delete_by');*/
		
    }
	
    /**
	 * @group Advertisement
	 */
	public function testShowAdvertisementDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$advertisement->showAdvertisementDetails(self::$id);
    }
	
    /**
	 * @group Advertisement
	 */
    public function testGetAdvertisementList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$advertisement->getAdvertisementList();
    	self::$advertisement->getAdvertisementList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group Advertisement
	 */
	public function testCreateAdvertisementDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	self::$advertisement->createAdvertisementDetails(self::$id);
    }
    
    /**
	 * @group Advertisement
	 */
    public function testEditAdvertisementDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	self::$advertisement->editAdvertisementDetails(self::$id);
    }
	
    /**
	 * @group Advertisement
	 */
	public function testUpdateAdvertisementDetails(){
    	
    	//setup request for update
		self::$_REQUEST[self::$fieldtest] = "";
		$_REQUEST = self::$_REQUEST;

		$this->expectOutputRegex("/<span class=\"error\">/");
    	self:: $advertisement->updateAdvertisementDetails(self::$id);
    	
    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;
    	
    	//update with change
    	self:: $advertisement->updateAdvertisementDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM advertisement WHERE advertisement_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group Advertisement
	 */
    public function testDeleteAdvertisementDetails(){
    	$blankval ='';
    	self::$advertisement->deleteAdvertisementDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM advertisement WHERE advertisement_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$advertisement->deleteAdvertisementDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM advertisement WHERE advertisement_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumAdvertisementTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*chrome");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group Advertisement
	 */
  public function testWebAdvertisementDetails()
  {
    $this->open("/people_scope/category.php");
    $this->click("//li[@onclick=\"location.href='advertisement.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("30000");
    $this->type("//input[@id='title']", "This is the first");
    $this->type("catagory_id", "1");
    $this->type("template_id", "1");
    //$this->type("office_id", "1");
    //$this->type("dept_id", "1");
   // $this->type("role_id", "1");
    $this->type("state_id", "1");
    //$this->type("store_location_id", "1");
    //$this->type("storerole_id", "1");
    $this->type("start_date", "12/12/2010");
    $this->type("end_date", "12/12/2011");
    $this->type("discription", "this is a discription");
    $this->type("requirments", "This is a requirement");
    $this->type("discription", "This is a discription");
    $this->click("upload_resume");
    $this->click("cover_letter");
    $this->click("status");
    $this->type("employmenttype_id", "1");
   	//$this->type("create_by", "1");
   	//$this->type("modify_by", "1");
   	//$this->type("delete_by", "1");*/
    $this->click("//div[@onclick='document.createAdvertisement.submit(); return false']");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("This is the first", $this->getText("//div[@id='tab-body']/div[3]"));
    $this->assertEquals("This is a discription", $this->getText("//div[@id='tab-body']/div[15]"));
    $this->assertEquals("This is a requirement", $this->getText("//div[@id='tab-body']/div[17]"));
    $this->click("//div[@onclick=\"location.href='advertisement.php?action=edit&id=2'\"]");
    $this->waitForPageToLoad("30000");
    $this->type("discription", "This is a discription too");
    $this->type("requirments", "This is a requirement too");
    $this->type("start_date", "12/12/2010");
    $this->type("end_date", "12/12/2011");
    $this->click("//div[@onclick='document.editAdvertisement.submit(); return false']");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("This is a discription too", $this->getText("//div[@id='tab-body']/div[15]"));
    $this->assertEquals("This is a requirement too", $this->getText("//div[@id='tab-body']/div[17]"));
    $this->click("//li[@onclick=\"location.href='advertisement.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("This is the first", $this->getText("//tr[@id='2']/td[1]"));
    $this->assertEquals("Store Management", $this->getText("//tr[@id='2']/td[2]"));
  }
}
 