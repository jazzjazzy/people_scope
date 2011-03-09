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

require_once('questionCatagory.class.php');

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
	private $fields =array('question_id', 'question_catagory_id', 'label', 'type','options' );
	
	/**
	 * Array of feilds require information when validating 
	 * @var Array|null
	 */
	private $fields_required = array('label');
	
	/**
	 * Array of feilds and there types that are check when validating 
	 * @var Array|null
	 */
	private $fields_validation_type = array ('question_id'=>'INT', 'question_catagory_id'=>'INT', 'label'=>'TEXT', 'type'=>'TEXT','options'=>'ARRAY' );
	
	/**
	 * Array use to store any error found during Validation function 
	 * @see Validation()
	 * @var Array
	 */
	private $validation_error = array();
	
	private $questionCatagory;
	
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
		$this->questionCatagory=new questionCatagory();
	
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
				question.question_catagory_id,
				question_catagory_name,
				label,
				type FROM question 
				LEFT JOIN question_catagory ON question.question_catagory_id= question_catagory.question_catagory_id
				WHERE (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL)";
		
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
				
				if(isset($source['question_multi'])){
					foreach($source['question_multi'] AS $key=>$entries){
						unset($field);
						unset($value);
						unset($exec);
						foreach($entries AS $key=>$val){
							$field[] = $key;
							$value[] = ":".$key;
						}
						
						$sql = "INSERT INTO question_multi (question_id, ".implode(', ',$field).") VALUES ('".$pid."', ".implode(', ',$value).");";
						
						foreach($entries AS $key=>$val){
							$exec[":".$key] = $val;
						}
						
						try{
							$mpid = $this->db->insert($sql, $exec); 
						}catch(CustomException $e){
							throw new CustomException($e->queryError($sql));
						}
					}
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
	
		$sql = "SELECT 
					question.*, 
					question_multi.label AS multi_lable,
					question_multi.multi_id,  
					question_catagory.question_catagory_name
				FROM question 
				LEFT JOIN question_multi ON question.question_id = question_multi.question_id 
				LEFT JOIN question_catagory ON question.question_catagory_id= question_catagory.question_catagory_id
				WHERE question.question_id = ". $id ." AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL)" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}
			
			unset($_SESSION['Question_Details']['values']);

			$count=0;
			foreach($result AS $key=>$value){
				$retResult['question_id'] =$value['question_id'];
				$retResult['question_catagory_id'] =$value['question_catagory_id'];
				$retResult['question_catagory_name'] =$value['question_catagory_name'];
				$retResult['label'] =$value['label'];
				$retResult['type'] =$value['type'];
				$retResult['options'] =unserialize($value['options']);
				$retResult['create_date'] =$value['create_date'];
				$retResult['create_by'] =$value['create_by'];
				$retResult['modify_date'] =$value['modify_date'];
				$retResult['modify_by'] =$value['modify_by'];
				$retResult['delete_date'] =$value['delete_date'];
				$retResult['delete_by'] =$value['delete_by'];
				$retResult['value'][$value['multi_id']]['multi_id'] =$value['multi_id'];
				$retResult['value'][$value['multi_id']]['value'] =$value['multi_lable'];
				$_SESSION['Question_Details']['values'][$value['multi_id']]['multi_id'] =$value['multi_id'];
				$_SESSION['Question_Details']['values'][$value['multi_id']]['value'] =$value['multi_lable'];
				$count++;
			}
			
			return $retResult;
		
			
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
		
		$this->table->removeColumn(array('question_id', 'question_catagory_id'));
		
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
						'question_catagory_name'=>'Catagory',
						'label'=>'Label',
						'type'=>'Type'));
				
				$this->table->setFilter(array(	
						'question_id'=>'COMPILED',
						'question_catagory_name'=>'COMPILED',
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


		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='question.php?action=edit&id=".$id."'\">Edit</div>");
		
		if(file_exists(DIR_ROOT.'/question/'.strtolower(@$fieldMember['type']).'.q.php')){
			include_once DIR_ROOT.'/question/'.strtolower(@$fieldMember['type']).'.q.php';
		}
		
		$questionType = new $fieldMember['type']();
		
		$html = $questionType->create($fieldMember['options'], $fieldMember['label']);
		
		$this->template->assign('create-question', $html);
			
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
		
		$this->template->assign('create-question', $this->getQuestionDetailsById($id));

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
				$save[$table]['label'] = $request['label'];
				$save[$table]['type'] = $request['type'];
				
				$save[$table]['options'] = serialize(@$request['options']);

				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				if(isset($_SESSION['Question_Details']['values'])){
					if(is_array($_SESSION['Question_Details']['values'])){
						$table="question_multi";
						foreach($_SESSION['Question_Details']['values'] AS $key=>$value){
							$save[$table][$key]['label'] = $value['value'];
						}
					}
				}
				unset($_SESSION['Question_Details']['values']);
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
				@$this->template->assign('question_catagory_id',  ($input)? $this->getQuestionCategoryList($fieldMember['question_catagory_id']) : $fieldMember['question_catagory_name']);
				@$this->template->assign('label', ($input)? $this->template->input('textarea', 'label', $fieldMember['label']):$fieldMember['label']);
				@$this->template->assign('type', ($input)? $this->getListOfQuestionTypes($fieldMember['type']):$this->getQuestionTypeLable($fieldMember['type']));
				//$this->template->assign('create-question', $this->getQuestionDetailsbyAdvertismentId($id));
				
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
			//$value = trim($value);
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
	
	/**
	 * Add a new question to the Advertisement
	 * This methods is used for an ajax call to add a question to the question list for an advertisment
	 * 
	 * @param Integer $id the Id of the question from the question pool
	 * @param Integer $pid The id of the advertise this question is to be added too 
	 * 
	 * @return String An li row to be added to an unorded list  
	 */
	public function addQuestionById($id, $pid=NULL){
		if(empty($id) && !is_numeric($id)){
			return false;
		}
		
		$list = $this->read($id);
		
		if(empty($pid)){
			$_SESSION['questions']['create'][$id] = $list;
		}else{
			$_SESSION['questions'][$pid][$id] = $list;
		}
		
		/*$sql = "INSERT INTO advertisement_question (advertisement_id,question_id) VALUES ('".$pid."','".$list['question_id']."');";
		
		try{
			$pid = $this->db->insert($sql); 
		}catch(CustomException $e){
			throw new CustomException($e->queryError($sql));
		}*/

		$html ='<li id="q_'.$list['question_id'].'">
					<div style="float:left">
						<div style="width:300px;float:left">'.$list['label'].'</div>
						<div style="width:50px;float:left">'.$this->getQuestionTypeLable($list['type'])."</div>
					</div>
				 </li>\n";

		return $html;
		
	}
	
	public function getListOfQuestionTypes($type = false){
		$html = "<select>";
		$list = array();
		if ($handle = opendir('question/')) {
			while (false !== ($file = readdir($handle))) {
				if($file != '.' && $file != '..'){
					$questionType = explode('.', $file);
		        	$list[] = $questionType[0];
		        	
				}
			}
		}
		
		sort($list);
		
		$html = "<select id=\"question-type\" name=\"type\"><option></option>";
		foreach($list AS $value){
			$label = $this->getQuestionTypeLable($value);
			if($value == $type){
				$html .= '<option value="'.$value.'" selected=selected>'.$label.'</option>'."\n";
			}else{
				$html .= '<option value="'.$value.'">'.$label.'</option>'."\n";
			}
		}
		$html .= "</select>";
		return $html;
	} 
	
	
	public function getQuestionTypeLable($name){
		$label = str_replace('_', ' ', $name);
		$label = ucwords($label);
		
		return $label;
	}
	
	public function getQuestionCategoryList($id, $retType=NULL){
		$list = $this->questionCatagory->getQuestionCatagorySelect();
		
		if($retType){
			foreach($list AS $value){
				$html = ($value['question_catagory_id'] == $id)? question_catagory_name : '';
			}
		}else{
			$html = "<select name=\"question_catagory_id\" id=\"question_catagory_id\"><option></option>";
			foreach($list AS $value){
				$selected = ($value['question_catagory_id'] == $id)? "selected=selected" : '';
				$html.='<option value="'.$value['question_catagory_id'].'" '.$selected.'>'.$value['question_catagory_name'].'</option>';
			}
		}
		$html .= "</select>";
		
		return $html;
	}
	
	public function getQestionsByAdvertismentId($id){
		$sql = "SELECT question.question_id,
				question.question_catagory_id,
				question_catagory_name,
				label,
				type FROM question 
				LEFT JOIN advertisement_question ON question.question_id = advertisement_question.question_id
				LEFT JOIN question_catagory ON question.question_catagory_id= question_catagory.question_catagory_id
				WHERE advertisement_id = ". $id ." AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL)" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}

			return $_SESSION['questions'][$id] = $result;
	}
	
	/*public function getQuestions($pid, $id){
		$sql = "SELECT question.question_id, question.label, question.type, question_multi.label AS multi_label, question_multi.value FROM advertisement_question 
					LEFT JOIN question ON advertisement_question.question_id = question.question_id
					LEFT JOIN question_multi ON question.question_id = question_multi.question_id
				WHERE advertisement_question.advertisement_id = ".$pid." AND question.question_id= ".$id." 
				AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL);";
		
		
		$stmt = $this->db_connect->prepare($sql);
		$stmt->execute();
		
		try{
			$result = $this->db->select($sql);
		}catch(CustomException $e){
			echo $e->queryError($sql);
		}
		
		foreach($result AS $value){
			$temp[] = $value['multi_label'].'|'.$value['value'];
		}
		
		$_SESSION['questions'][$id] = $temp;
	}*/
	
	public function getQuestionDetailsbyAdvertismentId($id, $pid=Null){
		
		/*$sql = "SELECT question.question_id, question.label, question.type, question_multi.label AS multi_label, question_multi.value FROM advertisement_question 
					LEFT JOIN question ON advertisement_question.question_id = question.question_id
					LEFT JOIN question_multi ON question.question_id = question_multi.question_id
				WHERE advertisement_question.advertisement_id = ".$pid." AND question.question_id= ".$id." 
				AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL);";
		
		$sql = "SELECT question.question_id, question.label, question.type, question_multi.label AS multi_label, question_multi.value FROM question 
					LEFT JOIN question_multi ON question.question_id = question_multi.question_id
				WHERE question.question_id= ".$id." 
				AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL);";
		
		$stmt = $this->db_connect->prepare($sql);
		$stmt->execute();
		
		try{
			$result = $this->db->select($sql);
		}catch(CustomException $e){
			echo $e->queryError($sql);
		}*/
		$result = $this->read($id);
		
		foreach($result['value'] AS $value){
			$temp[] = $value['value'].'|'.$value['value'];
		}
		
		$value = implode(',', $temp);
	
		$str = $result['label'].':'.$result['question_id'].':'.$result['type'].':'.$value.':::required:notype';
		
		/*$formExample = new form($str);		
		$formEdit = new form();
		$formSetting = new form();
		*/
		//$html = $form->edit($str);
		
		$type =strtolower($result['type']);
		if(file_exists(DIR_ROOT.'/question/'.$type.'.q.php')){
			include_once DIR_ROOT.'/question/'.$type.'.q.php';
		}
		
		$question = new $type();
		
		
		$html = '<script>
					$(document).ready(function(){
						$("#example").tabs();
					})
				</script> 
				<input type="hidden" value="'.$id.'" name="qid" id="qid" />
		<div id="example">
     	<ul>
		<li><a href="#tabs-1">Eample</a></li>
		<li><a href="#tabs-2">Tracking</a></li>
		<li><a href="#tabs-3">Setting</a></li>
		<li><div class="cancel-button">&nbsp;</div></li>
			</ul>
			
			<div id="tabs-1">
				<div id="tab-text" >This is an eample of what the question type would look like.</div>
				<div style="padding:0px 0px 20px 20px">'.$question->display($result['label'], $result['value']).'</div>
			</div>
			<div id="tabs-2">
				<div id="tab-text" >Use this to set the tracking requirments for this question</div>
				<div style="padding:0px 0px 20px 20px">'.$question->edit(true).'</div>
			</div>
			<div id="tabs-3">
				<div id="tab-text" >you can change the requirment setting for this question below</div>
				<div style="padding:0px 0px 20px 20px">'.$question->setting($result['options']).'</div>
			</div>
		
		</div>';
		
		//$html  = "<div class=\"button qedit\" id=\"$id-$pid\">edit</div><br class=\"clear\">".$form->draw()."<div class=\"button\" id=\"close\">Close</div><br class=\"clear\" />";
		
		return $html;

	}
	
	public function getQuestionEditbyAdvertismentId($id, $pid){
		
		$temp = array();
		
		/*$sql = "SELECT question.question_id, question.label, question.type, question_multi.label AS multi_label, question_multi.value FROM advertisement_question 
					LEFT JOIN question ON advertisement_question.question_id = question.question_id
					LEFT JOIN question_multi ON question.question_id = question_multi.question_id
				WHERE advertisement_question.advertisement_id = ".$pid." AND question.question_id= ".$id." 
				AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL);";*/
		
		$sql = "SELECT question.question_id, question.label, question.type, question_multi.label AS multi_label, question_multi.value FROM question
					LEFT JOIN question_multi ON question.question_id = question_multi.question_id
				WHERE question.question_id= ".$id." 
				AND (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL);";
		
		$stmt = $this->db_connect->prepare($sql);
		$stmt->execute();
		
		try{
			$result = $this->db->select($sql);
		}catch(CustomException $e){
			echo $e->queryError($sql);
		}
		
		foreach($result AS $value){
			$temp[] = $value['multi_label'].'|'.$value['value'];
		}
		
		$value = implode(',', $temp);
	
		$str = $result[0]['label'].':'.$result[0]['question_id'].':'.$result[0]['type'].':'.$value.':::required:notype';
		
		$form = new form();
		
		$html = $form->edit($str);
		

		//$html .= "<div class=\"button qedit\" id=\"$id-$pid\">edit</div><div class=\"button\" id=\"close\">Cancal</div><br class=\"clear\" /><br class=\"clear\">".$form->edit($str);

		return $html;

	}
	
	public function getQuestionPool($pid = NULl){
		
		$sql = "SELECT question_catagory_name, question.question_id, question.label, question.type
					FROM
					question_catagory
					Left Outer Join question ON question_catagory.question_catagory_id = question.question_catagory_id
					WHERE (question.delete_date ='00-00-0000 00:00:00' OR question.delete_date IS NULL)";

		if(!empty($pid)){
			//$sql .= " AND question.question_id NOT IN (SELECT question_id FROM advertisement_question WHERE advertisement_id = ".$pid.") ";
		}
		
		$sql .= " ORDER BY question_catagory_name;";
		
		$stmt = $this->db_connect->prepare($sql);
		$stmt->execute();
		
		try{
			$result = $this->db->select($sql);
		}catch(CustomException $e){
			echo $e->queryError($sql);
		}
		
		return $result;
	}
	
	public function sortQuestionOrder($id, $q){
		try{
			$this->db_connect->beginTransaction();
			
			foreach($q AS $key=>$value){
				$sql = "UPDATE advertisement_question SET sort = $key WHERE question_id =". $value ." AND advertisement_id = ".$id;

				try{
						$pid = $this->db->update($sql); 
				}catch(CustomException $e){
						throw new CustomException($e->queryError($sql));
				}
			}
			
			$this->db_connect->commit();	
		}
		
		catch (CustomException $e) {
			$e->queryError($sql);
			$this->db_connect->rollBack();
			return false;
		}	
	}
	
	public function getQuestionDetailsByType($name){
		
		$name = strtolower($name);
		
		if(file_exists(DIR_ROOT.'/question/'.$name.'.q.php')){
			include_once DIR_ROOT.'/question/'.$name.'.q.php';
		}
		unset($_SESSION['Question_Details']['values']);
		
		$questionTpye = new $name();
		
		$html = $questionTpye->create(true);	
		
		return $html;
	}

	public function getQuestionDetailsById($id){
		
		$read = $this->read($id);
		
		$name = $read['type'];
		
		unset($_SESSION['Question_Details']['values']);
		
		if(!empty($read['value'])){
			foreach($read['value'] AS $key=>$value){
				$_SESSION['Question_Details']['values'][]['value'] = trim($value['value']);
			}
		}
		
		
		if(file_exists(DIR_ROOT.'/question/'.strtolower($name).'.q.php')){
			include_once DIR_ROOT.'/question/'.strtolower($name).'.q.php';
		}
		
		$questionType = new $name();
		
		$html = $questionType->create($read['options'], $read['label']);
		
		//$html = create($read['options']);	
		
		return $html;
	}
	
	public function addTrackingId($qid, $tid, $pid=NULL){
		
		$_SESSION['questions']['create'][$qid]['value'][$tid]['tracking']='true';

		pp($_SESSION['questions']['create'][$qid]['value']);
	}
}