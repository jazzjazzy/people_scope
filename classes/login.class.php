<?php

class login {
	
	public $lastError;
	public $table;
	public $template;
	private $error;
	public $admin;
		
	function __construct(){
		$this->table = new table();
		$this->template = new template();
	
	}
	
	public function getHomePage(){
		$this->template = new template();
		$this->template->page('index.tpl.html');		
		echo $this->template->display();	
		
	}
	
	function checkUserLogin($uname, $password){		
		try {
			$conn2 = new PDO(MAIN_DB_TYPE.':dbname='.MAIN_DB_DBASE.';host='.MAIN_DB_HOST, MAIN_DB_USER, MAIN_DB_PASS);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}

		$conn2->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "SELECT users.user_id,clients_users.client_id,server_connection.* FROM
				users
				Left Join clients_users ON users.user_id = clients_users.user_id
				Left Join clients_server_connection ON clients_users.client_id = clients_server_connection.client_id
				Left Join server_connection ON clients_server_connection.connection_id = server_connection.connection_id
				WHERE user_name='".$uname."' AND user_password='".$password."'";
		
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
		
		$result = $stmt2->fetch(PDO::FETCH_NAMED);
		
		$_SESSION['dbaccess']['server_host'] = $result['server_host'];
		$_SESSION['dbaccess']['server_port'] = $result['server_port'];
		$_SESSION['dbaccess']['server_database'] = $result['server_database'];
		$_SESSION['dbaccess']['server_type'] = $result['server_type'];
		$_SESSION['user']['client_id'] = $result['client_id'];
		$_SESSION['user']['user_id'] = $result['user_id'];
		
		pp($_SESSION);
		
	}
	
}