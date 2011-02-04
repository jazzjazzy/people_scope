<?php
/**
 * Question Class 
 * <pre>
 * This class is based on the table question
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 1.1 of the Framework generator
 * @package PeopleScope
 */

class question {
	
	/**
	 * Connect to PDO object through database class
	 * @var Object
	 */
	private $db_connect;
	
	/**
	 * Database class object 
	 * @var Object
	 */
	private $db;
	
	/**
	 * Table class object  
	 * @var Object
	 */
	public $table;
	
	/**
	 * Template class object 
	 * @var Object
	 */
	public $template;

	/**
	 * Array of field used in the database if not in this list is dropped from insert or update
	 * @var Array
	 */
	private $fields =array('question_id', 'question_catagory_id', 'advertisement_id', 'label', 'type');
	
	/**
	 * Array of feilds require information when validating 
	 * @var Array|null
	 */
	private $fields_required = NULL;
	
	/**
	 * Array of feilds and there types that are check when validating 
	 * @var Array|null
	 */
	private $fields_validation_type = array ('question_id'=>'INT', 'question_catagory_id'=>'INT', 'advertisement_id'=>'INT', 'label'=>'TEXT', 'type'=>'TEXT');
	
	/**
	 * Array use to store any error found during Validation function 
	 * @see Validation()
	 * @var Array
	 */
	private $validation_error = array();
	
	/**
	 * Contructor for this method 
	 * 
	 * <pre>
	 * The constructor will setup the required object for this class 
	 * will initiate the database class, the table class and the template 
	 * for this class to use
	 * 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @see db::
	 * @see table
	 * @see template
	 */
	public function __construct(){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
		
		$this->table = new table();
		$this->template = new template();
	
	}
	
	/**
	 * Show will pull a list from the corresponding Question question
	 * 
	 * <pre>
	 * This Method will produce a list of all the element corresponding to the result of Question
	 * 
	 * I will only pull rows that are not considered delete 
	 * eg. the delete_date field is not "0000-00-00 00:00:00" or set to NULL
	 * 
	 * The parameter $filter expects an array with the key being the field to look for and the
	 * value being the the information to filter on
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @param String $orderby Which single field is used to oder the output
	 * @param String $direction Which direction os required for the orderby output  
	 * @param Array $filter A array of fields to filter, key=$val set (eg array('tile='=>'this title'))  
	 */
	Private function lists($orderby=NULL, $direction='ASC', $filter=NULL){
		
		$sql = "SELECT question_id,
question_catagory_id,
advertisement_id,
label,
type FROM question WHERE (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)";
		
		if(is_array($filter)){
		  	foreach($filter AS $key=>$value){
		  		if ($value != 'NULL'  && !empty($value)){
		  			$sql .=  " AND ". $value; 
		  		}
		  	}
		}
		  
		if($orderby){
		  	$sql .= " ORDER BY ". $orderby." ".$direction;
		}
		 
		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}
		  
