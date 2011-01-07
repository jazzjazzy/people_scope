<?php
/**
 * Tools Function, 
 * <br />
 * At set of general tool to help with development<br /> 
 * 
 *  
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 * @subpackage Base
 */


/**
 * Global style for error messages 
 * @global string $GLOBALS['style'] 
 */
$GLOBALS['style'] = "style=\"font-family: Arial, Helvetica, sans-serif; font-size: 11px; padding:10px; background-color: #ffcccc; width:100%; border:solid 1px #000\"";
/**
 * Global trace debugging
 * @global string $GLOBALS['trace'] 
 */
$GLOBALS['trace']=array();

/**
 * A debuging tool 
 * 
 * This will produce a error message to the screen displaying the value enter
 * it will take in vars | arrays | object 
 * it will show the page | Line | function that the echo accured 
 * and can be turned off thought the constant TURN_ON_PP
 * 
 * @param Mixed $var Value that will be echoed out  
 * @param bool $tracer true display to screen, false store in global $trace 
 * return void
 */
Function pp($var, $tracer=false){
	if (TURN_ON_PP){
		global $style;
		$info = debug_backtrace();
		$line =  trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $info[0]['file']))." -- Line : ".$info[0]['line']." -- Function : ".@$info[1]['function'];

		if(is_array($var) || is_object($var)){
			$responce = "<div $style><h2>Notification message</h2><h3>".$line."</h3>\n<pre>".html_entity_decode( print_r($var,true))."</pre></div>";
		}else{
			$responce = "<div $style><h2>Notification message</h2><h3>".$line."</h3>\n<pre>$var</pre></div>";
		}

		if($tracer){
			global $trace;
			$trace[] = $responce;
		}else{
			echo $responce;
		}
	}
}

/**
 * Debuging tool that that will store the outcomes into a $GLOBAL['trace'] var
 * 
 * @global Array $GLOBAL['trace']
 * @see showTrace for output
 * @param Mixed $var Value that will be echoed out
 * @param Bool $newline If type strip tags from string
 */

function trace($var, $newline=true) {
	global $trace,  $style;
	$info = debug_backtrace();
	if (!$newline && is_string($var)) $var = trim(strip_tags($var));
	$line =  trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $info[0]['file']))." -- Line : ".$info[0]['line']." -- Function : ".@$info[1]['function'];
	$responce = "<span>".$line."\n<pre>".print_r($var,true)."</pre></span>";
	$trace[] = $responce;

}

/**
 * Output $GLOBAL['trace'] Trace
 * 
 * @global Array $trace
 * return Void
 */
function showTrace() {
	global $trace;
	echo "<pre>".print_r($trace,1)."</pre>";
}

/**
 * Will return a html form with all the results of the $GLOBAL vars 
 * $_POST, $_GET, $_COOKIE, $_SESSION, $trace
 * @return String html vars
 */
function showVars(){
	if(!DEBUG){
		return;
	}
	$ret = '<style type="text/css">
		.debug h4 {	margin-top: 0;
					margin-bottom:0;
		}
		.debug h4:before {content: "+: ";}
		.debug {float:none;
				font-size: 0.8em;
				background:#fff;
				color: #999999;
				height: 1.2em;
				width: 1em;
				border: 1px solid #999999;
				overflow: hidden;
				position:absolute;
				top:0px;
				left:0px;
		}
		.debug:hover {	height: auto;
						width: auto;
						overflow: auto;
						width:100%;}
		</style>';
	global $trace;
	$ret .=  '<div class="debug">';
	$ret .= formatArray($_POST,'_POST');
	$ret .= formatArray($_GET,'_GET');
	$ret .= formatArray($_COOKIE,'_COOKIE');
	$ret .= formatArray(@$_SESSION,'_SESSION');
	$ret .= formatArray($trace,'TRACE');
	$ret .= '</div>';

	return $ret;
}

/**
 * Will input an associate array and output in a readable format 
 * @param Array $array Array to read 
 * @param String $name name you want to give the array 
 */
function formatArray($array, $name){

	$line='';

	$line .= "<h4>".$name."</h4><blockquote><br/>";
	if (isset($array)){
		foreach($array AS $key=>$value){
			$line .= "['".$key."']=&nbsp;&nbsp;&nbsp;". print_r($value,1)."<br />";
		}
	}
	$line .= "</blockquote>";
	return $line;
}


