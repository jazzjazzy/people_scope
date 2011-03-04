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
    	$_SESSION['user']['user_id'] = 1;

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
		//PHPUnit_Framework_Assert::assertEquals($_REQUEST['template_id'], $fields[0]['template_id'], 'this is template_id');
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
    	//$this->expectOutputRegex('/<input class="button" type="image" value="Update">/');
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
    	//$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	$this->expectOutputRegex('/<input class="button" type="image" value="Save">/');
    	self::$advertisement->createAdvertisementDetails(self::$id);
    }
    
    /**
	 * @group Advertisement
	 */
    public function testEditAdvertisementDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	//$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	$this->expectOutputRegex('/<input class="button" type="image" value="Update">/');
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
    $this->open("http://dev/people_scope/advertisement.php");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->click("link=Whare Housing");
    $this->doubleClick("//li[@id='list_4']/div/div[1]");
    $this->doubleClick("//li[@id='list_2']/div/div[1]");
    $this->doubleClick("//li[@id='list_3']/div/div[1]");
    $this->doubleClick("//li[@id='list_5']/div/div[1]");
    $this->click("upload_resume");
    $this->click("cover_letter");
    $this->click("status");
    $this->select("employmenttype_id", "label=Full-Time");
    $this->select("state_id", "label=New South Wales");
    $this->select("catagory_id", "label=Store Management");
    $this->type("title", "**Senior Drupal PHP developer**");
    $this->runScript("CKEDITOR.instances['discription'].setData('<p>Drupal PHP Senior Developer for&nbsp;immediate 6 month contract with possible to extend or move to a&nbsp;permanent&nbsp;role.<br> <br> The right&nbsp;candidate&nbsp;will have both back-end and front-end experience. <br> <br> Want to work on exciting projects&nbsp;work with&nbsp;household&nbsp;name brands? <br> Apply for instant feedback!</p>')");
    $this->runScript("CKEDITOR.instances['requirments'].setData('<p> <strong> Key skills:</strong><br> &bull; PHP 5<br> &bull; Drupal<br> &bull; XHTML&nbsp;<br> &bull; CSS<br> &bull; JavaScript<br> &bull; AJAX<br> </p>')");
    $moveValue = $this->getText("css=.question li:nth-child(1)");
    $this->mouseDownAt("css=.question li:nth-child(1)", "10,10");
    $this->mouseMoveAt("css=.question li:nth-child(4)", "10,20");
    $this->mouseOver("css=.question li:nth-child(4)");
    $this->mouseUpAt("css=.question li:nth-child(4)", "10,10");
    $this->assertEquals($moveValue, $this->getText("css=.question li:nth-child(4)"));
    $this->waitForPageToLoad("");
    $this->click("css=.question li:nth-child(2)");
    $this->click("css=.question li:nth-child(2)");
    $this->waitForPageToLoad("");
    $this->assertTrue($this->isTextPresent("How you worked for more the 10 years"));
    $this->clickAt("//li[@id='q_3']/div/div[1]", "");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ("Surname" == $this->getText("//div[@id='tabs-1']/div[2]/div[1]/div[1]")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->assertEquals("Surname", $this->getText("//div[@id='tabs-1']/div[2]/div[1]/div[1]"));
    $this->click("link=Tracking");
    $this->type("//div[@id='tabs-2']/div/textarea", "this, is a test");
    $this->click("link=Setting");
    $this->assertEquals("Is this field required: yes no", $this->getText("//div[@id='tabs-3']/div"));
    $this->click("required");
    $this->click("//input[@name='required' and @value='no']");
    $this->click("//input[@value='Save']");
    $this->waitForPageToLoad("10000");
  	
  }
}
 