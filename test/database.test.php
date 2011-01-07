<?php
define('DB_USER','root');
define('DB_PASS','password');
define('DB_HOST','localhost');
define('DB_DBASE','unit-test');
define('DB_TYPE','mysql');
define('TURN_ON_PP',true);
define('ERROR_SETTING', 'staging');

define('DEBUG', false);

require_once 'PHPUnit/Framework.php';
require_once('../classes/base/database.class.php');
require_once('../classes/base/errorhandler.class.php');
require_once('../classes/base/tools.function.php');
 
class DatabaseTest extends PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
			$db = new db();
			$sql = "
			CREATE TABLE IF NOT EXISTS `table_test` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `col1` varchar(255) NOT NULL,
				  `col2` int(11) NOT NULL,
				  `col3` date NOT NULL,
				  `col4` text NOT NULL,
				  `col5` varchar(255) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			
			
			$this->assertType('PDOStatement', $db->query($sql));
			
			$this->assertEquals($db->getDNSString(), DB_TYPE.':dbname='.DB_DBASE.';host='.DB_HOST);
			
			$db->dbh->beginTransaction();
	
			$id = $db->insert("INSERT INTO table_test(col1, col2, col3, col4, col5)values('jason', 22, '2004-1-1 00:00:00', 'jason', 'hello')");
			
			$this->assertEquals(is_numeric($id), true);
			
			
			$result = $db->select("select * from table_test");
			
			if($this->assertEquals(is_array($result), true)){
				$count = count($result)-1;
				$this->assertIdentical($result[$count]['col1'], 'jason');
			}
			
		
			$sql = "UPDATE table_test SET col1 = 'ffffff' WHERE id =".$id;
    		
			try{
				$resultupd = $db->update($sql); 
			}catch(CustomException $e){
				throw new CustomException($e->queryError($sql));
			}
    		
    		
			$this->assertEquals($resultupd, true);

			
			$result2 = $db->select("select * from table_test WHERE id =".$id);

			if($this->assertEquals(is_array($result2), true)){
				$this->assertIdentical($result2[0]['col1'], 'ffffff');
			}
			
			$resultdel = $db->delete("delete from table_test");
			
			$this->assertEquals($resultdel, true);
			
			$db->dbh->rollBack();
			
			$result3 = $db->select("select * from table_test WHERE id =".$id);
			
			if($this->assertEquals(is_array($result3), true)){
				$this->assertIdentical(count($result3), 0);
			}
			
			$sql = "DROP TABLE IF  EXISTS `table_test`;";
			
			$this->assertType('PDOStatement', $db->query($sql));
			
	}
}


?>