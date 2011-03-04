<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/advertTemplate.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class AdvertTemplateTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $advertTemplate;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	
	/**
	 * @group AdvertTemplate
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['title'] = "This is a template";
		self::$_REQUEST['employmenttype_id'] = "2";
		self::$_REQUEST['catagory_id'] = "2";
		self::$_REQUEST['office_id'] = "1";
		self::$_REQUEST['dept_id'] = "1";
		self::$_REQUEST['role_id'] = "1";
		self::$_REQUEST['state_id'] = "3";
		self::$_REQUEST['storeLoc_id'] = "1";
		self::$_REQUEST['start_date'] = "12/12/2010";
		self::$_REQUEST['end_date'] = "12/12/2011";
		self::$_REQUEST['discription'] = "This is a template test";
		self::$_REQUEST['requirments'] = "This is a template test";
		self::$_REQUEST['status'] = "1";
		self::$_REQUEST['tracking_id'] = "1";
		self::$_REQUEST['advertisement_id'] = "1";
		
		
		
		self::$fieldtest = 'title';
		self::$newfieldval = 'This is a New template' ;
		
	   	//instigate classes
		self::$advertTemplate = new advertTemplate();
    	self::$db = new db();
	}
	
	/**
	 * @group AdvertTemplate
	 */
	public function testSaveAdvertTemplateDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$advertTemplate->saveAdvertTemplateDetails();
    	
    	$_REQUEST[self::$fieldtest] = self::$_REQUEST[self::$fieldtest];

		//Save the information  
    	self::$advertTemplate->saveAdvertTemplateDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(template_id) AS id FROM template');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM template WHERE template_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['title'], $fields[0]['title'], 'this is title');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['employmenttype_id'], $fields[0]['employmenttype_id'], 'this is employmenttype_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['catagory_id'], $fields[0]['catagory_id'], 'this is catagory_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['office_id'], $fields[0]['office_id'], 'this is office_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['dept_id'], $fields[0]['dept_id'], 'this is dept_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['role_id'], $fields[0]['role_id'], 'this is role_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['state_id'], $fields[0]['state_id'], 'this is state_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['storeLoc_id'], $fields[0]['storeLoc_id'], 'this is storeLoc_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['start_date'], databaseToUI($fields[0]['start_date']), 'this is start_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['end_date'], databaseToUI($fields[0]['end_date']), 'this is end_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['discription'], $fields[0]['discription'], 'this is discription');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['requirments'], $fields[0]['requirments'], 'this is requirments');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['status'], $fields[0]['status'], 'this is status');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['tracking_id'], $fields[0]['tracking_id'], 'this is tracking_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['advertisement_id'], $fields[0]['advertisement_id'], 'this is advertisement_id');
		
    }
	
    /**
	 * @group AdvertTemplate
	 */
	public function testShowAdvertTemplateDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$advertTemplate->showAdvertTemplateDetails(self::$id);
    }
	
    /**
	 * @group AdvertTemplate
	 */
    public function testGetAdvertTemplateList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$advertTemplate->getAdvertTemplateList();
    	self::$advertTemplate->getAdvertTemplateList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group AdvertTemplate
	 */
	public function testCreateAdvertTemplateDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	$this->expectOutputRegex('/<input class="button" type="image" value="Save">/');
    	self::$advertTemplate->createAdvertTemplateDetails(self::$id);
    }
    
    /**
	 * @group AdvertTemplate
	 */
    public function testEditAdvertTemplateDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	//$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	$this->expectOutputRegex('/<input class="button" type="image" value="Update">/');
    	self::$advertTemplate->editAdvertTemplateDetails(self::$id);
    }
	
    /**
	 * @group AdvertTemplate
	 */
	public function testUpdateAdvertTemplateDetails(){
    	
    	//setup request for update
		$_REQUEST = self::$_REQUEST;
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$advertTemplate->updateAdvertTemplateDetails(self::$id);
    	
    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;
    	
    	//update with change
    	self::$advertTemplate->updateAdvertTemplateDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM template WHERE template_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST[''], $fields[0][''], 'Start date title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group AdvertTemplate
	 */
    public function testDeleteAdvertTemplateDetails(){
    	$blankval ='';
    	self::$advertTemplate->deleteAdvertTemplateDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM template WHERE template_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$advertTemplate->deleteAdvertTemplateDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM template WHERE template_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumAdvertTemplateTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group AdvertTemplate
	 */
  public function testWebAdvertTemplateDetails()
  {
  	    $this->open("/people_scope/");
    $this->click("//li[@onclick=\"location.href='advertTemplate.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("30000");
    $this->type("title", "This is template 1");
    $this->select("employmenttype_id", "label=Casual Full time");
    $this->select("catagory_id", "label=Store Management");
    $this->type("office_id", "2");
    $this->type("dept_id", "2");
    $this->type("role_id", "2");
    $this->select("state_id", "label=Victoria");
    $this->type("storeLoc_id", "1");
    $this->click("start_date");
    $this->click("link=2");
    $this->click("end_date");
    $this->click("link=25");
    $this->type("status", "1");
    $this->type("tracking_id", "1");
    $this->type("advertisement_id", "1");
    $this->click("//input[@value='Save']");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("This is template 1", $this->getText("//div[@id='tab-body']/div[3]"));
    //$this->assertEquals("This is a job", $this->getText("//div[@id='tab-body']/div[23]/p"));
    //$this->assertEquals("this is also a job", $this->getText("//div[@id='tab-body']/div[25]/p"));
    $this->click("//li[@onclick=\"location.href='advertTemplate.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("30000");
    $this->type("title", "This is template 2");
    $this->select("employmenttype_id", "label=Full-time");
    $this->select("catagory_id", "label=IT Department");
    $this->type("office_id", "1");
    $this->type("dept_id", "1");
    $this->type("role_id", "1");
    $this->select("state_id", "label=New South Wales");
    $this->type("storeLoc_id", "1");
    $this->click("start_date");
    $this->click("link=1");
    $this->click("end_date");
    $this->click("link=28");
    $this->click("status");
    $this->type("status", "1");
    $this->type("tracking_id", "1");
    $this->type("advertisement_id", "1");
    $this->click("//input[@value='Save']");
    $this->waitForPageToLoad("30000");
    $this->click("//li[@onclick=\"location.href='advertTemplate.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Store Management", $this->getText("//tr[@id='2']/td[5]"));
    $this->assertEquals("IT Department", $this->getText("//tr[@id='3']/td[5]"));
  }
}
 