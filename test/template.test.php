<?php
define('DB_USER','root');
define('DB_PASS','password');
define('DB_HOST','localhost');
define('DB_DBASE','unit-test');
define('DB_TYPE','mysql');
define('TURN_ON_PP',true);
define('ERROR_SETTING', 'dev');

define('DEBUG', false);

//require_once '/usr/local/share/pear/PHPUnit/Framework.php';
//require_once('../classes/base/template.class.php');
require_once('../classes/base/errorhandler.class.php');
require_once('../classes/base/tools.function.php');
 
class templateTest extends PHPUnit_Framework_TestCase
{
    public function testCreateTemplate()
    {
        $template = new template('template/1.html');
		
		$this->assertRegExp('/<head><script/', $template->fetch()); // check Javascipt added
		
		$this->assertTrue($template->assign('name','Jason Stewart'));
		$this->assertRegExp('/my name is Jason Stewart/', $template->fetch()); // check assign worked
		$this->assertNotRegExp('/{*name*}/', $template->fetch()); // check place holder is not there
		
		$this->assertTrue($template->assign('name','mary smith'));
		$this->assertRegExp('/my name is mary smith/', $template->fetch()); // check change assignment is there 
		$this->assertNotRegExp('/{*(.*?)*}/', $template->fetch()); // check place holder is not there
		
		/*
		 Test the asignment of tags using assignArray 
		 */
		$this->assertTrue($template->set('template/2.html'));
		$this->assertRegExp('/my name is mary smith/', $template->fetch()); // check change assignment is there
		
		$test['name']='jason Stewart';
		$test['name2']='mary smith';
		$test['name3']='frank jones';
		
		$template->assignArray($test);
		$this->assertRegExp('/<head><script/', $template->fetch()); // check Javascipt added
		$this->assertRegExp('/my name is '.$test['name'].' and '.$test['name2'].' with '.$test['name3'].'/', $template->fetch()); // check change assignment is there
		$this->assertNotRegExp('/{*(.*?)*}/', $template->fetch()); // check place holder is not there
		
		/*
		 Test the asignment of tags using assignRepeat
		 */
		$this->assertTrue($template->set('template/3.html'));
		unset($test);
		//array to assign 
		$test[0]['name'] = 'Jason';
		$test[0]['name2'] = 'Stewart';
		$test[0]['name3'] = '34';
		$test[1]['name'] = 'mary';
		$test[1]['name2'] = 'smith';
		$test[1]['name3'] = '45';
		$test[2]['name'] = 'frank';
		$test[2]['name2'] = 'jones';
		$test[2]['name3'] = '24';
		
		$template->assignRepeat('table', $test);
		$this->assertRegExp('/<tr><td>'.$test[0]['name'].'<\/td><td>'.$test[0]['name2'].'<\/td><td>'.$test[0]['name3'].'<\/td><\/tr>/', $template->fetch()); // check change assignment is there row 1 
		$this->assertRegExp('/<tr><td>'.$test[1]['name'].'<\/td><td>'.$test[1]['name2'].'<\/td><td>'.$test[1]['name3'].'<\/td><\/tr>/', $template->fetch()); // check change assignment is there row 2 
		$this->assertRegExp('/<tr><td>'.$test[2]['name'].'<\/td><td>'.$test[2]['name2'].'<\/td><td>'.$test[2]['name3'].'<\/td><\/tr>/', $template->fetch()); // check change assignment is there row 3
		
		/*
		 Test replace 
		 */
		$this->assertTrue($template->set('template/1.html'));
		$this->assertTrue($template->assign('name','Jason Stewart')); //assign for string test 
		$this->assertTrue($template->assign('name2','mary smith')); //assign for tag test 
		$this->assertTrue($template->replace('my name is', 'my name was')); // replace string 
		$this->assertRegExp('/my name was Jason Stewart/', $template->fetch()); // check assign worked
		$this->assertTrue($template->replace('{*name*}', '{*name2*}')); // replace the tag
		$this->assertRegExp('/my name was mary smith/', $template->fetch()); // check assign worked
		
		ob_start();
		$template->display(); //output as display for last test
		$op = ob_get_contents();
		ob_end_clean();
		$this->assertRegExp('/my name was mary smith/', $op); //output buffer for display() test 
		
		/*
		 Test assignBlak  
		 */
		$template2 = new template(); // new instance with no template
		$test2 = 'This is doing a test';
		$this->assertTrue($template2->assignBlank($test2)); // assign to blank
		$this->assertEquals($template2->fetch(), $test2); // check assign worked
    }
}
?>
