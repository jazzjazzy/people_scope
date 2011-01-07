<?php

require_once('administration.class.php');
require_once('division.class.php');

class users {

	private $db_connect;
	private $db;
	public $lastError;
	public $table;
	public $template;
	public $administration;
	public $division;
	private $error;
	public $admin;
	private $fields =array('user_id', 'username', 'password', 'name', 'surname', 'email', 'active', 'last_login', 'division_id', 'administration_id', 'create_date', 'modified_date', 'delete_date');
	private $fields_required = array('username', 'password', 'name', 'email');
	private $fields_validation_type = array ('user_id'=>'INT', 'username'=>'TEXT', 'password'=>'TEXT', 'name'=>'TEXT', 'surname'=>'TEXT', 'email'=>'TEXT', 'active'=>'BOOL', 'last_login'=>'TEXT', 'division_id'=>'INT', 'administration_id'=>'INT', 'create_date'=>'TEXT', 'modified_date'=>'TEXT', 'delete_date'=>'TEXT');

	function __construct(){
		$this->db = new db();

		try {
			$this->db_connect = $this->db->dbh;
		} catch (CustomException $e) {
			$e->logError();
		}

		$this->table = new table();
		$this->template = new template();
		$this->administration = new administration();
		$this->division = new division();

	}

	Private function lists($orderby=NULL, $direction='ASC', $filter=NULL){

		$sql = "SELECT 	user_id,
						username,
						password,
						users.name,
						surname,
						email,
						active,
						DATE_FORMAT(last_login, '%d/%m/%Y') AS last_login,
						division.name AS division_name,
						group_name,
						DATE_FORMAT(users.create_date, '%d/%m/%Y') AS create_date,
						DATE_FORMAT(users.modify_date, '%d/%m/%Y') AS modify_date,
						DATE_FORMAT(users.delete_date, '%d/%m/%Y') AS delete_date 
				FROM users 
					LEFT JOIN administration ON users.administration_id=administration.administration_id
					LEFT JOIN division ON users.division_id=division.division_id
				WHERE (users.delete_date ='00-00-0000 00:00:00' OR users.delete_date IS NULL)";

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
		
		try {
			$conn2 = new PDO('mysql:dbname=people_scope_main;host=DEV;port=3306', 'root', 'password');
		} catch (PDOException $e) {
			echo $e->getMessage();
		}

		$conn2->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "INSERT INTO users (user_name, user_password, create_date) VALUES ('".$source['users']['username']."','".$source['users']['password']."',NOW());";

		try{
			$stmt2 = $conn2->prepare($sql);
		}catch(CustomException $e){
			throw new CustomException('QUERY : '. $e->getMessage());
		}

		$stmt2->execute();
		
		if ($stmt2->errorCode() != 00000 )
		{
		  	$error = $stmt2->errorInfo();
		  	throw new CustomException($error[2]);
		}
		
		$source['users']['user_id'] = $conn2->lastInsertId();
		
		$sql = "INSERT INTO clients_users (client_id, user_id) VALUES (".$_SESSION['user']['client_id'].", ".$source['users']['user_id'].")";
		echo $sql;
		try{
			$stmt2 = $conn2->prepare($sql);
		}catch(CustomException $e){
			throw new CustomException('QUERY : '. $e->getMessage());
		}

		$stmt2->execute();
		
		if ($stmt2->errorCode() != 00000 )
		{
		  	$error = $stmt2->errorInfo();
		  	throw new CustomException($error[2]);
		}

		try{
			$this->db_connect->beginTransaction();

			foreach($source['users'] AS $key=>$val){
				$field[] = $key;
				$value[] = ":".$key;
			}

			$sql = "INSERT INTO users (".implode(', ',$field).") VALUES (".implode(', ',$value).");";

			foreach($source['users'] AS $key=>$val){
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

		$sql = "SELECT user_id,
							username,
							password,
							users.name,
							surname,
							email,
							active,
							last_login,
							users.division_id,
							division.name AS devision_name,
							group_name,
							users.administration_id,
							users.create_date,
							users.modify_date,
							users.delete_date FROM users 
				LEFT JOIN administration ON users.administration_id=administration.administration_id
				LEFT JOIN division ON users.division_id=division.division_id
				WHERE user_id = ". $id ." AND (users.delete_date ='00-00-0000 00:00:00' OR users.delete_date IS NULL)";


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

			foreach($source['users'] AS $key=>$val){
				$field[] = $key." = :".$key;
			}

			$sql = "UPDATE users SET ".implode(', ',$field)." WHERE user_id =". $id;

			foreach($source['users'] AS $key=>$val){
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

		header('Location:users.php?action=show&id='.$id);
			
	}

	Private function remove($id){
		if(empty($id)){
			return false;
		}
			
		$sql = "UPDATE users SET delete_date=NOW() WHERE user_id =". $id;

		try{
			$result = $this->db->update($sql);
		}catch(CustomException $e){
			echo $e->queryError($sql);
		}
		return true;
	}


	/******************* END CRUD METHOD*************************/

	public function getUsersList($type='TABLE',$orderby=NULL, $direction='ASC', $filter=NULL){

		$result = $this->lists($orderby, $direction, $filter);

		switch(strtoupper($type)){

			case 'AJAX' : $this->table->setRowsOnly();
			$this->table->removeColumn(array('user_id'));
			$this->table->setIdentifier('user_id');
			$this->table->setIdentifierPage('users');
			echo $this->table->genterateDisplayTable($result);

			BREAK;
			case 'TABLE' :
			DEFAULT :
				$this->table->setHeader(array(
						'user_id'=>'User Id',
						'username'=>'Username',
						'password'=>'Password',
						'name'=>'Name',
						'surname'=>'Surname',
						'email'=>'Email',
						'active'=>'Active',
						'last_login'=>'Login',
						'division_name'=>'Division',
						'group_name'=>'Group',
						'create_date'=>'Created'));

				$this->table->setFilter(array(
						'user_id'=>'TEXT',
						'username'=>'TEXT',
						'password'=>'TEXT',
						'name'=>'TEXT',
						'surname'=>'TEXT',
						'email'=>'TEXT',
						'active'=>'TEXT',
						'last_login'=>'TEXT',
						'division_name'=>'TEXT',
						'group_name'=>'COMPILED',
						'create_date'=>'TEXT'));

				$this->table->removeColumn(array('user_id', 'modify_date', 'delete_date'));

				$this->table->setIdentifier('user_id');

				$this->template->content(box($this->table->genterateDisplayTable($result), "User List", "list of all current users"));

				$this->template->display();
		}
	}

	Public function showUsersDetails($id, $return=false){
		$staffMember = $this->read($id);

		$this->template->page('users.tpl.html');

		$this->templateUsersLayout($staffMember);

		//if($this->checkAdminLevel(1)){
		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"location.href='users.php?action=edit&id=".$id."'\">Edit</div>");
		//}

		echo $this->template->fetch();
	}

	Public function editUsersDetails($id){

		$staffMember = $this->read($id);

		$name = 'editusers';

		$this->template->page('users.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="users.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');

		$this->templateUsersLayout($staffMember, true);

		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='users.php?action=show&id=".$id."'\">Cancel</div>");

		$this->template->display();
	}


	Public function updateUsersDetails($id){

		if ($this->Validate($_REQUEST)){

			$request = $_REQUEST;
			$table = 'users';

			$save[$table]['username'] = $request['username'];
			$save[$table]['password'] = $request['password'];
			$save[$table]['name'] = $request['name'];
			$save[$table]['surname'] = $request['surname'];
			$save[$table]['email'] = $request['email'];
			$save[$table]['active'] = $request['active'];
			$save[$table]['last_login'] = $request['last_login'];
			$save[$table]['division_id'] = $request['division_id'];
			$save[$table]['administration_id'] = $request['administration_id'];
			$save[$table]['modify_date'] = date('Y-m-d h:i:s');

			$this->update($save, $id );

		}else{

			$staffMember = $this->valid_field;
			$error = $this->validation_error;

			$name = 'editgrant';

			$this->template->page('users.tpl.html');

			foreach($validfields AS $value){
				if(isset($error[$value])){
					$this->template->assign('err_'.$value, "<span class=\"error\">".implode(',', $error[$value])."</spam>");
				}
			}

			$this->template->assign('FORM-HEADER', '<form action="users.php?action=update&id='.$id.'" method="POST" name="'.$name.'">');

			$this->templateUsersLayout($staffMember, true);

			if($this->admin->checkAdminLevel(1)){
				$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Update</div><div class=\"button\" onclick=\"location.href='users.php?action=show&id=".$id."'\">Cancel</div>");
			}
			$this->template->assign('FORM-FOOTER', '</form>');

			$this->template->display();
		}
	}


