<?php

class division {
	
	private $db_connect;
	private $db;
	public $lastError;
	public $table;
	public $template;
	private $error;
	public $admin;
	private $fields =array('division_id', 'name', 'description', 'create_date', 'modify_date', 'delete_date');
	private $fields_required = NULL;
	private $fields_validation_type = array ('division_id'=>'INT', 'name'=>'TEXT', 'description'=>'TEXT', 'create_date'=>'TEXT', 'modify_date'=>'TEXT', 'delete_date'=>'TEXT');
	
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
		
		$sql = "SELECT division_id,
name,
description,
create_date,
modify_date,
delete_date FROM division WHERE (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)";
		
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
				
				foreach($source['division'] AS $key=>$val){
					$field[] = $key;
					$value[] = ":".$key;
				}

				$sql = "INSERT INTO division (".implode(', ',$field).") VALUES (".implode(', ',$value).");";
				
				foreach($source['division'] AS $key=>$val){
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
	
		$sql = "SELECT division_id,
	name,
	description,
	create_date,
	modify_date,
	delete_date FROM division WHERE division_id = ". $id ." AND (delete_date ='00-00-0000 00:00:00' OR delete_date IS NULL)" ;

		
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
				
				foreach($source['division'] AS $key=>$val){
					$field[] = $key." = :".$key;
				}

				$sql = "UPDATE division SET ".implode(', ',$field)." WHERE division_id =". $id;

				foreach($source['division'] AS $key=>$val){
					$exec[":".$key] = $val;
				}
				
				try{
						$pid = $this->db->update($sql, $exec); 
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
			
			header('Location:division.php?action=show&id='.$id);
			
	}
	
	Private function remove($id){
			if(empty($id)){
				return false;
			}
			
			$sql = "UPDATE division SET delete_date=NOW() WHERE division_id =". $id;

			try{
				$result = $this->db->update($sql);
			}catch(CustomException $e){
				echo $e->queryError($sql);
			}
			return true;
	}
	
	
	/******************* END CRUD METHOD*************************/
	
	public function getDivisionList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){
		
		$result = $this->lists($orderby, $direction, $filter);
		
		switch(strtoupper($type)){
		
			case 'AJAX' : $this->table->setRowsOnly(); 
						  $this->table->removeColumn(array('division_id'));
						  $this->table->setIdentifier('division_id');
						  $this->table->setIdentifierPage('division');
						  echo $this->table->genterateDisplayTable($result);
						  
				BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'division_id'=>'Division Id',
						'name'=>'Name',
						'description'=>'Description',
						'create_date'=>'Create Date',
						'modify_date'=>'Modify Date',
						'delete_date'=>'Delete Date'));
				
				$this->table->setFilter(array(	
						'division_id'=>'TEXT',
						'name'=>'TEXT',
						'description'=>'TEXT',
						'create_date'=>'TEXT',
						'modify_date'=>'TEXT',
						'delete_date'=>'TEXT'));
				
				$this->table->removeColumn(array('division_id'));
				
				$this->table->setIdentifier('division_id');
				
				$this->template->content($this->table->genterateDisplayTable($result));
				
				$this->template->display();
		}
	}
	
	Public function showDivisionDetails($id, $return=false){
		$staffMember = $this->read($id);
		
		$this->template->page('division.tpl.html');
		
		$this->templateDivisionLayout($staffMember);

		//if($this->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='division.php?action=edit&id=".$id."'\">Edit</div>");
		//}
		
		echo $this->template->fetch();	
	}
	
	Public function editDivisionDetails($id){
		
		$staffMember = $this->read($id);
		
		$name = 'editdivision';
		
		$this->template->page('division.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="division.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
		$this->templateDivisionLayout($staffMember, true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='division.php?action=show&id=".$id."'\">Cancel</div>");
		
		$this->template->display();
	}
	
	
	Public function updateDivisionDetails($id){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'division';

				$save[$table]['name'] = $request['name'];$save[$table]['description'] = $request['description'];
				$save[$table]['modify_date'] = date('Y-m-d h:i:s');
				
				$this->update($save, $id );
				
			}else{
				
				$staffMember = $this->valid_field;
				$error = $this->validation_error;
				
				$name = 'editgrant';
		
				$this->template->page('division.tpl.html');
				
				foreach($validfields AS $value){
					if(isset($error[$value])){
						$this->template->assign('err_'.$value, "<span class=\"error\">".implode(',', $error[$value])."</spam>");
					}
				}
				
				$this->template->assign('FORM-HEADER', '<form action="division.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');
		
				$this->templateDivisionLayout($staffMember, true);
				
				if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='division.php?action=show&id=".$id."'\">Cancel</div>");
				}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}
	
	
	Public function createDivisionDetails(){
		
		$name = 'createAdmin';
		
		$this->template->page('division.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="division.php?action=save" method="POST" name="'.$name.'">');
		
		$this->templateDivisionLayout('', true);
		
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='division.php?action=list'\">Cancel</div>");
		

		$this->template->display();
	} 
	
	Public function saveDivisionDetails(){

		if ($this->Validate($_REQUEST)){
				
				$request = $_REQUEST;
				$table = 'division';

				$save[$table]['name'] = $request['name'];
				$save[$table]['description'] = $request['description'];
				$save[$table]['create_date'] = date('Y-m-d h:i:s');
				
				$id = $this->create($save);
				header('Location: division.php?action=show&id='.$id);
			}else{
			
				$staffMember = $this->valid_field;

				$error = $this->validation_error;
	
				$name = 'createdivision';
	
				$this->template->page('division.tpl.html');
	
				foreach($error AS $key=>$value){
					$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
				}

				$this->template->assign('FORM-HEADER', '<form action="division.php?action=save" method="POST" name="'.$name.'">');
		
				$this->templateDivisionLayout($staffMember, true);
				
				if($this->admin->checkAdminLevel(1)){
					$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='division.php\">Cancel</div>");
				}
				$this->template->assign('FORM-FOOTER', '</form>');
				
				$this->template->display();
		}
	}

	Public function deleteClientsDetails($id){
		$this->remove($id);
		header('Location: division.php');
	}
	
	private function templateDivisionLayout($staffMember, $input = false, $inputArray=array() ){
				
				$id = @$staffMember['division_id'];

				@$this->template->assign('name', ($input)? $this->template->input('text', 'name', $staffMember['name']):$staffMember['name']);
				@$this->template->assign('description', ($input)? $this->template->input('textarea', 'description', $staffMember['description']):$staffMember['description']);
				@$this->template->assign('create_date', ($input)? $this->template->input('text', 'create_date', $staffMember['create_date']):$staffMember['create_date']);
				@$this->template->assign('modify_date', ($input)? $this->template->input('text', 'modify_date', $staffMember['modify_date']):$staffMember['modify_date']);
				@$this->template->assign('delete_date', ($input)? $this->template->input('text', 'delete_date', $staffMember['delete_date']):$staffMember['delete_date']);

				
				/*if(isset($id)){
					$this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'division'));
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
	
	
	public function getSelectListOfDivision($id, $selectBox=NULL){

			if($selectBox == false){
				return $this->template->getListTable('division', $id, 'division_id', 'name');
			}else{
				$select = "<select name=\"division_id\">";
				$select .= "<option value=\"NULL\"></option>";
				$select .= $this->template->getListTable('division', $id, 'division_id', 'name', $selectBox);
				$select .= "</select>";
				return $select;
			}
	}
}