<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once('../classes/question.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class QuestionTest extends PHPUnit_Extensions_OutputTestCase
{
	protected static $question;
	protected static $id;
	protected static $_REQUEST;
	protected static $db;
	protected static $fieldtest;
	protected static $newfieldval;
	
	
	/**
	 * @group Question
	 */
	protected function setUp()
	{	
		// setup array to insert 
    	$_SESSION['user']['client_id'] = '10000000';

		self::$_REQUEST['question_catagory_id'] = "";
		self::$_REQUEST['advertisement_id'] = "";
		self::$_REQUEST['label'] = "";
		self::$_REQUEST['type'] = "";
		
		
		
		self::$fieldtest = 'title';
		self::$newfieldval = '' ;
		
	   	//instigate classes
		self::$question = new question();
    	self::$db = new db();
	}
	
	/**
	 * @group Question
	 */
	public function testSaveQuestionDetails()
    {	
		$_REQUEST = self::$_REQUEST;
		    	
    	//setup request for update
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$employmenttype->saveEmploymenttypeDetails();
    	
    	$_REQUEST[self::$fieldtest] = self::$_REQUEST[self::$fieldtest];

		//Save the information  
    	self::$question->saveQuestionDetails();
    	
    	//get last insert for ID 
    	$id1 = self::$db->select('SELECT MAX(question_id) AS id FROM question');
    	self::$id = $id1[0]['id']; 
    	//Uuse last ID to find the record
    	$fields = self::$db->select('SELECT * FROM question WHERE question_id = '.self::$id);

    	//Assertions check for all information put in 
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['question_catagory_id'], $fields[0]['question_catagory_id'], 'this is question_catagory_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['advertisement_id'], $fields[0]['advertisement_id'], 'this is advertisement_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['label'], $fields[0]['label'], 'this is label');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['type'], $fields[0]['type'], 'this is type');
		
    }
	
    /**
	 * @group Question
	 */
	public function testShowQuestionDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="location.href=\'(.+?)\.php\?action=edit&id=([0-9]+)\'">Edit<\/div>/');
    	self::$question->showQuestionDetails(self::$id);
    }
	
    /**
	 * @group Question
	 */
    public function testGetQuestionList(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	self::$question->getQuestionList();
    	self::$question->getQuestionList('AJAX', self::$fieldtest, 'DESC', array('='=>self::$_REQUEST[self::$fieldtest]));
    }
    
    /**
	 * @group Question
	 */
	public function testCreateQuestionDetails(){
    	$this->expectOutputRegex('/value=\"\"/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Save<\/div>/');
    	self::$question->createQuestionDetails(self::$id);
    }
    
    /**
	 * @group Question
	 */
    public function testEditQuestionDetails(){
    	$this->expectOutputRegex('/'.self::$_REQUEST[self::$fieldtest].'/');
    	$this->expectOutputRegex('/<div class="button" onclick="document\.(.+?)\.submit\(\); return false">Update<\/div>/');
    	self::$question->editQuestionDetails(self::$id);
    }
	
    /**
	 * @group Question
	 */
	public function testUpdateQuestionDetails(){
    	
    	//setup request for update
		$_REQUEST = self::$_REQUEST;
		$_REQUEST[self::$fieldtest] = "";
		
		$this->expectOutputRegex("/<span class=\"error\">/");
    	self::$question->updateQuestionDetails(self::$id);
    	
    	$_REQUEST[self::$fieldtest] = self::$newfieldval ;
    	
    	//update with change
    	self::$question->updateQuestionDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is not deleted  
    	$fields = self::$db->select("SELECT * FROM question WHERE question_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST[self::$fieldtest], $fields[0][self::$fieldtest], 'this is title2');
    	PHPUnit_Framework_Assert::assertEquals(self::$_REQUEST[''], $fields[0][''], 'Start date title2');
    	PHPUnit_Framework_Assert::assertEquals(false, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));
    }
    
	/**
	 * @group Question
	 */
    public function testDeleteQuestionDetails(){
    	$blankval ='';
    	self::$question->deleteQuestionDetails($blankval);
    	
    	$fields = self::$db->select("SELECT delete_date FROM question WHERE question_id = ".self::$id );
    
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] == '0000-00-00 00:00:00' || empty($fields[0]['delete_date'])));    	
    	
    	//mark entry as delete
    	self::$question->deleteQuestionDetails(self::$id);
    	
    	//select the entry to confirm it has changed and confirm it is deleted 
    	$fields = self::$db->select("SELECT delete_date FROM question WHERE question_id = ".self::$id );
    	PHPUnit_Framework_Assert::assertEquals(true, ($fields[0]['delete_date'] != '0000-00-00 00:00:00' && !empty($fields[0]['delete_date'])));

    }
 }  
 
 
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumQuestionTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
	    $this->setBrowser("*firefox");
	    $this->setBrowserUrl("http://dev/");
  }
	/**
	 * @group Question
	 */
  public function testWebQuestionDetails()
  {
  
  }
}
 