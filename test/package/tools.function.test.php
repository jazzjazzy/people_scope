<?php
$_SERVER['HTTP_HOST'] = "DEV";
$_SERVER['DOCUMENT_ROOT'] = "/home/workspace";


//require_once 'PHPUnit/Framework.php';

require_once('../config/config.php');
require_once 'PHPUnit/Extensions/OutputTestCase.php';
//require_once('../../classes/base/tools.function.php');


class ToolFunction extends PHPUnit_Extensions_OutputTestCase
//class ToolFunction extends PHPUnit_Framework_TestCase
{
    public function testPPString()
    {
    	$string = 'Test String';
    	$this->expectOutputString('<div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding:10px; background-color: #ffcccc; width:100%; border:solid 1px #000"><h2>Notification message</h2><h3>'.trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__)).' -- Line : '.(__LINE__ +1).' -- Function : '.__FUNCTION__.'</h3>'."\n".'<pre>'.$string.'</pre></div>');
    	pp($string); // check assign worked
    }
    
	public function testPPArray()
    {
    	$Array = array('TEST 1', 'TEST 2', 'TEST 3');
    	$this->expectOutputString('<div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding:10px; background-color: #ffcccc; width:100%; border:solid 1px #000"><h2>Notification message</h2><h3>'.trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__)).' -- Line : '.(__LINE__ +1).' -- Function : '.__FUNCTION__.'</h3>'."\n".'<pre>'.print_r($Array,1).'</pre></div>');
    	pp($Array); // check assign worked
    }
    
	public function testPPAssocArray()
    {
    	$AssocArray = array('TEST 1'=>'OK', 'TEST 2'=>'FAIL', 'TEST 3'=>'ERROR');
    	$this->expectOutputString('<div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding:10px; background-color: #ffcccc; width:100%; border:solid 1px #000"><h2>Notification message</h2><h3>'.trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__)).' -- Line : '.(__LINE__ +1).' -- Function : '.__FUNCTION__.'</h3>'."\n".'<pre>'.print_r($AssocArray,1).'</pre></div>');
    	pp($AssocArray); // check assign worked
    }
    
	public function testPPObject()
    {
    	$Object = (object) array(
		     'foo' => (object) array(
		          'bar' => 'baz',
		          'pax' => 'vax'
		      ),
		      'moo' => 'ui'
	   	);
    	
    	$this->expectOutputString('<div style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding:10px; background-color: #ffcccc; width:100%; border:solid 1px #000"><h2>Notification message</h2><h3>'.trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__)).' -- Line : '.(__LINE__ +1).' -- Function : '.__FUNCTION__.'</h3>'."\n".'<pre>'.print_r($Object,1).'</pre></div>');
    	pp($Object); // check assign worked
    }
    
    public function testTrace(){
    	$Object = (object) array(
		     'foo' => (object) array(
		          'bar' => 'baz',
		          'pax' => 'vax'
		      ),
		      'moo' => 'ui'
	   	);
    	
	   	trace($Object);
    	$this->expectOutputString("<pre>Array\n(\n    [0] => <span>".trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__))." -- Line : ".(__LINE__ -1)." -- Function : ".__FUNCTION__."\n<pre>".print_r($Object,1)."</pre></span>\n)\n</pre>");
    	showTrace();
    }
    
    public function testFormatArray(){
    	
    	$AssocArray = array('TEST 1'=>'OK', 'TEST 2'=>'FAIL', 'TEST 3'=>'ERROR');
    	
    	$r = '<h4>TEST</h4><blockquote><br/>';
    	foreach($AssocArray AS $key=>$val){
    		$r .= "['".$key."']=&nbsp;&nbsp;&nbsp;".$val."<br />";
    	}
		$r .= "</blockquote>";
		
    	$this->expectOutputString($r);
    	echo formatArray($AssocArray, 'TEST');
    }
    
    public function  testParseArray(){
    	$string = "TEST 1<bt>TEST 2</bt>TEST 3";
    	$this->assertEquals('TEST 2', parseArray($string, '<bt>','</bt>'));
    	$this->assertEquals('<bt>TEST 2</bt>', parseArray($string, '<bt>','</bt>',0));
    }
    
	public function testStripElements(){
		$AssocArray = array('TEST 1'=>'OK', 'TEST 2'=>'FAIL', 'TEST 3'=>'ERROR');
		$elemants = 'TEST 1,TEST 3';
		
		$this->assertEquals('OK', $AssocArray['TEST 1']);
		$this->assertEquals('FAIL', $AssocArray['TEST 2']);
		$this->assertEquals('ERROR', $AssocArray['TEST 3']);
		stripElements($elemants,$AssocArray);
		$this->assertFalse(isset($AssocArray['TEST 1']));
		$this->assertTrue(isset($AssocArray['TEST 2']));
		$this->assertFalse(isset($AssocArray['TEST 3']));
	}
	
	public function testCreateDateField(){
		$day = 12;
		$month = 10;
		$year = date('Y');
		
		$fields = createDateField( "$day/$month/$year", 10, 2003);
		
		//test the array 
		$this->assertTrue(isset($fields['day']));
		$this->assertTrue(isset($fields['month']));
		$this->assertTrue(isset($fields['year']));
		//test if content selected 
		$months = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		$this->assertRegExp("/<option value=\"".$day."\" selected=\"selected\">".$day."/", $fields['day']);
		$this->assertRegExp("/<option value=\"".$month."\" selected=\"selected\">".$months[$month]."/", $fields['month']);
		$this->assertRegExp("/<option value=\"".$year."\" selected=\"selected\">".$year."/", $fields['year']);
		
	}
	
	public function testFormatDateUI(){
		$day = 12;
		$month = 10;
		$year = date('Y');
		$this->assertEquals($year.'-'.$month.'-'.$day.' 00:00:00', formatDateUI("$day/$month/$year"));
	}
	
	public function testFormatDateResponce(){
		$name = 'Doom';
		
		$day = 12;
		$month = 10;
		$year = date('Y');
		
		$DateFields[$name.'__day']  = $day;
		$DateFields[$name.'__month'] = $month;
		$DateFields[$name.'__year'] = $year;
		
		$response = formatDateResponce($name,$DateFields);
		
		$this->assertEquals($year.'-'.$month.'-'.$day.' 00:00:00',$response);
		$this->assertEquals($year.'-'.$month.'-'.$day.' 00:00:00',$DateFields[$name]);
		
		$this->assertFalse(isset($DateFields[$name.'__day']));
		$this->assertFalse(isset($DateFields[$name.'__month']));
		$this->assertFalse(isset($DateFields[$name.'__year']));
		
	}
	
	public function testConvertUIdate(){
		
		$day1 = 11;
		$month1 = 10;
		$year1 = date('Y');
		
		$day2 = 12;
		$month2 = 10;
		$year2 = date('Y');
		
		$date1['start'] = "$day1/$month1/$year1";  
		$date1['end'] = "$day2/$month2/$year2";
		 
		//test if only one fields is required 
		convertUIdate('start' , 0  ,$date1);
		$this->assertEquals($year1.'-'.$month1.'-'.$day1.' 00:00:00', $date1['start']);
		convertUIdate(0 , 'end'  ,$date1);
		$this->assertEquals($year2.'-'.$month2.'-'.$day2.' 00:00:00', $date1['end']);
		
		$date1['start'] = "$day1/$month1/$year1";  
		$date1['end'] = "$day2/$month2/$year2";
		
		//test if both dates are correct 
		convertUIdate('start' , 'end'  ,$date1);
		$this->assertEquals($year1.'-'.$month1.'-'.$day1.' 00:00:00', $date1['start']);
		$this->assertEquals($year2.'-'.$month2.'-'.$day2.' 00:00:00', $date1['end']);
		
		$date1['start'] = "$day2/$month2/$year2";
		$date1['end'] = "$day1/$month1/$year1";  
		
		//test if start date is greater the end that date get swapped
		convertUIdate('start' , 'end'  ,$date1);
		$this->assertEquals($year1.'-'.$month1.'-'.$day1.' 00:00:00', $date1['start']);
		$this->assertEquals($year2.'-'.$month2.'-'.$day2.' 00:00:00', $date1['end']);
	}
	
	public function testDatabaseToUI(){
		$day = 12;
		$month = 10;
		$year = date('Y');
		
		$this->assertEquals("$day/$month/$year" , databaseToUI($year.'-'.$month.'-'.$day.' 00:00:00'));
	}
	
	public function testFileext(){
		$this->assertEquals("doc" , fileExt('application/msword'));
		$this->assertEquals("pdf" , fileExt('application/pdf'));
		$this->assertEquals("docx" , fileExt('application/vnd.openxmlformats-officedocument.wordprocessingml.document'));
	}
	
	public function testConvertDecimalToTime(){
		$Minutes = convertDecimalToMinutes(1); 
		$this->assertEquals("60" , $Minutes);//test convertDecimalToMinutes
		$this->assertEquals("1:00" ,convertMinutes2Hours($Minutes)); // test convertMinutes2Hours
		
		$Minutes = convertDecimalToMinutes(.50);
		$this->assertEquals("30" , $Minutes);
		$this->assertEquals("0:30" ,convertMinutes2Hours($Minutes));
		
		$Minutes = convertDecimalToMinutes(.25);
		$this->assertEquals("15" , $Minutes);
		$this->assertEquals("0:15" ,convertMinutes2Hours($Minutes));
		
		$Minutes = convertDecimalToMinutes(1.25);
		$this->assertEquals("75" , $Minutes);
		$this->assertEquals("1:15" ,convertMinutes2Hours($Minutes));
		
		$Minutes = convertDecimalToMinutes(2.25);
		$this->assertEquals("135" , $Minutes);
		$this->assertEquals("2:15" ,convertMinutes2Hours($Minutes));
		
		$this->assertEquals("30" , convertDecimalToMinutes(.50));
		$this->assertEquals("15" , convertDecimalToMinutes(.25));
		$this->assertEquals("75" , convertDecimalToMinutes(1.25));
	}
	
}


