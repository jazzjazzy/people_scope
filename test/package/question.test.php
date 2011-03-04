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

		self::$_REQUEST['question_catagory_id'] = "1";
		self::$_REQUEST['advertisement_id'] = "1";
		self::$_REQUEST['label'] = "That is the Universe";
		self::$_REQUEST['type'] = "text";
		
		
		
		self::$fieldtest = 'label';
		self::$newfieldval = 'What is your name' ;
		
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
    	self::$question->saveQuestionDetails();
    	
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
    	self::$question->getQuestionList('AJAX', self::$fieldtest, 'DESC', array('flt_'.self::$fieldtest.'='=>self::$_REQUEST[self::$fieldtest]));
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
  	 $this->open("/people_scope/question.php?action=create");
    $this->click("//li[@onclick=\"location.href='question.php'\"]");
    $this->waitForPageToLoad("10000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->select("question-type", "label=Checkbox");
    $this->click("//option[@value='checkbox']");
    $this->type("label", "This is your question");
    $this->select("question_catagory_id", "label=History");
    $this->type("add-field", "question 1");
    $this->click("add-field-button");
    $this->type("add-field", "question 2");
    $this->click("add-field-button");
    $this->type("add-field", "question 3");
    $this->click("add-field-button");
    $this->click("//div[@onclick='document.createQuestion.submit(); return false']");
    $this->waitForPageToLoad("10000");
    $this->click("//li[@onclick=\"location.href='question.php'\"]");
    $this->waitForPageToLoad("10000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->select("question-type", "label=Radio");
    $this->click("//option[@value='radio']");
    $this->select("question_catagory_id", "label=History");
    $this->type("label", "what is your question");
    $this->type("add-field", "question 1,question 2,question 3,question 4");
    $this->click("add-field-button");
    $this->click("//div[@onclick='document.createQuestion.submit(); return false']");
    $this->waitForPageToLoad("10000");
    $this->click("//li[@onclick=\"location.href='question.php'\"]");
    $this->waitForPageToLoad("10000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->type("label", "Why is your question");
    $this->select("question-type", "label=Select");
    $this->click("//option[@value='select']");
    $this->select("question_catagory_id", "label=History");
    $this->type("add-field", "question 1,question 2, question 3,question 4");
    $this->click("add-field-button");
    $this->click("//div[@onclick='document.createQuestion.submit(); return false']");
    $this->waitForPageToLoad("10000");
    $this->click("//li[@onclick=\"location.href='question.php'\"]");
    $this->waitForPageToLoad("10000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->type("label", "Why not is your question");
    $this->select("question-type", "label=Multiselect");
    $this->click("//option[@value='multiselect']");
    $this->select("question_catagory_id", "label=History");
    $this->type("add-field", "question 1,question 2, question 3,question 4");
    $this->click("add-field-button");
    $this->click("//div[@onclick='document.createQuestion.submit(); return false']");
    $this->waitForPageToLoad("10000");
    $this->click("//li[@onclick=\"location.href='question.php'\"]");
    $this->waitForPageToLoad("10000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->type("label", "Text is your question");
    $this->select("question-type", "label=Text");
    $this->click("//option[@value='text']");
    $this->select("question_catagory_id", "label=History");
    $this->click("//div[@onclick='document.createQuestion.submit(); return false']");
    $this->waitForPageToLoad("10000");
    $this->click("//li[@onclick=\"location.href='question.php'\"]");
    $this->waitForPageToLoad("10000");
    $this->click("link=Click Here");
    $this->waitForPageToLoad("10000");
    $this->type("label", "textarea is your question");
    $this->select("question-type", "label=Textarea");
    $this->click("//option[@value='textarea']");
    $this->select("question_catagory_id", "label=History");
    $this->click("//div[@onclick='document.createQuestion.submit(); return false']");
    $this->waitForPageToLoad("10000");
    
	  	
  }
}

class QuestionTestFill extends PHPUnit_Extensions_OutputTestCase
{	
	
//	PROTECTED STATIC $QUESTION;
//	PROTECTED STATIC $DB;
//	
//	/**
//	 * @GROUP QUESTION
//	 */
//	PROTECTED FUNCTION SETUP()
//	{	
//		// SETUP ARRAY TO INSERT 
//    	$_SESSION['USER']['CLIENT_ID'] = '10000000';
//		
//	   	//INSTIGATE CLASSES
//		SELF::$QUESTION = NEW QUESTION();
//    	SELF::$DB = NEW DB();
//	}
//
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION1()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "1";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "FIRST NAME";
//		$_REQUEST['TYPE'] = "TEXT";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION2()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "1";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "SURNAME";
//		$_REQUEST['TYPE'] = "TEXT";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION3()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "1";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "HOW MANY YEARS HAVE YOU BEEN IN THE INDUSTRY";
//		$_REQUEST['TYPE'] = "SELECT";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION4()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "1";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "HOW YOU WORKED FOR MORE THE 10 YEARS";
//		$_REQUEST['TYPE'] = "YES_NO";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION5()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "2";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "ARE YOU AN AUSTRALIAN CITIZEN";
//		$_REQUEST['TYPE'] = "YES_NO";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION6()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "2";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "IF YOU GET THE JOB, HOW LONG BEFORE YOU CAN START WORK";
//		$_REQUEST['TYPE'] = "SELECT";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION7()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "3";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "WOULD YOU BE WILLING TO WORK IN OTHER STORE";
//		$_REQUEST['TYPE'] = "RADIO";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION8()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "3";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "PERSONAL INFORMATION PRIVACY?";
//		$_REQUEST['TYPE'] = "RADIO";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION9()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "3";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "THE RETAIL INDUSTRY REQUIRES EMPLOYEES TO COMPLETE TASKS OF A PHYSICAL NATURE, THESE INCLUDE TASKS SUCH AS LIFTING, CARRYING, USING LADDERS AND STANDING FOR LONG PERIODS OF TIME. DO YOU HAVE ANY LIFTING OR MOVEMENT RESTRICTIONS?*";
//		$_REQUEST['TYPE'] = "TEXTAREA";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION10()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "3";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "PLEASE COMPLETE YOUR WORK HISTORY BELOW";
//		$_REQUEST['TYPE'] = "HISTORY";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION11()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "3";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "HAVE YOU WORKED IN A SIMILIAR ROLE WITHIN A RETAIL ENVIRONMENT?";
//		$_REQUEST['TYPE'] = "YES_NO";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
//    
//	/**
//	 * @GROUP QUESTION
//	 * @GROUP FILLER
//	 */
//	PUBLIC FUNCTION TESTFILLQUESTION12()
//    {	
//		$_SESSION['USER']['CLIENT_ID'] = '10000000';
//
//		$_REQUEST['QUESTION_CATAGORY_ID'] = "3";
//		$_REQUEST['ADVERTISEMENT_ID'] = "1";
//		$_REQUEST['LABEL'] = "DO YOU HAVE SUPERVISORY OR MANAGEMENT EXPERIENCE?";
//		$_REQUEST['TYPE'] = "YES_NO";
//
//		//SAVE THE INFORMATION  
//    	SELF::$QUESTION->SAVEQUESTIONDETAILS();
//    	
//    	//GET LAST INSERT FOR ID 
//    	$ID1 = SELF::$DB->SELECT('SELECT MAX(QUESTION_ID) AS ID FROM QUESTION');
//    	$ID = $ID1[0]['ID']; 
//    	//UUSE LAST ID TO FIND THE RECORD
//    	$FIELDS = SELF::$DB->SELECT('SELECT * FROM QUESTION WHERE QUESTION_ID = '.$ID);
//
//    	//ASSERTIONS CHECK FOR ALL INFORMATION PUT IN 
//    	PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['QUESTION_CATAGORY_ID'], $FIELDS[0]['QUESTION_CATAGORY_ID'], 'THIS IS QUESTION_CATAGORY_ID');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['LABEL'], $FIELDS[0]['LABEL'], 'THIS IS LABEL');
//		PHPUNIT_FRAMEWORK_ASSERT::ASSERTEQUALS($_REQUEST['TYPE'], $FIELDS[0]['TYPE'], 'THIS IS TYPE');
//		
//    }
}