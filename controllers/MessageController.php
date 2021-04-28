<?php 
namespace app\controllers;
use app\api\AfricasTalkingGatewayException;
use app\api\AfricasTalkingGateway;

class MessageController
{
	
	public $username;
	public $apikey;
	public  AfricasTalkingGateway $gateway;
	public function __construct(){

		$this->username = '';
		$this->apikey = '';
		$this->gateway = new AfricasTalkingGateway($this->username, $this->apikey);
	}

	public function sanitizer($number){
		// remove all white space
		$number = preg_replace('/\s+/', '', $number);

		// remove all the dashes
		$number = str_replace("-", "", $number);

		// append 254
		$number = "+2547". substr($number, 1);

    	return $number;	
	}

	public function readBalance(){
		try
		{ 
		  // Fetch the data from our USER resource and read the balance
		  // The result will have the format=> KES XXX
		  return $this->gateway->getUserData();
		  
		}
		catch ( AfricasTalkingGatewayException $e )
		{
		  return "Encountered an error while fetching user data: ".$e->getMessage()."\n";
		}

	}
	
	public function sendSMS($recipients, $message, $from) {
			
    	try 
    	{ 
    	  // Thats it, hit send and we'll take care of the rest. 
    	  $results = $this->gateway->sendMessage($recipients, $message, $from);
    	            
    	  foreach($results as $result_log) {
    	    $feedback_msg = " Number: "  .$result_log->number;
    	    $feedback_msg .= "Status: " .$result_log->status;
    	    $feedback_msg .= " MessageId: " .$result_log->messageId;
    	    $feedback_msg .= " Cost: "   .$result_log->cost."\n";
    	  }
    	}
    	catch ( AfricasTalkingGatewayException $e )
    	{
    	  $feedback_msg = "Encountered an error while sending: ".$e->getMessage();
    	}
		return $feedback_msg;
	}
}
?>
