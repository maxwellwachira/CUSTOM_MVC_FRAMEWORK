<?php

namespace app\models;
use app\core\Model;
use app\config\Database;
use app\core\Application;
use app\core\Controller;
use app\core\Request;

/**
 * 
 */
class LoginModel extends Model
{
	public $con;
	public $firstname;
	public $secondname;
	public $email;
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

	public function login($email, $password){

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

	    $input_password = $password;
	    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
	    $hashed_password = $row['password'];
	    if(password_verify($input_password, $hashed_password)){
	    	// Password is correct, so start a new session
            session_start();
            
            // Store data in session variables
            $_SESSION["id"] = $row['id'];
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $row['firstname'].' '.$row['secondname'];                            
            
            // Redirect user to welcome page
           
	    	return true;
	    }else {
	    	return false;
	    }
	}

}
?>