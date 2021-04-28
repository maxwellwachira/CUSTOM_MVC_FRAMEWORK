<?php

namespace app\models;
use app\core\Model;
use app\config\Database;
use app\controllers\MailController;

/**
 * 
 */
class PasswordRecovery extends Model
{
	public $con;
	public $email;
	public $phonenumber;
	public $password;
	public $confirmPassword;
	public $token;
	public Database $database;
	public MailController $mailController;

	public function __construct(){
		$this->database = new Database();
		$this->con = $this->database->getConnection_pdo();
		$this->mailController = new MailController();
		
	}

	public function userExists($email){

		$this->email = $email;
		$query = "SELECT email FROM 
	     			users 
	     			WHERE email = ?
	     			LIMIT 1";

	    
	    // prepare query statement
   		$stmt = $this->con->prepare($query);

	    // bind email 
	    $stmt->bindParam(1, $this->email);

	    // execute query
	    $stmt->execute();

		// query row count
		$num = $stmt->rowCount();
		// check if more than 0 record found
		if($num > 0){

			return true;
			
		}else if($num === 0){

			return false;
		}
	}

	public function insertRecoveryData($serial, $user_id){

		date_default_timezone_set("Africa/Nairobi");

		$query = "INSERT INTO password_reset (token, status, user_id, created_at)
            VALUES
                (?, ?, ?, ?)";
  
	    // prepare query
	    $stmt = $this->con->prepare($query);


	    $status = 0;
	    $timestamp = date('Y/m/d H:i:s');
	    
	    // bind values
	    $stmt->bindParam(1, $serial);
	    $stmt->bindParam(2, $status);
	    $stmt->bindParam(3, $user_id);
	    $stmt->bindParam(4, $timestamp);

	    if($stmt->execute()){
	        
	        return true;

	    }else{

	        return false;
	    }

	}
	public function sendRecoveryEmail(){

		$query = "SELECT * FROM 
	     			users 
	     			WHERE email = ?
	     			LIMIT 1";
	    // prepare query statement
   		$stmt = $this->con->prepare($query);

	    // bind email 
	    $stmt->bindParam(1, $this->email);

	    // execute query
	    $stmt->execute();

	    $row = $stmt->fetch(\PDO::FETCH_ASSOC);

	    $id = $row['id'];

	    $mj_from_email = 'management@rentlordke.com';
	    $mj_from_name = 'Rent Lord';
	    $mj_to_email = $row['email'];
	    $mj_to_name = $row['firstname']. ' '.$row['secondname'];
	    $mj_subject = 'Password Recovery';
	    $mj_text = '';
	   
	    // generate serial
	    $serial = md5($id. ' '.time());

	    $mj_html = 'Hello '.$mj_to_name.'<br>Use the link below to reset your password. The link shall expire in 3 hours<br>Password reset link : http://localhost:443/password-reset?rst='.$serial;


	    if($this->insertRecoveryData($serial, (int)$id)){
	    	if($this->mailController->send_email($mj_from_email, $mj_from_name, $mj_to_email, $mj_to_name, $mj_subject, $mj_text, $mj_html)){

		    	return true;

		    }else{
		    	
		    	return false;
		    }

	    }else{

	    	return false;
	    }

	}	

	public function tokenExists($token){

		$query = "SELECT token FROM 
	     			password_reset 
	     			WHERE token = ?
	     			LIMIT 1";

	    
	    // prepare query statement
   		$stmt = $this->con->prepare($query);

   		$this->token = $token;


	    // bind email 
	    $stmt->bindParam(1, $this->token);

	    // execute query
	    $stmt->execute();

		// query row count
		$num = $stmt->rowCount();
		// check if more than 0 record found
		if($num > 0){

			return true;
			
		}else if($num === 0){

			return false;
		}
	}

	public function updatePassword($token, $password){

		$query = "SELECT * FROM 
	     			password_reset 
	     			WHERE token = ?
	     			LIMIT 1";

	    
	    // prepare query statement
   		$stmt = $this->con->prepare($query);

   		$this->token = $token;

	    // bind email 
	    $stmt->bindParam(1, $this->token);

	    // execute query
	    $stmt->execute();
	  
		$row = $stmt->fetch(\PDO::FETCH_ASSOC);

		$user_id = $row['user_id'];
		
	    
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);

		$sql = "UPDATE users SET password = :password WHERE id = :id";
		$statement = $this->con->prepare($sql);
		
		// bind parameters 
		$statement->bindParam(':password', $hashed_password);
	    $statement->bindParam(':id', $user_id);
	 
	    // execute query
	    if($statement->execute()){
	    	return true;

	    }else{

	    	return false;
	    }

	}

}
?>