	Public function createUsersDetails(){

		$name = 'createAdmin';

		$this->template->page('users.tpl.html');
		$this->template->assign('FORM-HEADER', '<form action="users.php?action=save" method="POST" name="'.$name.'">');

		$this->templateUsersLayout('', true);

		$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='users.php?action=list'\">Cancel</div>");


		$this->template->display();
	}

	Public function saveUsersDetails(){

		if ($this->Validate($_REQUEST)){

			$request = $_REQUEST;
			$table = 'users';

			$save[$table]['active'] = (isset($request['active']))?1:0;

			$save[$table]['username'] = $request['username'];
			$save[$table]['password'] = $request['password'];
			$save[$table]['name'] = $request['name'];
			$save[$table]['surname'] = $request['surname'];
			$save[$table]['email'] = $request['email'];
			$save[$table]['active'] = (isset($request['active']))?1:0;
			$save[$table]['last_login'] = $request['last_login'];
			$save[$table]['division_id'] = $request['division_id'];
			$save[$table]['administration_id'] = $request['administration_id'];
			$save[$table]['create_date'] = date('Y-m-d h:i:s');

			$id = $this->create($save);
			header('Location: users.php?action=show&id='.$id);

		}else{
				
			$staffMember = $this->valid_field;

			$error = $this->validation_error;

			$name = 'createusers';

			$this->template->page('users.tpl.html');

			foreach($error AS $key=>$value){
				$this->template->assign('err_'.$key, "<span class=\"error\">".@implode(',', $value)."</spam>");
			}

			$this->template->assign('FORM-HEADER', '<form action="users.php?action=save" method="POST" name="'.$name.'">');

			$this->templateUsersLayout($staffMember, true);

			//if($this->admin->checkAdminLevel(1)){
			$this->template->assign('FUNCTION', "<div class=\"button\" onclick=\"document.$name.submit(); return false\">Save</div><div class=\"button\" onclick=\"location.href='users.php'\">Cancel</div>");
			//}
			$this->template->assign('FORM-FOOTER', '</form>');

			$this->template->display();
		}
	}

	Public function deleteClientsDetails($id){
		$this->remove($id);
		header('Location: users.php');
	}

	private function templateUsersLayout($staffMember, $input = false, $inputArray=array() ){

		$id = @$staffMember['user_id'];

		/*@$this->template->assign('user_id', ($input)? $this->template->input('text', 'user_id', $staffMember['user_id']):$staffMember['user_id']);*/
		@$this->template->assign('username', ($input)? $this->template->input('text', 'username', $staffMember['username']):$staffMember['username']);
		@$this->template->assign('password', ($input)? $this->template->input('text', 'password', $staffMember['password']):$staffMember['password']);
		@$this->template->assign('name', ($input)? $this->template->input('text', 'name', $staffMember['name']):$staffMember['name']);
		@$this->template->assign('surname', ($input)? $this->template->input('text', 'surname', $staffMember['surname']):$staffMember['surname']);
		@$this->template->assign('email', ($input)? $this->template->input('text', 'email', $staffMember['email']):$staffMember['email']);
		@$this->template->assign('active', ($input)? $this->template->input('checkbox', 'active', $this->template->formatBoolean($staffMember['active'])):$this->template->formatBoolean($staffMember['active'] ));
		@$this->template->assign('last_login', ($input)? $this->template->input('text', 'last_login', $staffMember['last_login']):$staffMember['last_login']);
		$this->template->assign('division_id', ($input)?  $this->division->getSelectListOfDivision($staffMember['division_id'], TRUE):$this->division->getSelectListOfDivision($staffMember['division_id']));
		$this->template->assign('group_name', ($input)? $this->administration->getSelectListOfAdministration($staffMember['administration_id'], TRUE):$this->administration->getSelectListOfAdministration($staffMember['administration_id']));

		/*if(isset($id)){
		 $this->template->assign('COMMENTS', $this->comment->getCommentBox($id, 'users'));
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
}