<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Framework.php';

require_once('../config/config.php');
require_once('../classes/advertisment.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class DivisionTest extends PHPUnit_Framework_TestCase
{

	
    public function testUserStore()
    {	
    	
    	$_SESSION['user']['client_id'] = '10000000';
    	
    	
    	$_REQUEST['title'] = "this ia a title";
		$_REQUEST['catagory_id'] = "1";
		$_REQUEST['template_id'] = "1";
		$_REQUEST['office_id'] = "1";
		$_REQUEST['dept_id'] = "1";
		$_REQUEST['role_id'] = "1";
		$_REQUEST['state_id'] = "1";
		$_REQUEST['store_location_id'] = "1";
		$_REQUEST['storerole_id'] = "1";
		$_REQUEST['start_date'] = "22/12/2010";
		$_REQUEST['end_date'] = "22-12-2011";
		$_REQUEST['discription'] = "this is a discription ";
		$_REQUEST['requirments'] = "this is the requirments";
		$_REQUEST['upload_resume'] = "1";
		$_REQUEST['cover_letter'] = "1";
		$_REQUEST['status'] = "1";
		$_REQUEST['employmenttype'] = "1";
		$_REQUEST['create_by'] = "1";
		$_REQUEST['modify_by'] = "1";
		$_REQUEST['delete_by'] = "1";
		$_REQUEST['question_id'] = "1";
		$_REQUEST['tracking_id'] = "1";
		
		
		$advertisment = new advertisment();
    	
    	$advertisment->saveAdvertismentDetails();
    	
    	$db = new db();
    	$id = $db->select('SELECT MAX(advertisment_id) AS id FROM advertisment');
    	$fields = $db->select('SELECT * FROM advertisment WHERE advertisment_id = '.$id[0]['id']);
    	
    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['title'], $fields[0]['title'], 'this is title');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['catagory_id'], $fields[0]['catagory_id'], 'this is catagory_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['template_id'], $fields[0]['template_id'], 'this is template_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['office_id'], $fields[0]['office_id'], 'this is office_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['dept_id'], $fields[0]['dept_id'], 'this is dept_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['role_id'], $fields[0]['role_id'], 'this is role_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['state_id'], $fields[0]['state_id'], 'this is state_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['store_location_id'], $fields[0]['store_location_id'], 'this is store_location_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['storerole_id'], $fields[0]['storerole_id'], 'this is storerole_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['start_date'], databaseToUI($fields[0]['start_date']), 'this is start_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['end_date'], databaseToUI($fields[0]['end_date']), 'this is end_date');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['discription'], $fields[0]['discription'], 'this is discription');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['requirments'], $fields[0]['requirments'], 'this is requirments');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['upload_resume'], $fields[0]['upload_resume'], 'this is upload_resume');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['cover_letter'], $fields[0]['cover_letter'], 'this is cover_letter');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['status'], $fields[0]['status'], 'this is status');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['employmenttype'], $fields[0]['employmenttype'], 'this is employmenttype');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['create_by'], $fields[0]['create_by'], 'this is create_by');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['modify_by'], $fields[0]['modify_by'], 'this is modify_by');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['delete_by'], $fields[0]['delete_by'], 'this is delete_by');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['question_id'], $fields[0]['question_id'], 'this is question_id');
		PHPUnit_Framework_Assert::assertEquals($_REQUEST['tracking_id'], $fields[0]['tracking_id'], 'this is tracking_id');
		
    	
		$_REQUEST['title'] = "";
		
		
    	
    	$advertisment->updateAdvertismentDetails($id[0]['id']);
    	
    	$fields = $db->select("SELECT * FROM advertisment WHERE advertisment_id = ".$id[0]['id'] );

    	PHPUnit_Framework_Assert::assertEquals($_REQUEST['title'], $fields[0]['title'], 'this is title2');
    	
    }
 }  