/**
 * Will take in a String and a start and end delimiter and will return the content between the delimiter
 * 
 * @param String $string String to parse 
 * @param String $beg_tag start delimiter 
 * @param String $close_tag end delimiter 
 * @param Bool $remove_tag true return content without delimiter
 * @return Array all content found between delimiters
 */
function parseArray($string, $beg_tag, $close_tag, $remove_tag=true){
	$on = preg_match_all("($beg_tag(.*)$close_tag)siU", $string, $matching_data);
	
	if($remove_tag){
		return $matching_data[1][0];
	}

    return $matching_data[0][0];
}

/**
 * Will take in a list of accociated array names and remove 
 * from that array via referance 
 * 
 * @param String $elements Comma seperated list of names 
 * @param Array $array The array to remove them from via referance 
 */
function stripElements($elements, &$array){
	$elementsArray = explode(',', $elements);
	
	foreach($elementsArray AS $value){
		unset($array[$value]);
	}
}
/**
 * This will take in a name for the field types and a date that you wish and format 
 * an option list for select boxes, and return them in an associated array for year, month and day
 * 
 * @param String $name Name used for selection box 
 * @param String $date Date That should be selected
 * @param Integer $yearRange How many years should be displayed
 * @param Integer $startyear What year should the list start at
 * 
 * @return an Array of day,month,Year holding the required option list for select box 
 */
function createDateField($date, $yearRange = 10, $startyear = NULL){

	if (!isset($startyear)){
		$startyear = date('Y');
	}

	//lets do some cleaning up on the date format to make it easy to work with.
	$date = trim($date);
	$date = str_replace(array('\\','/'), '-', $date);
	$date = str_replace(' ', '', $date);

	list($day, $month, $year) = explode('-', $date);

	$months = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	for($i = 1; $i < count($months) ; $i++){
		$selectmonth = ($i == $month)? ' selected="selected"' : '';
		@$dateRet['month'] .= "<option value=\"$i\"$selectmonth>".$months[$i]."</option>\n";
	}

	for($j = 1; $j < 31 ; $j++){
		$selectday = ($j == $day)? ' selected="selected"' : '';
		@$dateRet['day'] .= "<option value=\"$j\"$selectday>$j</option>\n";
	}

	for($k = $startyear; $k < ($startyear+$yearRange) ; $k++){
		$selectyear = ($k == $year)? ' selected="selected"' : '';
		@$dateRet['year'] .= "<option value=\"$k\"$selectyear>$k</option>\n";
	}

	return $dateRet;
}

/**
 * Will take a Au date from jQuery Datepicker and convert to a database date format
 * 
 * @link http://docs.jquery.com/UI/Datepicker
 * @link http://docs.jquery.com/UI/Datepicker/formatDate
 * @param String $date database date format
 */
function formatDateUI($date){
	list($day, $month, $year) = explode('/', $date);
	$year = (strlen($year) == 2)?'20'.$year: $year;
	return $year.'-'.$month.'-'.$day.' 00:00:00' ;

}

/**
 * This will take in an Array or if no array give will default to the $_REQUEST
 * global looking for accociated name type and remove the elements and replace the with a $name field with a value of the compiled date,
 * if none found then will put in todays date 
 * accociated name types :<br />
 * $name.'__day'<br />
 * $name.'__month'<br />
 * $name.'__year'<br />
 * <br />
 * $name = $name.'__year-'$name.'__month-'$name.'__day 00::00::00' <br />
 * <br />
 * @param String $name the name ofthe fields we are looking for
 * @param Array &$listArray alternate array to look at, $_REQUEST by default
 * @return String database date format
 */
function formatDateResponce($name, &$listArray=NULL){
	if (!$listArray){
		$listArray = $_REQUEST;
	}

	if(isset($listArray[$name.'__day'])){
		$nameDay = $listArray[$name.'__day'];
		unset($listArray[$name.'__day']);
	}else{
		$nameDay = date('j');
	}

	if(isset($listArray[$name.'__month'])){
		$nameMonth = $listArray[$name.'__month'];
		unset($listArray[$name.'__month']);
	}else{
		$nameMonth = date('n');
	}

	if(isset($listArray[$name.'__year'])){
		$nameYear = $listArray[$name.'__year'];
		unset($listArray[$name.'__year']);
	}else{
		$nameYear = date('Y');
	}

	$listArray[$name] = ($nameYear.'-'.$nameMonth.'-'.$nameDay.' 00:00:00' );

	return $listArray[$name];
}