		return $result;
	}
	
	/**
	 * This method will take an array and insert it in the database
	 * 
	 * <pre>
	 * This method will insert the formated information into a database, the format for the array 
	 * should be an associated array being the first key should be the table inserting with the keys 
	 * for child array the fields that are being inserted too and the values to insert
	 * 
	 * Array
	 *(
	 *	[users] => Array
	 *		(
	 *			[name] => Dave
	 *			[surname] => Smith
	 *			[email] => dave@dave.com
	 *		)	
	 *	[staff] => Array
	 *		(
	 *			[staff_id] => 1245
	 *			[office_number] => 22
	 *			[drown_code] => bee223
	 *		)
	 * )
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * 
	 *</pre>
	 *
	 * @param Array $source
	 * 
	 * @return Integer Return last inserted primary id  
	 */
	Private function create($source){
			try{
				$this->db_connect->beginTransaction();
				
				foreach($source['question'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO question (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['question'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
					$pid = $this->db->insert($sql, $exec); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				$this->db_connect->commit();
			}

			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}

			return $pid;
	}
	
	
	/**
	 * This method will return information as row
	 * 
	 * <pre>
	 * This method is you to get a single row of information from the database 
	 * based ith the primary id and return it as an array 
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 *
	 * @param Integer $id The primary id of the row to show 
	 */
	Private function read($id){
	
		$sql = "SELECT question_id,
question_catagory_id,
advertisement_id,
label,
type FROM question WHERE question_id = ". $id ." AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}

			return $result[0];
		
			
	}
	
	/**
	 * This method will take an array and update a row in the database
	 * 
	 * <pre>
	 * This method will update the formated information into the database, the format for the array 
	 * should be an associated array being the first key should be the table to be updated with the keys 
	 * for child array the fields that are being updated too and the values to be updated
	 * 
	 * Array
	 *(
	 *	[users] => Array
	 *		(
	 *			[name] => Dave
	 *			[surname] => Smith
	 *			[email] => dave@dave.com
	 *		)	
	 *	[staff] => Array
	 *		(
	 *			[staff_id] => 1245
	 *			[office_number] => 22
	 *			[drown_code] => bee223
	 *		)
	 * )
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * 
	 *</pre>
	 *
	 * @param Array $source
	 * 
	 * @return Integer Return last inserted primary id  
	 */
	Private function update($source, $id){
			try{
				$this->db_connect->beginTransaction();
				
				foreach($source['question'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE question SET ".implode(', ',$field)." WHERE question_id =". $id;

				foreach($source['question'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
						$pid = $this->db->update($sql, $exec); 
				}catch(CustomException $e){
						throw new CustomException($e->queryError($sql));
				}
				$this->db_connect->commit();	
			}
			
			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}
			
			header('Location:question.php?action=show&id='.$id);
			
	}
	
	/**
	 * This method will update a row and make the recored as deleted
	 * 
	 * <pre>
	 * This method will take the id and set the delete_date field to 
	 * the current datetime, which will marking it as deleted
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 *
	 * @param Integer $id The primary id of the row to show
	 * @return Boolean success or failed
	 */
	 
	Private function remove($id){
			if(empty($id)){
				return false;
			}
			
			$sql = "UPDATE question SET delete_date=NOW() WHERE question_id =". $id;

			try{
				$result = $this->db->update($sql);
			}catch(CustomException $e){
				echo $e->queryError($sql);
			}
			return true;
	}
	
	
	/******************* END CRUD METHOD*************************/
	
	
	/**
	 * Show list of information corresponding the to this class 
	 * 
	 * <pre>This Method will produce a list of all the element corresponding to the result of Question
	 * using the base/table.class.php file, which will format the list into a filtable table that
	 * uses ajax class to change the content on filtering
	 * 
	 * There are to response type for this table for the parameter $type 
	 * 
	 * 	TABLE = Will return the content in a table with a filter row and a heading row
	 * 	AJAX = Will return just the content after evaluating the filter or heading infomation
	 * 
	 * The parameter $filter expects an array with the key being the field to look for and the
	 * value being the the information to filter on
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @param String $type Option of type of response for the output of the list  
	 * @param String $orderby Which single field is used to oder the output
	 * @param String $direction Which direction os required for the orderby output  
	 * @param Array $filter A array of fields to filter, key=TEXT set (eg array('tile='=>'this title'))  
	 */
	
	public function getQuestionList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		
		$result = $this->lists($orderby, $direction, $filter);
		
		$this->table->removeColumn(array('question_id'));
		
		switch(strtoupper($type)){
		
			case 'AJAX' : $this->table->setRowsOnly(); 
						  $this->table->setIdentifier('question_id');
						  $this->table->setIdentifierPage('question');
						  echo $this->table->genterateDisplayTable($result);
						  
				BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'question_id'=>'Question Id',
'question_catagory_id'=>'Question Catagory Id',
'advertisement_id'=>'Advertisement Id',
'label'=>'Label',
'type'=>'Type'));
				
				$this->table->setFilter(array(	
						'question_id'=>'TEXT',
'question_catagory_id'=>'TEXT',
'advertisement_id'=>'TEXT',
'label'=>'TEXT',
'type'=>'TEXT'));
				
				$this->table->setIdentifier('question_id');
				
				$this->template->content(Box($this->table->genterateDisplayTable($result),'Question List', 'Shows the current listings for the Question. To create a new Listing <a href="question.php?action=create">Click Here</a>'));
				
				$this->template->display();
		}
	}
	
	
	/**
	 * Show details of a single Question from the question
	 * 
	 * <pre>This method will return a template page of the information requested 
	 * the method use the template class to format the information ready to display the 
	 * the user 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id the primary id of the row to show 
	 */
	Public function showQuestionDetails($id){
		$fieldMember = $this->read($id);
		
		$this->template->page('question.tpl.html');
		
		$this->templateQuestionLayout($fieldMember);

		//if($this->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='question.php?action=edit&id=".$id."'\">Edit</div>");
		//}
		
		echo $this->template->fetch();	
	}
		
	
	/**
	 * Show the details ready to edit of a single Question from the question 
	 * 
	 * <pre>
	 * This method is used to display and editable page to the use, so that they 
	 * maybe able to edit any of the fields releated to the row in question. 
	 * The method uses the template class to format the information ready to display the 
	 * the user 
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>     
	 * </pre>
	 * @param Integer $id The primary id of the row to show 
	 */
	Public function editQuestionDetails($id){
		
		$fieldMember = $this->read($id);
		
		$name = 'editQuestion';
		
		$this->template->page('question.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="question.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
		$this->templateQuestionLayout($fieldMember, true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='question.php?action=show&id=".$id."'\">Cancel</div>");
		
		$this->template->display();
	}
	
	/**
	 * update the information in a single Question from the question 
	 * 
	 * <pre>
	 * This method is used to take the information from editDepartmentDetails and try to validate it thought the 
	 * validation method and on success will format it ready for input into the datebase through the update method 
	 * 
	 * if the validate fails then the user is show a page that mimics the editDepartmentDetails method and point out 
	 * error in there input 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>    
	 * </pre>
	 * 
	 * @see editDepartmentDetails()
	 * @see Validate()
	 * @see update()
	 * @param Integer $id The primary id of the row to updated 
	 */
	Public function updateQuestionDetails($id){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'question';

				$save[$table]['question_catagory_id'] = $request['question_catagory_id'];
				$save[$table]['advertisement_id'] = $request['advertisement_id'];
				$save[$table]['label'] = $request['label'];
				$save[$table]['type'] = $request['type'];
				
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				header('Location: question.php?action=show&id='.$id);
			}else{
				
				$fieldMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editQuestion';
		
				$this->template->page('question.tpl.html');
				
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $error[$key])."</spam>");
				}
				
				$this->template->assign('FORM-HEADER', '<form action="question.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateQuestionLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='question.php?action=show&id=".$id."'\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	/**
	 * This method will provide a page to the to add a single row Question to the question table
	 * 
	 * <pre>
	 * The method using the template class to format the information ready to display the 
	 * the user, so that they may be able to add any of the fields releated to a row in the database. 
	 * The method uses the template class to format the information ready to display the 
	 * the user
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>   
	 * </pre>  
	 */
	Public function createQuestionDetails(){
		
		$name = 'createQuestion';
		
		$this->template->page('question.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="question.php?action=save" method="POST" name="'.$name.'">');
		
		$this->templateQuestionLayout('', true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='question.php?action=list'\">Cancel</div>");
		

		$this->template->display();
	} 
	
	/**
	 * save the information in a single Question to the question table 
	 * 
	 * <pre>
	 * This method is used to take the information from createDepartmentDetails and try to validate it thought the 
	 * validation method and on success will format it ready for inserted into the datebase through the insert method 
	 * 
	 * if the validate fails then the user is show a page that mimics the createDepartmentDetails method and point out 
	 * error in there input 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>   
	 * </pre>
	 * 
	 * @see createDepartmentDetails()
	 * @see Validate()
	 * @see update()
	 * @param Integer $id The primary id of the row to updated 
	 */
	Public function saveQuestionDetails(){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'question';

				$save[$table]['question_catagory_id'] = $request['question_catagory_id'];
				$save[$table]['advertisement_id'] = $request['advertisement_id'];
				$save[$table]['label'] = $request['label'];
				$save[$table]['type'] = $request['type'];
				
				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				$id = $this->create($save);
				header('Location: question.php?action=show&id='.$id);
			}else{
			
				$fieldMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createQuestion';
	
				$this->template->page('question.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="question.php?action=save" method="POST" name="'.$name.'">');
		
				$this->templateQuestionLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='question.php\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	/**
	 * Set a row to be marked as deleted 
	 * 
	 * <pre>
	 * This method will take the id and set the delete_date field to 
	 * the current datetime, which will marking it as deleted
	 *
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id The primary id of the row to marked as delete 
	 */
	Public function deleteQuestionDetails($id){
		$this->remove($id);
		header('Location: question.php');
	}
	
	/**
	 * This method assigns the associate array values to the template
	 * 
	 * <pre>
	 * This method is used to incorperate the standards elements of the templates to a single 
	 * function across all tempatled methods
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>  
	 * </pre>
	 * @todo find out what $inputArray is used for 
	 * 
	 * @param Array $fielddata An associative array of fields that need to be assigned to the template object
	 * @param Boolean $input If false then just assign the value if true the add the value to corresponding form element 
	 * @param Array $inputArray Not sure :S
	 */
	private function templateQuestionLayout($fieldMember, $input = false, $inputArray=array() ){
				
				$id = @$fieldMember['question_id'];

				//@$this->template->assign('question_id', ($input)? $this->template->input('text', 'question_id', $fieldMember['question_id']):$fieldMember['question_id']);
				@$this->template->assign('question_catagory_id', ($input)? $this->template->input('text', 'question_catagory_id', $fieldMember['question_catagory_id']):$fieldMember['question_catagory_id']);
				@$this->template->assign('advertisement_id', ($input)? $this->template->input('text', 'advertisement_id', $fieldMember['advertisement_id']):$fieldMember['advertisement_id']);
				@$this->template->assign('label', ($input)? $this->template->input('textarea', 'label', $fieldMember['label']):$fieldMember['label']);
				@$this->template->assign('type', ($input)? $this->getListOfQuestionTypes($fieldMember['type']):$fieldMember['type']);

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'question'));
				}*/
	
	}
	
	
	/**
	 * This medthod is used to validate inputs from form information 
	 * <pre>
	 * This method will first check the if the fields are in the valid_field array and strip out any that are not 
	 * Then it check that fields that require a value in them from the fields_required have a value, if not add an error to validation_error array 
	 * Then it will check all the values to find out if the value match the type found in the fields_validation_type array, if not add an error to validation_error array 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * 
	 * @see fields
	 * @see fields_required
	 * @see fields_validation_type
	 * @see validation_error
	 * 
	 * @param Array $request
	 */
	public function Validate($request){
	
		unset($this->valid_field);
		unset($this->validation_error);
		$isvalid = True;
		
		$validfields = $this->fields;
		$requiredfields = $this->fields_required;
		$fieldsvalidationtype = $this->fields_validation_type;
		
		foreach ($request AS $key=>$value){ //lets strip put unwanted or security violation fields  
			if(in_array($key, $validfields)){
				$this->valid_field[$key] = $value; //pure fields
			}
		}
		
		foreach ($validfields AS $value){ //now lets just add fields as blank if they didn't come though so we can check them, helps with checkboxs
			if(!isset($this->valid_field[$value])){
				$this->valid_field[$value] = ''; 
			}
		}
		
		if(count($requiredfields) > 0 ){
			foreach($requiredfields AS $value){ // lets check all the required fields have a value 
				if (empty($this->valid_field[$value]) || $this->valid_field[$value] == 'NULL'){ 
					$this->validation_error[$value][] = 'Field is Required'; //error field
					$isvalid = false;
				}
			}
		}
	
		
		
		//now lets validate
		foreach ($this->valid_field AS $key=>$value){
			$value = trim($value);
			if(!empty($value)){ // don't cheak if empty, alread done in required check 
				
				switch(@$fieldsvalidationtype[$key]){
					case 'TEXTAREA': if (strlen($value) > 1024) {
									$this->validation_error[$key][] = 'Field longer then 1024 charactors'; 
									$isvalid = false;
								} break;
					case 'TEXT': if (strlen($value) > 1024) {
									$this->validation_error[$key][] = 'Field longer then 1024 charactors'; 
									$isvalid = false;
								} break;
					case 'SAP': if ((!is_numeric($value)) || (strlen($value) != 10)) {
									$this->validation_error[$key][] = 'not a valid SAP number'; 
									$isvalid = false;
								} break;
					case 'DECIMAL': if (!is_numeric($value)) {
									$this->validation_error[$key][] = 'Decimal value expected';
									$isvalid = false;									
								} break;
					case 'BOOL': if ((!is_bool($value)) && (strtoupper($value)!="YES") && ($value != 1)) {
									$this->validation_error[$key][] = 'Please check'; 
									$isvalid = false;
								} break;
					case 'INT': if (!is_numeric($value) && $value != 'NULL' ){
									$this->validation_error[$key][] = 'Numeric value expected';
									$isvalid = false;
								} break;
					case 'DATE': //$date = str_replace('/', '-', $value);
								 //$date = str_replace("\\", '-', $date);
									@list($day, $month, $year) = explode('/', $value);
									if(!checkdate($month,$day, $year)){
										$this->validation_error[$key][] = 'incorrect date format, expecting dd/mm/yyyy'; 
										$isvalid = false;
									} break;	
					case 'YEAR':  if(!checkYear($value)){
										$this->validation_error[$key][] = 'incorrect year format, expecting yyyy'; 
										$isvalid = false;
								   } break;	
					
				}
			}
		}
	
		return $isvalid;
	
	}
	
	public function getListOfQuestionTypes($type = false){
		$html = "<select>";
		if ($handle = opendir('question/')) {
			while (false !== ($file = readdir($handle))) {
				if($file != '.' && $file != '..'){
					$questionType = explode('.', $file);
		        	$html .= '<option value="'.$questionType[0].'">'.$questionType[0].'</option>'."\n";
				}
			}
		}
		$html .= "</select>";
		return $html;
	} 
}