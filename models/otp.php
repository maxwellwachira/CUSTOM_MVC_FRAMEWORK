<?php

namespace app\models;
use app\core\Model;
use app\config\Database;
use app\controllers\MailController;


class otp 
{
	public $con;
	public Database $database;
	public MailController $mailController;

	public function __construct(){
		$this->database = new Database();
		$this->con = $this->database->getConnection_pdo();
		$this->mailController = new MailController();
		session_start();
		
	}

	public function generateOTP(){

		
		$query = "INSERT INTO otp (otp, status, user_id, created_at)
            VALUES
                (?, ?, ?, ?)";

        // prepare query
	    $stmt = $this->con->prepare($query);

	   	date_default_timezone_set("Africa/Nairobi");

	    $created_at = date('Y/m/d H:i:s');
	    $status = 0;
	    $user_id = $_SESSION['id'];
	    $otp = rand(100000, 999999);
	    
	    
	    // bind values
	   	$stmt->bindParam(1, $otp);
	    $stmt->bindParam(2, $status);
	    $stmt->bindParam(3, $user_id);
	    $stmt->bindParam(4, $created_at);

	    if(!$stmt->execute()){
	    	return false;
	    }

	    $mj_from_email = 'management@rentlordke.com';
	    $mj_from_name = 'Rent Lord';
	    $mj_to_email = $_SESSION['email'];
	    $mj_to_name = $_SESSION['name'];
	    $mj_subject = 'One Time Login Password';
	    $mj_text = '';
	    $mj_html = 'Hello '.$mj_to_name.'<br>Use the code below to Login to your ACCOUNT.<br><br><h1 style = "text-align: center;">'.$otp.'</h1>';

	    if($this->mailController->send_email($mj_from_email, $mj_from_name, $mj_to_email, $mj_to_name, $mj_subject, $mj_text, $mj_html)){

		    	return true;

		    }else{
		    	
		    	return false;
		    }


	}

	public function isValid($otp){

		$query = "SELECT * FROM 
	     			otp 
	     			WHERE otp = ?
	     			AND status = ?
	     			LIMIT 1";

	    // prepare query statement
   		$stmt = $this->con->prepare($query);

	    // bind email 
	    $status = 0;
	    

	    $stmt->bindParam(1, $otp);
	    $stmt->bindParam(2, $status);


	    // execute query
	    $stmt->execute();

		// query row count
		$num = $stmt->rowCount();
		// check if more than 0 record found
		if($num > 0){

			$user_id = $_SESSION['id'];
			$_SESSION["loggedin"] = true;

			$sql = "UPDATE otp SET status = :status WHERE user_id = :id AND otp = :otp";
			$statement = $this->con->prepare($sql);
			
			$new_status = 1;
			// bind parameters 
			$statement->bindParam(':status', $new_status);
		    $statement->bindParam(':id', $user_id);
		    $statement->bindParam(':otp', $otp);

			// execute query
		    if(!$statement->execute()){
		    	return false;
		    }else {
		    	return true;
		    }
		}else {

			$_SESSION["loggedin"] = false;
			return false;
		}

	}
}