/**
 * Will convert the output from a Jquery UI date field format 
 * format must be in Au date format
 * @link http://docs.jquery.com/UI/Datepicker
 * @link http://docs.jquery.com/UI/Datepicker/formatDate
 * 
 * @todo remember how this works :S
 * @param String $start start date
 * @param String $end end date
 * @param Array &$ARRAY returns by referance
 */
function convertUIdate($start, $end, &$ARRAY){
	$startdate = (empty($start))? '' : $ARRAY[$start];
	$enddate = (empty($end))? '' : $ARRAY[$end];
	
	if(empty($startdate) && empty($enddate)){
		return;
	}
	
	if(!empty($startdate)){
		list($sd,$sm,$sy) = explode('/', $startdate);
		$stimestamp = mktime(0,0,0,$sm,$sd,$sy);
		if(empty($enddate)){
			$ARRAY[$start] = date('Y-m-d 00:00:00', $stimestamp);
			return;
		}
	}
	
	if(!empty($enddate)){
		list($ed,$em,$ey) = explode('/', $enddate);
		$etimestamp = mktime(0,0,0,$em,$ed,$ey);
		if(empty($startdate)){
			$ARRAY[$end] = date('Y-m-d 00:00:00', $etimestamp);
			return;
		}
	}
	
	if ($stimestamp < $etimestamp){
		$ARRAY[$start] = date('Y-m-d 00:00:00', $stimestamp);
		$ARRAY[$end] = date('Y-m-d 00:00:00', $etimestamp);
	}else{
		$ARRAY[$end] = date('Y-m-d 00:00:00', $stimestamp);
		$ARRAY[$start] = date('Y-m-d 00:00:00', $etimestamp);
	}

}

/**
 * converts a database date value and conversts to Au date format
 * 2001-04-02 = 02/04/2001
 * @param String $dbdate database date format yyyy-mm-dd
 * @return String Date dd/mm/yyyy
 */
function databaseToUI($dbdate){
	
	list($dbdate) = explode(' ' , $dbdate);
	
	list($year, $month, $day) = explode('-' , $dbdate);
	
	return  $day ."/".$month."/".$year; 
}

/**
 * return file extention based on Mime type for common doc types
 * doc, docx, pdf
 *  
 * @param String $type Mime type
 * @return String Document extention
 */
function fileExt($type){
	$ext = false;
	switch($type){
		case 'application/msword' : $ext = 'doc'; break;
		case 'application/pdf' : $ext = 'pdf'; break;
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' : $ext = 'docx'; break;
	}
	return $ext;
}
/**
 * Will take in a percent amount and return values in mins 
 * eg. 50% = 30mins
 * @param Integer $percent
 * @return Integer Minutes
 */
function convertDecimalToMinutes($percent){
	list($hours, $minutes)= explode('.', number_format($percent,2));
	$minutes = ceil(($minutes*60)/100);
	$in_minutes=($hours*60)+$minutes;	
	return $in_minutes;
}

/**
 * Will take in a amount in minutes and return the value in the amount of hours : minute
 * 
 *  124 minutes = 2:04
 *  
 * @param Integer $Minutes
 * @return String hh:mm
 */
function convertMinutes2Hours($Minutes)
{
	if ($Minutes < 0)
	{
		$Min = Abs($Minutes);
	}
	else
	{
		$Min = $Minutes;
	}
	$iHours = Floor($Min / 60);
	$Minutes = ($Min - ($iHours * 60)) / 100;
	$tHours = $iHours + $Minutes;
	if ($Minutes < 0)
	{
		$tHours = $tHours * (-1);
	}
	$aHours = explode(".", $tHours);
	$iHours = $aHours[0];
	if (empty($aHours[1]))
	{
		$aHours[1] = "00";
	}
	$Minutes = $aHours[1];
	if (strlen($Minutes) < 2)
	{
		$Minutes = $Minutes ."0";
	}
	$tHours = $iHours .":". $Minutes;
	return $tHours;
}

function Box($content, $header, $discription){
	$html ='<div  class="tab">
			<div id="tab-header">
				<h1>'.$header.'</h1>
			</div>
			<div id="tab-text">
				'.$discription.'
			</div>
			<div id="tab-body">
				'.$content.'<br class="clear">
			</div>
			<div id="tab-foot"></div>
		</div>';
	return $html;
	
}

?>