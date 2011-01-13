<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


require_once 'PHPUnit/Framework.php';

require_once('../config/config.php');
require_once('../classes/division.class.php');
require_once('database.class.php');
require_once('template.class.php');
 
class DivisionTest extends PHPUnit_Framework_TestCase
{

	
    public function testDivisionStore()
    {	
    	$_REQUEST['name'] = "Store";
		$_REQUEST['description'] = "This is a division of the stores department";
    	$division = new division();
    	
    	$division->saveDivisionDetails();
    	
    	$db = new db();
    	$id = $db->select('SELECT MAX(division_id) AS id FROM division');
    	
    	PHPUnit_Framework_Assert::assertEquals($division->getSelectListOfDivision($id[0]['id']), $_REQUEST['name']);
    	
    	$_REQUEST['name'] = "Store Division";
    	
    	$division->updateDivisionDetails($id[0]['id']);

    	PHPUnit_Framework_Assert::assertEquals($division->getSelectListOfDivision($id[0]['id']), $_REQUEST['name']);
    }
    
    public function testDivisionWarehouse()	{
    	
    	$_REQUEST['name'] = "Warehouse";
		$_REQUEST['description'] = "This is a division of the Warehouse department";
    	$division = new division();
    	
    	$division->saveDivisionDetails();
    	
    	$db = new db();
    	$id = $db->select('SELECT MAX(division_id) AS id FROM division');
    	
    	PHPUnit_Framework_Assert::assertEquals($division->getSelectListOfDivision($id[0]['id']), $_REQUEST['name']);
    	
    	$_REQUEST['name'] = "Warehouse Division";
    	
    	$division->updateDivisionDetails($id[0]['id']);

    	PHPUnit_Framework_Assert::assertEquals($division->getSelectListOfDivision($id[0]['id']), $_REQUEST['name']);
    	
    }
}