<?php

namespace app\models;
use app\core\Model;
use app\config\Database;

/**
 * 
 */
class RegisterModel extends Model
{
	public $con;
	public $firstname;
	public $secondname;
	public $email;
	public $phonenumber;
	public $password;
	public $confirmPassword;
	public $type = 2;
	public $deleted = 0;
	public Database $database;

	public function __construct(){
		$this->database = new Database();
		$this->con = $this->database->getConnection_pdo();
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

	public function register($firstname, $secondname, $email, $phonenumber, $password){

		 	$query = "INSERT INTO users (firstname, secondname, email, phonenumber, password, type, created_at, deleted)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)";
  
		    // prepare query
		    $stmt = $this->con->prepare($query);
		    
		    $this->firstname = $firstname;
		    $this->secondname = $secondname;
		    $this->email = $email;
		    $this->phonenumber = $phonenumber;
		   	$this->password = password_hash($password, PASSWORD_DEFAULT);

		   	date_default_timezone_set("Africa/Nairobi");
		    $created_at = date('Y/m/d H:i:s');
		    
		    // bind values
		    $stmt->bindParam(1, $this->firstname);
		    $stmt->bindParam(2, $this->secondname);
		    $stmt->bindParam(3, $this->email);
		    $stmt->bindParam(4, $this->phonenumber);
		    $stmt->bindParam(5, $this->password);
		    $stmt->bindParam(6, $this->type);
		    $stmt->bindParam(7, $created_at);
		    $stmt->bindParam(8, $this->deleted);

		    // create the user
		    if($stmt->execute()){
		  
		        // set response code - 201 created
		        http_response_code(201);
		  		
		        // tell the user
		        
		        return true;
		    }
		  
		    // if unable to create the device, tell the user
		    else{
		  
		        // set response code - 503 service unavailable
		        http_response_code(503);
		        
		  
		        // tell the user
		        //echo json_encode(array("message" => "internal_error"));
		        return false;
		    }

	}

}