<?php

class administration {
	
	private $db_connect;
	private $db;
	public $lastError;
	public $table;
	public $template;
	private $error;
	public $admin;
	private $fields =array('administration_id', 'group_name', 'create_advert', 'edit_advert', 'remove_advert', 'create_template', 'edit_template', 'remove_template', 'add_user', 'edit_user', 'delete_user', 'edit_status', 'edit_referral');
	private $fields_required = NULL;
	private $fields_validation_type = array ('administration_id'=>'INT', 'group_name'=>'TEXT', 'create_advert'=>'BOOL', 'edit_advert'=>'BOOL', 'remove_advert'=>'BOOL', 'create_template'=>'BOOL', 'edit_template'=>'BOOL', 'remove_template'=>'BOOL', 'add_user'=>'BOOL', 'edit_user'=>'BOOL', 'delete_user'=>'BOOL', 'edit_status'=>'BOOL', 'edit_referral'=>'BOOL');
	
	function __construct(){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}
		
		$this->table = new table();
		$this->template = new template();
	
	}
	
	Private function lists($orderby=NULL, $direction='ASC', $filter=NULL){
		
		$sql = "SELECT administration_id,
					group_name,
					create_advert,
					edit_advert,
					remove_advert,
					create_template,
					edit_template,
					remove_template,
					add_user,
					edit_user,
					delete_user,
					edit_status,
					edit_referral 
				FROM administration";
		
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
	
	Private function create($source){
			try{
				$this->db_connect->beginTransaction();
				
				foreach($source['administration'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO administration (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['administration'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
					$pid = $this->db->insert($sql, $exec); 
				}catch(CustomException $e){
					throw new CustomException($e->queryError($sql));
				}

				$pid = $this->db_connect->lastInsertId();
			}

			catch (CustomException $e) {
				$e->queryError($sql);
				$this->db_connect->rollBack();
				return false;
			}

			return $pid;
	}
	
	
	Private function read($id){
	
		$sql = "SELECT administration_id,
					group_name,
					create_advert,
					edit_advert,
					remove_advert,
					create_template,
					edit_template,
					remove_template,
					add_user,
					edit_user,
					delete_user,
					edit_status,
					edit_referral 
				FROM administration WHERE administration_id = ". $id ."" ;

		
			$stmt = $this->db_connect->prepare($sql);
			$stmt->execute();
			
			try{
				 $result = $this->db->select($sql);
			}catch(CustomException $e){
				 echo $e->queryError($sql);
			}

			return $result[0];
		
			
	}
	
	Private function update($source, $id){
			try{
				$this->db_connect->beginTransaction();
				
				foreach($source['administration'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE administration SET ".implode(', ',$field)." WHERE administration_id =". $id;
				
				$stmt = $this->db_connect->prepare($sql);
				
				foreach($source['administration'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				$stmt->execute($exec); 

				
				if ($stmt->errorCode() != 00000 )
			    {
			    	$this->error->set_error($stmt->errorInfo(), $sql);
					$this->error->get_error();
					$this->db_connect->rollBack();
			    }
	    

				//$id = $this->db_connect->lastInsertId();
				$source['administration']['administration_id'] = $id;

			}

			catch (Exception $e) {
				$this->error->set_error($stmt->errorInfo(), $sql);
				$this->error->get_error();
				$this->db_connect->rollBack();
			}
			
			header('Location:administration.php?action=show&id='.$id);
			
	}
	
	Private function remove($id){
			if(empty($id)){
				return false;
			}
			
			$sql = "UPDATE administration SET delete_date=NOW() WHERE administration_id =". $id;

			try{
				$result = $this->db->update($sql);
			}catch(CustomException $e){
				echo $e->queryError($sql);
			}
			return true;
	}
	
	
	/******************* END CRUD METHOD*************************/
	
	public function getAdministrationList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		
		$result = $this->lists($orderby, $direction, $filter);
		
		switch(strtoupper($type)){
		
			case 'AJAX' : $this->table->setRowsOnly(); 
						  $this->table->removeColumn(array('administration_id'));
						  $this->table->setIdentifier('administration_id');
						  $this->table->setIdentifierPage('administration');
						  echo $this->table->genterateDisplayTable($result);
						  
				BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'administration_id'=>'Administration Id',
'group_name'=>'Group Name',
'create_advert'=>'Create Advert',
'edit_advert'=>'Edit Advert',
'remove_advert'=>'Remove Advert',
'create_template'=>'Create Template',
'edit_template'=>'Edit Template',
'remove_template'=>'Remove Template',
'add_user'=>'Add User',
'edit_user'=>'Edit User',
'delete_user'=>'Delete User',
'edit_status'=>'Edit Status',
'edit_referral'=>'Edit Referral'));
				
				$this->table->setFilter(array(	
						'administration_id'=>'TEXT',
'group_name'=>'TEXT',
'create_advert'=>'TEXT',
'edit_advert'=>'TEXT',
'remove_advert'=>'TEXT',
'create_template'=>'TEXT',
'edit_template'=>'TEXT',
'remove_template'=>'TEXT',
'add_user'=>'TEXT',
'edit_user'=>'TEXT',
'delete_user'=>'TEXT',
'edit_status'=>'TEXT',
'edit_referral'=>'TEXT'));
				
				$this->table->removeColumn(array('administration_id'));
				
				$this->table->setIdentifier('administration_id');
				
				$this->template->content($this->table->genterateDisplayTable($result));
				
				$this->template->display();
		}
	}
	
	Public function showAdministrationDetails($id, $return=false){
		$staffMember = $this->read($id);
		
		$this->template->page('administration.tpl.html');
		
		$this->templateAdministrationLayout($staffMember);

		//if($this->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='administration.php?action=edit&id=".$id."'\">Edit</div>");
		//}
		
		echo $this->template->fetch();	
	}
	
	Public function editAdministrationDetails($id){
		
		$staffMember = $this->read($id);
		
		$name = 'editadministration';
		
		$this->template->page('administration.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="administration.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
		$this->templateAdministrationLayout($staffMember, true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='administration.php?action=show&id=".$id."'\">Cancel</div>");
		
		$this->template->display();
	}
	
	
	Public function updateAdministrationDetails($id){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'administration';

				$save[$table]['group_name'] = $request['group_name'];$save[$table]['create_advert'] = $request['create_advert'];$save[$table]['edit_advert'] = $request['edit_advert'];$save[$table]['remove_advert'] = $request['remove_advert'];$save[$table]['create_template'] = $request['create_template'];$save[$table]['edit_template'] = $request['edit_template'];$save[$table]['remove_template'] = $request['remove_template'];$save[$table]['add_user'] = $request['add_user'];$save[$table]['edit_user'] = $request['edit_user'];$save[$table]['delete_user'] = $request['delete_user'];$save[$table]['edit_status'] = $request['edit_status'];$save[$table]['edit_referral'] = $request['edit_referral'];
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				
			}else{
				
				$staffMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editgrant';
		
				$this->template->page('administration.tpl.html');
				
				foreach($validfields AS $value){
					if(isset($error[$value])){
						$this->template->assign('err_'.$value, "<span class=\"error\">".implode(',', $error[$value])."</spam>");
					}
				}
				
				$this->template->assign('FORM-HEADER', '<form action="administration.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateAdministrationLayout($staffMember, true);
				
				if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='administration.php?action=show&id=".$id."'\">Cancel</div>");
				}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	
	Public function createAdministrationDetails(){
		
		$name = 'createAdmin';
		
		$this->template->page('administration.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="administration.php?action=save" method="POST" name="'.$name.'">');
		
		$this->templateAdministrationLayout('', true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='administration.php?action=list'\">Cancel</div>");
		

		$this->template->display();
	} 
	
	Public function saveAdministrationDetails(){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'administration';

				$save[$table]['group_name'] = $request['group_name'];$save[$table]['create_advert'] = $request['create_advert'];$save[$table]['edit_advert'] = $request['edit_advert'];$save[$table]['remove_advert'] = $request['remove_advert'];$save[$table]['create_template'] = $request['create_template'];$save[$table]['edit_template'] = $request['edit_template'];$save[$table]['remove_template'] = $request['remove_template'];$save[$table]['add_user'] = $request['add_user'];$save[$table]['edit_user'] = $request['edit_user'];$save[$table]['delete_user'] = $request['delete_user'];$save[$table]['edit_status'] = $request['edit_status'];$save[$table]['edit_referral'] = $request['edit_referral'];
				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				$id = $this->create($save);
				header('Location: administration.php?action=show&id='.$id);
				
			}else{
			
				$staffMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createadministration';
	
				$this->template->page('administration.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $error[12])."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="administration.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateAdministrationLayout($staffMember, true);
				
				if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='administration.php?action=show&id=".$id."'\">Cancel</div>");
				}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}

	Public function deleteClientsDetails($id){
		$this->remove($id);
		header('Location: administration.php');
	}
	
	private function templateAdministrationLayout($staffMember, $input = false, $inputArray=array() ){
				
				$id = $staffMember['administration_id'];

				@$this->template->assign('administration_id', ($input)? $this->template->input('text', 'administration_id', $staffMember['administration_id']):$staffMember['administration_id']);
@$this->template->assign('group_name', ($input)? $this->template->input('text', 'group_name', $staffMember['group_name']):$staffMember['group_name']);
@$this->template->assign('create_advert', ($input)? $this->template->input('checkbox', 'create_advert', $this->template->formatBoolean($staffMember['create_advert'])):$this->template->formatBoolean($staffMember['create_advert'] ));
@$this->template->assign('edit_advert', ($input)? $this->template->input('checkbox', 'edit_advert', $this->template->formatBoolean($staffMember['edit_advert'])):$this->template->formatBoolean($staffMember['edit_advert'] ));
@$this->template->assign('remove_advert', ($input)? $this->template->input('checkbox', 'remove_advert', $this->template->formatBoolean($staffMember['remove_advert'])):$this->template->formatBoolean($staffMember['remove_advert'] ));
@$this->template->assign('create_template', ($input)? $this->template->input('checkbox', 'create_template', $this->template->formatBoolean($staffMember['create_template'])):$this->template->formatBoolean($staffMember['create_template'] ));
@$this->template->assign('edit_template', ($input)? $this->template->input('checkbox', 'edit_template', $this->template->formatBoolean($staffMember['edit_template'])):$this->template->formatBoolean($staffMember['edit_template'] ));
@$this->template->assign('remove_template', ($input)? $this->template->input('checkbox', 'remove_template', $this->template->formatBoolean($staffMember['remove_template'])):$this->template->formatBoolean($staffMember['remove_template'] ));
@$this->template->assign('add_user', ($input)? $this->template->input('checkbox', 'add_user', $this->template->formatBoolean($staffMember['add_user'])):$this->template->formatBoolean($staffMember['add_user'] ));
@$this->template->assign('edit_user', ($input)? $this->template->input('checkbox', 'edit_user', $this->template->formatBoolean($staffMember['edit_user'])):$this->template->formatBoolean($staffMember['edit_user'] ));
@$this->template->assign('delete_user', ($input)? $this->template->input('checkbox', 'delete_user', $this->template->formatBoolean($staffMember['delete_user'])):$this->template->formatBoolean($staffMember['delete_user'] ));
@$this->template->assign('edit_status', ($input)? $this->template->input('checkbox', 'edit_status', $this->template->formatBoolean($staffMember['edit_status'])):$this->template->formatBoolean($staffMember['edit_status'] ));
@$this->template->assign('edit_referral', ($input)? $this->template->input('checkbox', 'edit_referral', $this->template->formatBoolean($staffMember['edit_referral'])):$this->template->formatBoolean($staffMember['edit_referral'] ));

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'administration'));
				}*/
	
	}
	
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
					case 'DATE': $date = str_replace('/', '-', $value);
								 $date = str_replace("\\", '-', $date);
									@list($day, $month, $year) = explode('-', $date);
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
	
	public function getSelectListOfAdministration($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('administration', $id, 'administration_id', 'group_name');
			}else{
				$select = "<select name=\"administration_id\">";
				$select .= "<option value=\"NULL\"></option>";
				$select .= $this->template->getListTable('administration', $id, 'administration_id', 'group_name', $selectBox);
				$select .= "</select>";
				return $select;
			}
	}
}