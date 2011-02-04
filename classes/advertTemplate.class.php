<?php
/**
 * AdvertTemplate Class 
 * <pre>
 * This class is based on the table template
 *
 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
 * </pre> 
 * 
 * @author Jennifer Erator <jason@lexxcom.com>
 * @version 1.1 of the Framework generator
 * @package PeopleScope
 */

class advertTemplate {
	
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
	private $fields =array('template_id', 'title', 'employmenttype_id', 'catagory_id', 'office_id', 'dept_id', 'role_id', 'state_id', 'storeLoc_id', 'start_date', 'end_date', 'discription', 'requirments', 'status', 'tracking_id', 'advertisement_id', 'create_date', 'modify_date', 'delete_date');
	
	/**
	 * Array of feilds require information when validating 
	 * @var Array|null
	 */
	private $fields_required = array('title', 'catagory_id');
	
	/**
	 * Array of feilds and there types that are check when validating 
	 * @var Array|null
	 */
	private $fields_validation_type = array ('template_id'=>'TEXT', 'title'=>'TEXT', 'employmenttype_id'=>'INT', 'catagory_id'=>'INT', 'office_id'=>'INT', 'dept_id'=>'INT', 'role_id'=>'INT', 'state_id'=>'INT', 'storeLoc_id'=>'INT', 'start_date'=>'DATE', 'end_date'=>'DATE', 'discription'=>'TEXT', 'requirments'=>'TEXT', 'status'=>'TEXT', 'tracking_id'=>'INT', 'advertisement_id'=>'INT', 'create_date'=>'TEXT', 'modify_date'=>'TEXT', 'delete_date'=>'TEXT');
	
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
	 * Show will pull a list from the corresponding AdvertTemplate template
	 * 
	 * <pre>
	 * This Method will produce a list of all the element corresponding to the result of AdvertTemplate
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
		
		$sql = "SELECT template_id,
					title,
					employmenttype.employmenttype_id,
					employmenttype,
					template.catagory_id,
					category.catagory_name,
					office_id,
					dept_id,
					role_id,
					state.name,
					storeLoc_id,
					start_date,
					end_date,
					discription,
					requirments,
					status,
					tracking_id,
					advertisement_id,
					template.create_date,
					template.modify_date,
					template.delete_date 
				FROM template 
				LEFT JOIN category ON template.catagory_id = category.catagory_id
						LEFT JOIN state ON template.state_id = state.state_id
						LEFT JOIN employmenttype ON template.employmenttype_id = employmenttype.employmenttype_id
				WHERE (template.delete_date ='00-00-0000 00:00:00' OR template.delete_date IS NULL)";
		
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
				
