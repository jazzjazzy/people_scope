<?php

require_once('package/build.test.php'); 
require_once('package/advertisement.test.php');
require_once('package/category.test.php');
require_once('package/employmenttype.test.php');
require_once('package/advertTemplate.test.php');
require_once('package/questionCatagory.test.php');
require_once('package/question.test.php');

class Package_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Package');
        $suite->addTestSuite('clientDatabaseTest');
        $suite->addTestSuite('CategoryTest');
        $suite->addTestSuite('SeleniumCategoryTest');
        $suite->addTestSuite('EmploymenttypeTest');
        $suite->addTestSuite('SeleniumEmploymenttypeTest');
        $suite->addTestSuite('AdvertTemplateTest');
        $suite->addTestSuite('SeleniumAdvertTemplateTest');
        $suite->addTestSuite('QuestionCatagoryTest');
        $suite->addTestSuite('SeleniumQuestionCatagoryTest');
		$suite->addTestSuite('QuestionTest');
        $suite->addTestSuite('SeleniumQuestionTest');
        $suite->addTestSuite('QuestionTestFill');
        $suite->addTestSuite('AdvertisementTest');
        $suite->addTestSuite('SeleniumAdvertisementTest');
        return $suite;
    }
}
?>
