<?php

require_once('package/build.test.php'); 
require_once('package/category.test.php');
require_once('package/advertisement.test.php');
require_once('package/advertTemplate.test.php');
require_once('package/division.test.php');
require_once('package/tools.function.test.php');
require_once('package/department.test.php');
require_once('package/state.test.php');
class Package_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Package');
 		$suite->addTestSuite('clientDatabaseTest');
 		$suite->addTestSuite('AdvertTemplateTest');
 		$suite->addTestSuite('CategoryTest');
 		$suite->addTestSuite('SeleniumCategoryTest');
        $suite->addTestSuite('AdvertisementTest');
        $suite->addTestSuite('SeleniumAdvertTemplateTest');
        $suite->addTestSuite('StateTest');
        $suite->addTestSuite('SeleniumStateTest');
        $suite->addTestSuite('DivisionTest');
		$suite->addTestSuite('ToolFunction');
		$suite->addTestSuite('DepartmentTest');
        return $suite;
    }
}
?>
