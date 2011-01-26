<?php
/**
 * Email Class,
 * <br />
 * This class is used create and send email, can also record emails in a db, and can use the template class to format Html <br />
 * 
 * 
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 * @subpackage Base
 */

/**
 * Pear Mail class 
 */
require_once ('Mail.php');
/**
 * Pear Mime class 
 */
require_once ('Mail/mime.php');

/**
 * This class is used create and send email<br />
 * 
 * @package PeopleScope
 * @subpackage Base
 */
class email{

	/**
	 * Carrage return
	 * @var String 
	 */
	var $crlf = "\n";
	
	/**
	 * Set Mime Type
	 * @var unknown_type
	 */
	var $mime;
	
	/**
	 * Mail Object
	 * @var Object
	 */
	var $mail;
	
	/**
	 * Template Object
	 * @var Object
	 */
	var $template;
	
	/**
	 * Mail Header information 
	 * @var Araay
	 */
	var $header;

	/**
	 * Constructor for Email php5 
	 * 
	 * @param unknown_type $type
	 */
	function _construct(){
			$this->email();
	}
	
	/**
	 * Constructor for Email php4 
	 * 
	 */
	function email(){
			$this->mime = new Mail_mime($this->crlf);
			if (PEAR::isError($this->mime)) { pp($this->mime->getMessage(),1);}

	}
	/**
	 * converts the HTML version of the email to be sent to be appended to the email 
	 * 
	 * @param String $html
	 */
	function append_html($html) {
		$this->mime->setHTMLBody($html);
	}
	
	/**
	 * converts the TEXT version of the email to be sent to be appended to the email 
	 * 
	 * @param String $text
	 */
	function append_text($text) {
		$this->mime->setTXTBody($text);
	}
	
	/**
	 * converts the TEXT version of the email to be sent to be appended to the email 
	 * 
	 * @param String $file file path to the, or file content
	 * @param String $type Mime type of the thaile attached 
	 */
	function append_file($file, $type){
		$this->mime->addAttachment($file, $type);
	}
	
	/**
	 * Create a HTML email using the base template class 
	 * 
	 * @param String $template path to template
	 * @param String $content array of assigned varable converstion 
	 * @see template::assignArray
	 */
	function setHTMLtemplate($template, $content){
		$this->template = new template;
		$this->template->set($template);
		$this->template->assignArray($content);
		$this->mime->setHTMLBody($this->template->fetch());
	}
	
	/**
	 * Will create and output from Form Class standardised format String  
	 * To create a TEXT email in a reable format
	 * 
	 * @todo confirm this is correct 
	 * @param String $table Form Class standardised format String
	 * @param String $content Content 
	 */
	function setTXTtemplate($table,$content){
		$table = trim($table);
		$rows = explode("\n", $table);

		$str = '';
		foreach ($rows AS $value){
			if ($value == "------" OR $value == "\n"){
				next();
			}
			//Nulled Error as as some feilds may not need validating
			@list($info,$validation) = explode(':::', $value);
			list($lable,$field) = explode(':', $info);

			$str .= $lable. "= ";
			if (isset($content[$field])){
				if (is_array($content[$field])){
					$str .=  implode("," , $content[$field]) ."\n";
				}else{
					$str .=  $content[$field] ."\n";
				}
			}
		}

		$this->mime->setTXTBody($str);
	}
	
	/**
	 * Set the Sender info for header
	 * 
	 * @param String $from email of the sender 
	 */
	function sender($from){
			$this->header['From'] = $from;
	}
	
	/**
	 * Set the Subject line fo the email 
	 * @param String $subject
	 */
	function subject($subject){
			//$this->header['Subject'] = $subject;
			$this->mime->setSubject($subject);
	}
	
	/**
	 * Set all other Header information as required 
	 * 
	 * @param Array $header header param to header value
	 */
	function setHeader($header){
		if (!empty($header)){
			if (is_array($header)){
				$this->header = $header;
				return true;
			}
		}

		return false;
	}
	
	/**
	 * Record email into the database 
	 * 
	 * @param String $table table to store the email  
	 * @param String $content content of the email 
	 * @param String $form_name identifer of record, Default URI sent from  
	 */
	function recordEmail($table, $content, $form_name = NULL ){

		$this->dbase = global_db();

		if(empty($form_name)){
			$form_name = $_SERVER["REQUEST_URI"];
		}

		$create_date = dbase_date_format();

		//$set_fields = "sent_to, subject_line, form_name, creat_date";
		$set_values = array("sent_to"=>$this->lastsent , "subject_line"=>$this->header['Subject'], "form_name"=>$form_name, "create_date"=>$create_date);

		$contentValueArray = array_merge($content, $set_values);
		$fieldcount = count($contentValueArray);
		$columnsArray = array_keys($contentValueArray);
		$columnStr = implode(',', $columnsArray);

		$sql = "INSERT INTO ". $table ." (".$columnStr.") values (".str_repeat  ( "?, " , $fieldcount-1 )."?);";

		$recordinsert = $this->dbase->Prepare($sql);
		$ok = $this->dbase->Execute($recordinsert, $contentValueArray);

		if (!$ok) {
			pp($this->dbase->ErrorMsg(),1);
			return false;
		}

		return true;
	}
	
	/**
	 * compile and Send email  
	 *  
	 * @param String $to Email address to send too;
	 */
	function send($to){

			$this->lastsent = $to;
			$body = $this->mime->get();
			$hdrs = $this->mime->headers($this->header);
			$mail = Mail::factory(MAILTYPE,array('debug' => true));
			if (PEAR::isError($this->mail)) { return pp("Error:".$this->mail->getMessage());}
			return $mail->send($this->lastsent, $hdrs, $body);
	}
}
?>