				foreach($source['template'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO template (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['template'] AS $key=>$val){
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
	
		$sql = "SELECT template_id,
title,
employmenttype_id,
catagory_id,
office_id,
dept_id,
role_id,
state_id,
storeLoc_id,
start_date,
end_date,
discription,
requirments,
status,
tracking_id,
advertisement_id,
create_date,
modify_date,
delete_date FROM template WHERE template_id = ". $id ." AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)" ;

		
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
				
				foreach($source['template'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE template SET ".implode(', ',$field)." WHERE template_id =". $id;

				foreach($source['template'] AS $key=>$val){
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
			
			header('Location:advertTemplate.php?action=show&id='.$id);
			
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
			
			$sql = "UPDATE template SET delete_date=NOW() WHERE template_id =". $id;

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
	 * <pre>This Method will produce a list of all the element corresponding to the result of AdvertTemplate
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
	
	public function getAdvertTemplateList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		
		$result = $this->lists($orderby, $direction, $filter);
		
		$this->table->removeColumn(array('template_id'));
		
		switch(strtoupper($type)){
		
			case 'AJAX' : $this->table->setRowsOnly(); 
						  $this->table->setIdentifier('template_id');
						  $this->table->setIdentifierPage('advertTemplate');
						  echo $this->table->genterateDisplayTable($result);
						  
				BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'template_id'=>'Template Id',
'title'=>'Title',
'employmenttype_id'=>'Employmenttype Id',
'catagory_id'=>'Catagory Id',
'office_id'=>'Office Id',
'dept_id'=>'Dept Id',
'role_id'=>'Role Id',
'state_id'=>'State Id',
'storeLoc_id'=>'StoreLoc Id',
'start_date'=>'Start Date',
'end_date'=>'End Date',
'discription'=>'Discription',
'requirments'=>'Requirments',
'status'=>'Status',
'tracking_id'=>'Tracking Id',
'advertisement_id'=>'Advertisement Id',
'create_date'=>'Create Date',
'modify_date'=>'Modify Date',
'delete_date'=>'Delete Date'));
				
				$this->table->setFilter(array(	
						'template_id'=>'TEXT',
'title'=>'TEXT',
'employmenttype_id'=>'TEXT',
'catagory_id'=>'TEXT',
'office_id'=>'TEXT',
'dept_id'=>'TEXT',
'role_id'=>'TEXT',
'state_id'=>'TEXT',
'storeLoc_id'=>'TEXT',
'start_date'=>'TEXT',
'end_date'=>'TEXT',
'discription'=>'TEXT',
'requirments'=>'TEXT',
'status'=>'TEXT',
'tracking_id'=>'TEXT',
'advertisement_id'=>'TEXT',
'create_date'=>'TEXT',
'modify_date'=>'TEXT',
'delete_date'=>'TEXT'));
				
				$this->table->setIdentifier('template_id');
				
				$this->template->content(Box($this->table->genterateDisplayTable($result),'AdvertTemplate List', 'Shows the current listings for the AdvertTemplate. To create a new Listing <a href="advertTemplate.php?action=create">Click Here</a>'));
				
				$this->template->display();
		}
	}
	
	
	/**
	 * Show details of a single AdvertTemplate from the template
	 * 
	 * <pre>This method will return a template page of the information requested 
	 * the method use the template class to format the information ready to display the 
	 * the user 
	 * 
	 * <div style="color:red">NOTE: This is generated information from the framework and will need to be corrected if there are any changes</div>
	 * </pre>
	 * @param Integer $id the primary id of the row to show 
	 */
	Public function showAdvertTemplateDetails($id){
		$fieldMember = $this->read($id);
		
		$this->template->page('advertTemplate.tpl.html');
		
		$this->templateAdvertTemplateLayout($fieldMember);

		//if($this->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='advertTemplate.php?action=edit&id=".$id."'\">Edit</div>");
		//}
		
		echo $this->template->fetch();	
	}
		
	
	/**
	 * Show the details ready to edit of a single AdvertTemplate from the template 
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
	Public function editAdvertTemplateDetails($id){
		
		$fieldMember = $this->read($id);
		
		$name = 'editAdvertTemplate';
		
		$this->template->page('advertTemplate.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="advertTemplate.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
		$this->templateAdvertTemplateLayout($fieldMember, true);
		
		$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Update\"><div class=\"button\" onclick=\"location.href='advertTemplate.php?action=show&id=".$id."'\">Cancel</div>");
		
		$this->template->display();
	}
	
	/**
	 * update the information in a single AdvertTemplate from the template 
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
	Public function updateAdvertTemplateDetails($id){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'template';

				$save[$table]['title'] = $request['title'];
				$save[$table]['employmenttype_id'] = $request['employmenttype_id'];
				$save[$table]['catagory_id'] = $request['catagory_id'];
				$save[$table]['office_id'] = $request['office_id'];
				$save[$table]['dept_id'] = $request['dept_id'];
				$save[$table]['role_id'] = $request['role_id'];
				$save[$table]['state_id'] = $request['state_id'];
				$save[$table]['storeLoc_id'] = $request['storeLoc_id'];
				$save[$table]['start_date'] = formatDateUI($request['start_date']);
				$save[$table]['end_date'] = formatDateUI($request['end_date']);
				$save[$table]['discription'] = $request['discription'];
				$save[$table]['requirments'] = $request['requirments'];
				$save[$table]['status'] = $request['status'];
				$save[$table]['tracking_id'] = $request['tracking_id'];
				$save[$table]['advertisement_id'] = $request['advertisement_id'];
				
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				header('Location: advertTemplate.php?action=show&id='.$id);
			}else{
				
				$fieldMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editAdvertTemplate';
		
				$this->template->page('advertTemplate.tpl.html');
				
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $error[$key])."</spam>");
				}
				
				$this->template->assign('FORM-HEADER', '<form action="advertTemplate.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateAdvertTemplateLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Update\"><div class=\"button\" onclick=\"location.href='advertTemplate.php?action=show&id=".$id."'\">Cancel</div>");
				//}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	/**
	 * This method will provide a page to the to add a single row AdvertTemplate to the template table
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
	Public function createAdvertTemplateDetails(){
		
		$name = 'createAdvertTemplate';
		
		$this->template->page('advertTemplate.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="advertTemplate.php?action=save" method="POST" name="'.$name.'">');
		
		$this->templateAdvertTemplateLayout('', true);
		
		$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Save\"><div class=\"button\" onclick=\"location.href='advertTemplate.php?action=list'\">Cancel</div>");
		

		$this->template->display();
	} 
	
	/**
	 * save the information in a single AdvertTemplate to the template table 
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
	Public function saveAdvertTemplateDetails(){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'template';

				$save[$table]['title'] = $request['title'];
				$save[$table]['employmenttype_id'] = $request['employmenttype_id'];
				$save[$table]['catagory_id'] = $request['catagory_id'];
				$save[$table]['office_id'] = $request['office_id'];
				$save[$table]['dept_id'] = $request['dept_id'];
				$save[$table]['role_id'] = $request['role_id'];
				$save[$table]['state_id'] = $request['state_id'];
				$save[$table]['storeLoc_id'] = $request['storeLoc_id'];
				$save[$table]['start_date'] = formatDateUI($request['start_date']);
				$save[$table]['end_date'] = formatDateUI($request['end_date']);
				$save[$table]['discription'] = $request['discription'];
				$save[$table]['requirments'] = $request['requirments'];
				$save[$table]['status'] = $request['status'];
				$save[$table]['tracking_id'] = $request['tracking_id'];
				$save[$table]['advertisement_id'] = $request['advertisement_id'];
				
				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				$id = $this->create($save);
				header('Location: advertTemplate.php?action=show&id='.$id);
			}else{
			
				$fieldMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createAdvertTemplate';
	
				$this->template->page('advertTemplate.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="advertTemplate.php?action=save" method="POST" name="'.$name.'">');
		
				$this->templateAdvertTemplateLayout($fieldMember, true);
				
				//if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<input class=\"button\" type=\"image\" value=\"Update\"><div class=\"button\" onclick=\"location.href='advertTemplate.php\">Cancel</div>");
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
	Public function deleteAdvertTemplateDetails($id){
		$this->remove($id);
		header('Location: advertTemplate.php');
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
	private function templateAdvertTemplateLayout($fieldMember, $input = false, $inputArray=array() ){
				
				$id = @$fieldMember['template_id'];

				@$this->template->assign('title', ($input)? $this->template->input('text', 'title', $fieldMember['title']):$fieldMember['title']);
				@$this->template->assign('employmenttype_id', ($input)? $this->getSelectListOfEmploymentType($fieldMember['employmenttype_id'], True):$this->getSelectListOfEmploymentType($fieldMember['employmenttype_id']));
				@$this->template->assign('catagory_id',  ($input)? $this->getSelectListOfCategory($fieldMember['catagory_id'], True):$this->getSelectListOfCategory($fieldMember['catagory_id']));
				@$this->template->assign('office_id', ($input)? $this->template->input('text', 'office_id', $fieldMember['office_id']):$fieldMember['office_id']);
				@$this->template->assign('dept_id', ($input)? $this->template->input('text', 'dept_id', $fieldMember['dept_id']):$fieldMember['dept_id']);
				@$this->template->assign('role_id', ($input)? $this->template->input('text', 'role_id', $fieldMember['role_id']):$fieldMember['role_id']);
				@$this->template->assign('state_id', ($input)? $this->getSelectListOfStates($fieldMember['state_id'], True):$this->getSelectListOfStates($fieldMember['state_id']));
				@$this->template->assign('storeLoc_id', ($input)? $this->template->input('text', 'storeLoc_id', $fieldMember['storeLoc_id']):$fieldMember['storeLoc_id']);
				@$this->template->assign('start_date', ($input)? $this->template->input('text', 'start_date', $fieldMember['start_date']):$fieldMember['start_date']);
				@$this->template->assign('end_date', ($input)? $this->template->input('text', 'end_date', $fieldMember['end_date']):$fieldMember['end_date']);
				@$this->template->assign('discription', ($input)? $this->template->input('textarea', 'discription', $fieldMember['discription']):$fieldMember['discription']);
				@$this->template->assign('requirments', ($input)? $this->template->input('textarea', 'requirments', $fieldMember['requirments']):$fieldMember['requirments']);
				@$this->template->assign('status', ($input)? $this->template->input('text', 'status', $fieldMember['status']):$fieldMember['status']);
				@$this->template->assign('tracking_id', ($input)? $this->template->input('text', 'tracking_id', $fieldMember['tracking_id']):$fieldMember['tracking_id']);
				@$this->template->assign('advertisement_id', ($input)? $this->template->input('text', 'advertisement_id', $fieldMember['advertisement_id']):$fieldMember['advertisement_id']);
				@$this->template->assign('create_date', ($input)? $this->template->input('text', 'create_date', $fieldMember['create_date']):$fieldMember['create_date']);
				@$this->template->assign('modify_date', ($input)? $this->template->input('text', 'modify_date', $fieldMember['modify_date']):$fieldMember['modify_date']);
				@$this->template->assign('delete_date', ($input)? $this->template->input('text', 'delete_date', $fieldMember['delete_date']):$fieldMember['delete_date']);

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'advertTemplate'));
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
	
	/**
	 * This Method will either the catagory_name feild or a select box of catagory_name fields
	 * 
	 * This Methos is based in the catagory_id of the category table, it will return either the information 
	 * in the catagory_name field as a string or a select box with the id selected 
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfCategory($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('category', $id, 'catagory_id', 'catagory_name');
			}else{
				$select = "<select name=\"catagory_id\">";
				$select .= "<option value=\"NULL\"></option>";
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
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfStates($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('state', $id, 'state_id', 'name');
			}else{
				$select = "<select name=\"state_id\">";
				$select .= "<option value=\"NULL\"></option>";
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
	 * @param Integer $id the id of the row we are looking for 
	 * @param Boolean $selectBox True will return it as a select box with field selected, else just the field
	 * @return string 
	 */
	public function getSelectListOfEmploymentType($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('employmenttype', $id, 'employmenttype_id', 'employmenttype');
			}else{
				$select = "<select name=\"employmenttype_id\">";
				$select .= "<option value=\"NULL\"></option>";
				$select .= $this->template->getListTable('employmenttype', $id, 'employmenttype_id', 'employmenttype', $selectBox);
				$select .= "</select>";
				return $select;
			}
	}
}