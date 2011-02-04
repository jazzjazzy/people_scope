<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/category.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class CategoryTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $category;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	
	/**
	 * @group Category
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['catagory_name'] = "Ware Housing";
		
		
		
		self::$fieldtest = 'catagory_name';
		self::$newfieldval = 'Store Management' ;
		
	   	//instigate classes
		self::$category = new category();
    	self::$db = new db();
	}
	
	/**
	 * @group Category
	 */
	public function testSaveCategoryDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$category->saveCategoryDetails();
    	
    	$_REQUEST[self::$fieldtest] = "Ware Housing";
    	
		//Save the information  
    	self::$category->saveCategoryDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(catagory_id) AS id FROM category');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM category WHERE catagory_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is catagory_name');
		
    }
	
    /**
	 * @group Category
	 */
	public function testShowCategoryDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$category->showCategoryDetails(self::$id);
    }
	
    /**
	 * @group Category
	 */
    public function testGetCategoryList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$category->getCategoryList();
    	self::$category->getCategoryList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group Category
	 */
	public function testCreateCategoryDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	self::$category->createCategoryDetails(self::$id);
    }
    
    /**
	 * @group Category
	 */
    public function testEditCategoryDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	self::$category->editCategoryDetails(self::$id);
    }
	
    /**
	 * @group Category
	 */
	public function testUpdateCategoryDetails(){
    	
    	//setup request for update
		$_REQUEST = self::$_REQUEST;
		
		$_REQUEST[self::$fieldtest] = "";

		$this->expectOutputRegex("/<span class=\"error\">/");
    	self:: $category->updateCategoryDetails(self::$id);

    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;

    	//update with change
    	self:: $category->updateCategoryDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM category WHERE catagory_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	//PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST[''], $fields[0][''], 'Start date title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group Category
	 */
    public function testDeleteCategoryDetails(){
    	$blankval ='';
    	self::$category->deleteCategoryDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM category WHERE catagory_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$category->deleteCategoryDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM category WHERE catagory_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumCategoryTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group Category
	 */
  public function testWebCategoryDetails()
  {
  	$this->open("/people_scope/category.php");
    $this->click("//li[@onclick=\"location.href='category.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("30000");
    $this->type("catagory_name", "Warehouseing");
    $this->click("//div[@onclick='document.createCategory.submit(); return false']");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Warehouseing", $this->getText("//div[@id='tab-body']/div[5]"));
    $this->click("//li[@onclick=\"location.href='category.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Warehouseing", $this->getText("//tr[@id='2']/td[1]"));
    $this->click("link=Click Here");
    $this->waitForPageToLoad("30000");
    $this->type("catagory_name", "Sales Department");
    $this->click("//div[@onclick='document.createCategory.submit(); return false']");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Sales Department", $this->getText("//div[@id='tab-body']/div[5]"));
    $this->click("//li[@onclick=\"location.href='category.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->click("//tr[@id='2']/td[1]");
    $this->waitForPageToLoad("30000");
    $this->click("//div[@onclick=\"location.href='category.php?action=edit&id=2'\"]");
    $this->waitForPageToLoad("30000");
    $this->type("catagory_name", "Warehouseing Department");
    $this->click("//div[@onclick='document.editCategory.submit(); return false']");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Warehouseing Department", $this->getText("//div[@id='tab-body']/div[5]"));
    $this->click("//li[@onclick=\"location.href='category.php'\"]");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Warehouseing Department", $this->getText("//tr[@id='2']/td[1]"));
  }
}
 