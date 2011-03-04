<?php
/**
 * Advertisement Class 
 * <pre>
 * This class is based on the table advertisement
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 1.1 of the Framework generator
 * @package PeopleScope
 */

require_once('question.class.php');

class advertisement {
	
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
	 * Template class object 
	 * @var Object
	 */
	public $questions;
	
	/**
	 * Array of field used in the database if not in this list is dropped from insert or update
	 * @var Array
	 */
	private $fields =array('advertisement_id', 'title', 'catagory_id', 'template_id', 'office_id', 'dept_id', 'role_id', 'state_id', 'store_location_id', 'storerole_id', 'start_date', 'end_date', 'discription', 'requirments', 'upload_resume', 'cover_letter', 'status', 'employmenttype_id', 'create_date', 'create_by', 'modify_date', 'modify_by', 'delete_date', 'delete_by');
	
	/**
	 * Array of feilds require information when validating 
	 * @var Array|null
	 */
	private $fields_required = array('title', 'employmenttype_id', 'state_id');
	
	/**
	 * Array of feilds and there types that are check when validating 
	 * @var Array|null
	 */
	private $fields_validation_type = array ('advertisement_id'=>'TEXT', 'title'=>'TEXT', 'catagory_id'=>'INT', 'template_id'=>'INT', 'office_id'=>'INT', 'dept_id'=>'INT', 'role_id'=>'INT', 'state_id'=>'INT', 'store_location_id'=>'INT', 'storerole_id'=>'INT', 'start_date'=>'DATE', 'end_date'=>'DATE', 'discription'=>'TEXTAREA', 'requirments'=>'TEXTAREA', 'upload_resume'=>'BOOL', 'cover_letter'=>'BOOL', 'status'=>'BOOL', 'employmenttype_id'=>'INT', 'create_date'=>'TEXT', 'create_by'=>'INT', 'modify_date'=>'TEXT', 'modify_by'=>'INT', 'delete_date'=>'TEXT', 'delete_by'=>'TEXT');
	
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
		$this->questions = new question();
	
	}
	
	/**
	 * Show will pull a list from the corresponding Advertisement advertisement
	 * 
	 * <pre>
	 * This Method will produce a list of all the element corresponding to the result of Advertisement
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
		
		$sql = "SELECT advertisement.advertisement_id,
					advertisement.title,
					category.catagory_id,
					category.catagory_name,
					template.title AS template_title,
					advertisement.office_id,
					advertisement.dept_id,
					advertisement.role_id,
					advertisement.state_id,
					state.name,
					advertisement.store_location_id,
					advertisement.storerole_id,
					CASE WHEN advertisement.start_date = '00-00-0000 00:00:00' THEN 'Now' ELSE DATE_FORMAT(advertisement.start_date, '%d/%m/%Y') END  AS start_date,
					CASE WHEN advertisement.end_date = '00-00-0000 00:00:00' THEN 'Never' ELSE DATE_FORMAT(advertisement.end_date, '%d/%m/%Y') END  AS end_date,
					advertisement.discription,
					advertisement.requirments,
					if(advertisement.upload_resume, 'yes', 'no') AS upload_resume,
					if(advertisement.cover_letter, 'yes', 'no') AS cover_letter,
					advertisement.status,
					advertisement.employmenttype_id, 
					employmenttype,
					advertisement.create_date,
					create_by,
					advertisement.modify_date,
					modify_by,
					advertisement.delete_date,
					delete_by
					 FROM advertisement 
						LEFT JOIN category ON advertisement.catagory_id = category.catagory_id
						LEFT JOIN state ON advertisement.state_id = state.state_id
						LEFT JOIN employmenttype ON advertisement.employmenttype_id = employmenttype.employmenttype_id
						LEFT JOIN template ON advertisement.template_id = template.template_id
					 WHERE (advertisement.delete_date ='00-00-0000 00:00:00' OR advertisement.delete_date IS NULL)";
		
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
				
				foreach($source['advertisement'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO advertisement (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['advertisement'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
					$pid = $this->db->insert($sql, $exec); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}
				
				$sql = "INSERT INTO advertisement_question (advertisement_id, question_id, sort) VALUES "; 
				pp($source['advertisement_question']);
				foreach($source['advertisement_question'] AS $key=>$value){
					
					$fields[] = "(".$pid.", ".$value['question_id'].",".$key.")";
				}
				
				$sql .= implode(',', $fields);
				
				try{
					$this->db->insert($sql); 
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
	
		$sql = "SELECT advertisement_id,
					title,
					advertisement.catagory_id,
					catagory_name,
					template_id,
					office_id,
					dept_id,
					role_id,
					state_id,
					store_location_id,
					storerole_id,
					CASE WHEN advertisement.start_date = '00-00-0000 00:00:00' THEN '' ELSE DATE_FORMAT(advertisement.start_date, '%d/%m/%Y') END  AS start_date,
					CASE WHEN advertisement.end_date = '00-00-0000 00:00:00' THEN '' ELSE DATE_FORMAT(advertisement.end_date, '%d/%m/%Y') END   AS end_date,
					discription,
					requirments,
					upload_resume,
					cover_letter,
					status,
					employmenttype_id,
					DATE_FORMAT(advertisement.create_date, '%d/%m/%Y') AS create_date,
					create_by,
					DATE_FORMAT(advertisement.modify_date, '%d/%m/%Y') AS modify_date,
					modify_by,
					DATE_FORMAT(advertisement.delete_date, '%d/%m/%Y') AS delete_date,
					delete_by
					FROM advertisement 
						LEFT JOIN category ON advertisement.catagory_id = category.catagory_id
					WHERE advertisement_id = ". $id ." 
					AND (advertisement.delete_date ='00-00-0000 00:00:00' OR advertisement.delete_date IS NULL)" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}
			
			$this->questions->getQestionsByAdvertismentId($id);
			
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
				
				foreach($source['advertisement'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE advertisement SET ".implode(', ',$field)." WHERE advertisement_id =". $id;

				foreach($source['advertisement'] AS $key=>$val){
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
			
			header('Location:advertisement.php?action=show&id='.$id);
			
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
			
			$sql = "UPDATE advertisement SET delete_date=NOW(), delete_by=".$_SESSION['user']['user_id']." WHERE advertisement_id =". $id;

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
	 * <pre>This Method will produce a list of all the element corresponding to the result of Advertisement
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
	 * @param Array $filter A array of fields to filter, key=INT set (eg array('tile='=>'this title'))  
	 */
	
	public function getAdvertisementList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		
		$result = $this->lists($orderby, $direction, $filter);
		
		$this->table->removeColumn(array('advertisement_id','catagory_id','office_id', 'dept_id', 'role_id', 'state_id', 'store_location_id','storerole_id', 'create_date', 'discription', 'requirments', 'employmenttype_id','create_date','create_by','modify_date','modify_by','delete_date','delete_by'));
		
		switch(strtoupper($type)){
		
			case 'AJAX' : $this->table->setRowsOnly(); 
						  $this->table->setIdentifier('advertisement_id');
						  $this->table->setIdentifierPage('advertisement');
						  echo $this->table->genterateDisplayTable($result);
						  
				BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'title'=>'Title',
						'catagory_name'=>'Catagory',
						'template_title'=>'Template',
						'name'=>'State',
						'start_date'=>'Start&nbsp;Date',
						'end_date'=>'End&nbsp;Date',
						'upload_resume'=>'Resume',
						'cover_letter'=>'Cover',
						'status'=>'Status',
						'employmenttype'=>'Type'
				));
				
				$this->table->setFilter(array(	
						'title'=>'TEXT',
						'catagory_name'=>'COMPILED',
						'template_title'=>'TEXT',
						'name'=>'COMPILED',
						'start_date'=>'DATE',
						'end_date'=>'DATE',
						'upload_resume'=>'COMPILED',
						'cover_letter'=>'COMPILED',
						'status'=>'COMPILED',
						'employmenttype'=>'COMPILED'
				));
				
				$this->table->setColumnsWidth(array(	
						'0'=>'300',
						'1'=>'300',
						'2'=>'300',
						'3'=>'300',
						'4'=>'10',
						'5'=>'10',
						'6'=>'10',
						'7'=>'10',
						'8'=>'10',
						'9'=>'10'
				));
				
				$this->table->setIdentifier('advertisement_id');
				
				$this->template->assign('pagetitle', "List Advertisments" );
				$this->template->content(Box($this->table->genterateDisplayTable($result),'Advertisement List', 'Shows the current listings for the Advertisement. To create a new Listing <a href="advertisement.php?action=create">Click Here</a>'));
				
				$this->template->display();
		}
	}
	
	
	/**
	 * Show details of a single Advertisement from the advertisement
	 * 
	 * <pre>This method will return a template page of the information requested 
	 * the method use the template class to format the information ready to display the 
	 * the user 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id the primary id of the row to show 
	 */
	Public function showAdvertisementDetails($id){
		$fieldMember = $this->read($id);
		
		$this->template->page('advertisement.tpl.html');
		
		$this->templateAdvertisementLayout($fieldMember);
		$this->template->assign('pagetitle', $fieldMember['title'] );
		//if($this->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='advertisement.php?action=edit&id=".$id."'\">Edit</div>");
		//}
		
		echo $this->template->fetch();	
	}
		
	
	/**
	 * Show the details ready to edit of a single Advertisement from the advertisement 
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
	Public function editAdvertisementDetails($id){
		
		$fieldMember = $this->read($id);
		
		$name = 'editAdvertisement';
		
		$this->template->page('advertisement.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="advertisement.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		$this->template->assign('pagetitle', $fieldMember['title'] );
		$this->templateAdvertisementLayout($fieldMember, true);
		
		$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Update\"><div class=\"button\" onclick=\"location.href='advertisement.php?action=show&id=".$id."'\">Cancel</div>");
		
		$this->template->display();
	}
	
	/**
	 * update the information in a single Advertisement from the advertisement 
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
	Public function updateAdvertisementDetails($id){
		
		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'advertisement';
				
				$save[$table]['title'] = $request['title'];
				$save[$table]['catagory_id'] = $request['catagory_id'];
				//$save[$table]['template_id'] = @$request['template_id'];
				/*$save[$table]['office_id'] = $request['office_id'];
				$save[$table]['dept_id'] = $request['dept_id'];
				$save[$table]['role_id'] = $request['role_id'];*/
				$save[$table]['state_id'] = $request['state_id'];
				/*$save[$table]['store_location_id'] = $request['store_location_id'];
				$save[$table]['storerole_id'] = $request['storerole_id'];*/
				$save[$table]['start_date'] = formatDateUI($request['start_date']);
				$save[$table]['end_date'] = formatDateUI($request['end_date']);
				$save[$table]['discription'] = $request['discription'];
				$save[$table]['requirments'] = $request['requirments'];
				$save[$table]['upload_resume'] = (isset($request['upload_resume']))?$request['upload_resume']:0;
				$save[$table]['cover_letter'] = (isset($request['cover_letter']))?$request['cover_letter']:0;
				$save[$table]['status'] = (isset($request['status']))?$request['status']:0;
				$save[$table]['employmenttype_id'] = $request['employmenttype_id'];
				$save[$table]['modify_by'] = @$_SESSION['user']['user_id'];
				
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				header('Location: advertisement.php?action=show&id='.$id);
			}else{
				
				$fieldMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editAdvertisement';
		
				$this->template->page('advertisement.tpl.html');
				
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $error[$key])."</spam>");
				}
				
				$this->template->assign('FORM-HEADER', '<form action="advertisement.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateAdvertisementLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Update\"><div class=\"button\" onclick=\"location.href='advertisement.php?action=show&id=".$id."'\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	/**
	 * This method will provide a page to the to add a single row Advertisement to the advertisement table
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
	Public function createAdvertisementDetails(){
		
		$_SESSION['questions']['create'] = '';
		
		$name = 'createAdvertisement';
		
		$this->template->page('advertisement.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="advertisement.php?action=save" method="POST" name="'.$name.'">');
		$this->template->assign('pagetitle', "Create Advertisment" );
		$this->templateAdvertisementLayout('', true);
		
		$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Save\"><div class=\"button\" onclick=\"location.href='advertisement.php?action=list'\">Cancel</div>");
		

		$this->template->display();
	} 
	
	/**
	 * save the information in a single Advertisement to the advertisement table 
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
	Public function saveAdvertisementDetails(){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'advertisement';

				$save[$table]['title'] = $request['title'];
				$save[$table]['catagory_id'] = $request['catagory_id'];
				//$save[$table]['template_id'] = $request['template_id'];
				/*$save[$table]['office_id'] = $request['office_id'];
				$save[$table]['dept_id'] = $request['dept_id'];
				$save[$table]['role_id'] = $request['role_id'];*/
				$save[$table]['state_id'] = $request['state_id'];
				/*$save[$table]['store_location_id'] = $request['store_location_id'];
				$save[$table]['storerole_id'] = $request['storerole_id'];*/
				$save[$table]['start_date'] = formatDateUI($request['start_date']);
				$save[$table]['end_date'] = formatDateUI($request['end_date']);
				$save[$table]['discription'] = $request['discription'];
				$save[$table]['requirments'] = $request['requirments'];
				$save[$table]['upload_resume'] = $request['upload_resume'];
				$save[$table]['cover_letter'] = $request['cover_letter'];
				$save[$table]['status'] = $request['status'];
				$save[$table]['employmenttype_id'] = $request['employmenttype_id'];
				$save[$table]['create_by'] = $_SESSION['user']['user_id'];
				
				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				$table = 'advertisement_question';
				if(isset($_SESSION['questions']['create'])){
					$save[$table] = $_SESSION['questions']['create'];
				}
				
				
				$id = $this->create($save);
				header('Location: advertisement.php?action=show&id='.$id);
			}else{
			
				$fieldMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createAdvertisement';
	
				$this->template->page('advertisement.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="advertisement.php?action=save" method="POST" name="'.$name.'">');
		
				$this->templateAdvertisementLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Update\"><div class=\"button\" onclick=\"location.href='advertisement.php\">Cancel</div>");
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
	Public function deleteAdvertisementDetails($id){
		$this->remove($id);
		header('Location: advertisement.php');
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
	private function templateAdvertisementLayout($fieldMember, $input = false, $inputArray=array() ){
				
				$id = @$fieldMember['advertisement_id'];
				
				@$this->template->assign('advertisement_id', $id);
				
				@$this->template->assign('title', ($input)? $this->template->input('text', 'title', $fieldMember['title']):'<div id="title">'.$fieldMember['title'].'</div>');
				@$this->template->assign('catagory_name', ($input)? $this->getSelectListOfCategory($fieldMember['catagory_id'], True):'<div class="indent">'.$this->getSelectListOfCategory($fieldMember['catagory_id']).'</div>');
				@$this->template->assign('template_title', ($input)? $this->getSelectListOfTemplate($fieldMember['template_id'], True):$this->getSelectListOfTemplate($fieldMember['template_id']));
				@$this->template->assign('office_id', ($input)? $this->template->input('text', 'office_id', $fieldMember['office_id']):$fieldMember['office_id']);
				@$this->template->assign('dept_id', ($input)? $this->template->input('text', 'dept_id', $fieldMember['dept_id']):$fieldMember['dept_id']);
				@$this->template->assign('role_id', ($input)? $this->template->input('text', 'role_id', $fieldMember['role_id']):$fieldMember['role_id']);
				@$this->template->assign('state_name', ($input)? $this->getSelectListOfStates($fieldMember['state_id'], True):'<div class="indent">'.$this->getSelectListOfStates($fieldMember['state_id']).'</div>');
				@$this->template->assign('store_location_id', ($input)? $this->template->input('text', 'store_location_id', $fieldMember['store_location_id']):$fieldMember['store_location_id']);
				@$this->template->assign('storerole_id', ($input)? $this->template->input('text', 'storerole_id', $fieldMember['storerole_id']):$fieldMember['storerole_id']);
				@$this->template->assign('start_date', ($input)? $this->template->input('text', 'start_date', $fieldMember['start_date']):'<div class="indent">'.$fieldMember['start_date'].'</div>');
				@$this->template->assign('end_date', ($input)? $this->template->input('text', 'end_date', $fieldMember['end_date']):'<div class="indent">'.$fieldMember['end_date'].'</div>');
				@$this->template->assign('discription', ($input)? $this->template->input('textarea', 'discription', $fieldMember['discription']):$fieldMember['discription']);
				@$this->template->assign('requirments', ($input)? $this->template->input('textarea', 'requirments', $fieldMember['requirments']):$fieldMember['requirments']);
				@$this->template->assign('question_list', ($input)? $this->getAdvertisingQuestions($_SESSION['questions'][$id]):$this->getAdvertisingQuestions($_SESSION['questions'][$id]));
				@$this->template->assign('question_pool', ($input)? $this->getListOfQuestions($id):$this->getListOfQuestions($id));
				@$this->template->assign('upload_resume', ($input)? $this->template->input('checkbox', 'upload_resume', $this->template->formatBoolean($fieldMember['upload_resume'])):$this->template->formatBoolean($fieldMember['upload_resume'] ));
				@$this->template->assign('cover_letter', ($input)? $this->template->input('checkbox', 'cover_letter', $this->template->formatBoolean($fieldMember['cover_letter'])):$this->template->formatBoolean($fieldMember['cover_letter'] ));
				@$this->template->assign('status', ($input)? $this->template->input('checkbox', 'status', $this->template->formatBoolean($fieldMember['status'])):$this->template->formatBoolean($fieldMember['status'] ));
				@$this->template->assign('employmenttype', ($input)? $this->getSelectListOfEmploymentType($fieldMember['employmenttype_id'], True):'<div class="indent">'.$this->getSelectListOfEmploymentType($fieldMember['employmenttype_id']).'</div>');
				@$this->template->assign('create_date', $fieldMember['create_date']);
				@$this->template->assign('create_by', $this->getUserById($fieldMember['create_by']));
				@$this->template->assign('modify_date', $fieldMember['modify_date']);
				@$this->template->assign('modify_by', $this->getUserById($fieldMember['modify_by']));
				@$this->template->assign('delete_date', $fieldMember['delete_date']);
				@$this->template->assign('delete_by', $this->getUserById($fieldMember['delete_by']));

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'advertisement'));
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
					case 'TEXTAREA': if (strlen($value) > (1024*10)) {
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
	
	private function getAdvertisingQuestions($qArray){
		if(!is_array($qArray)){
			return false;
		}
		
		$html ="";
		
		foreach($qArray AS $value){
			$html .='<li id="q_'.$value['question_id'].'" style="clear:both">
						<div>
							<div style="width:300px;float:left">'.$value['label'].'</div>
							<span style="width:50px;">'.$this->questions->getQuestionTypeLable($value['type'])."</span>
						</div>
					 </li>\n";
		}
		
		return $html;
	}
	
	public function getListOfQuestions($pid){
		$list = $this->questions->getQuestionPool($pid);
		//pp($list);
		foreach($list AS $value){
			$value['question_catagory_name'] = (!empty($value['question_catagory_name']))?$value['question_catagory_name'] : "Other";
			$listArray[$value['question_catagory_name']][] = $value;
		}
		
		$html ='<div style="float:left;width:380px">';
		$html .='<div class="pool" >';
		$html .= "<h3><a href=\"#\">Create new</a></h3><ul><li></li></ul>";
		foreach($listArray AS $key=>$value1){
			$html .= "<h3><a href=\"#\">$key</a></h3>";
			$html .= "<ul>";
			foreach($value1 AS $key=>$value){
				$html .='<li id="list_'.$value['question_id'].'">
							<div class="pool-list" style="float:left">
								<div style="width:265px;float:left">'.$value['label'].'</div>
								<div style="width:50px;float:left">'.$this->questions->getQuestionTypeLable($value['type'])."</div>
							</div>
						 </li>\n";
			}
			$html .= "</ul>";
		}
		$html .="</div>";
		$html .='</div>';
		
		return $html;
	}
	
	/**
	 * This Method will either the catagory_name feild or a select box of catagory_name fields
	 * 
	 * This Methos is based in the catagory_id of the category table, it will return either the information 
	 * in the catagory_name field as a string or a select box with the id selected 
	 * 
	 * @todo move this to its own class for all classes to use 
	 * 
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfCategory($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('category', $id, 'catagory_id', 'catagory_name');
			}else{
				$select = "<select name=\"catagory_id\">";
				$select .= "<option value=\"\"></option>";
				$select .= $this->template->getListTable('category', $id, 'catagory_id', 'catagory_name', $selectBox);
				$select .= "</select>";
				return $select;
			}
	}
	
	/**
	 * This Method will either the name feild or a select box of state fields
	 * 
	 * This Methos is based in the state_id of the state table, it will reurn either the information 
	 * in the state name field as a string or a select box with the id selected 
	 * 
	 * @todo move this to its own class for all classes to use 
	 * 
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfStates($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('state', $id, 'state_id', 'name');
			}else{
				$select = "<select name=\"state_id\">";
				$select .= "<option value=\"\"></option>";
				$select .= $this->template->getListTable('state', $id, 'state_id', 'name', $selectBox);
				$select .= "</select>";
				return $select;
			}
	}
	
	/**
	 * This Method will either the employmenttype feild or a select box of employmenttype fields
	 * 
	 * This Methos is based in the employmenttype_id of the employmenttype table, it will reurn either the information 
	 * in the employmenttype field as a string or a select box with the id selected 
	 * 
	 * @todo move this to its own class for all classes to use 
	 * 
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfEmploymentType($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('employmenttype', $id, 'employmenttype_id', 'employmenttype');
			}else{
				$select = "<select name=\"employmenttype_id\">";
				$select .= "<option value=\"\"></option>";
				$select .= $this->template->getListTable('employmenttype', $id, 'employmenttype_id', 'employmenttype', $selectBox);
				$select .= "</select>";
				return $select;
			}
	}
	
	/**
	 * This Method will either the employmenttype feild or a select box of employmenttype fields
	 *
	 * This Methos is based in the employmenttype_id of the employmenttype table, it will reurn either the information 
	 * in the employmenttype field as a string or a select box with the id selected 
	 * 
	 * @todo move this as a function in advertTemplate class
	 * @todo move this to its own class for all classes to use 
	 * 
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfTemplate($id, $selectBox=NULL){

		if(is_nan($id) && empty($id)){
			return;
		}
		
		$sql = "Select * from template";
		
		$stmt = $this->db_connect->prepare($sql);
		$stmt->execute();
		
		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}
		
		$html = '';
		
		foreach($result AS $values){
			if($values['template_id'] == $id){
				$html .= '<div class="template-list template-selected"><a href="advertTemplate.php?action=show&id='.$values['template_id'].'">'.$values['title'].'</a></div>';
			}else{
				$html .= '<div class="template-list"><a href="advertTemplate.php?action=show&id='.$values['template_id'].'">'.$values['title'].'</a></div>';
			}
		}
		return $html;
	}
	
	/**
	 * Will get name of the user based on id 
	 * 
	 * This will return the name and surname concatinated of a user by their id 
	 *  
	 * @param Integer $id
	 * @return String The user name
	 */
	public function getUserById($id = false){

		if(is_nan($id) || empty($id)){
			return;
		}
		
		$sql = "Select CONCAT(name, ' ', surname) as admin from users WHERE user_id = $id;";
		
		$stmt = $this->db_connect->prepare($sql);
		$stmt->execute();
		
		try{
			 $result = $this->db->select($sql);
		}catch(CustomException $e){
			 echo $e->queryError($sql);
		}
		return $result[0]['admin'];
		
	}
	
	
}