<?php 

require_once 'PHPUnit/Framework.php';
require_once 'package/AllTests.php';


class AllTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Project');
		$suite->addTest(Package_AllTests::suite());
		
		return $suite;
	}
}
?>
