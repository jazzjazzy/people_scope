<?php
require_once('advertisment.test.php');
require_once('division.test.php');
require_once('package/tools.function.test.php');
require_once('package/department.test.php'); 
class Package_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Package');
 
        $suite->addTestSuite('AdvertismentTest');
        $suite->addTestSuite('DivisionTest');
	$suite->addTestSuite('ToolFunction');
	$suite->addTestSuite('DepartmentTest');
        return $suite;
    }
}
?>
