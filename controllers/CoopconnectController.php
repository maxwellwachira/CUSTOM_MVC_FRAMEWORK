<?php

namespace app\controllers;
use app\models\CoopconnectModel;
use app\core\csrf;
use app\core\Application;
use app\core\Controller;
use app\core\Request;

class CoopconnectController{

    private $CK;
    private $SK;
    private $authorization;
    public $header;
    public $content;
    public $reference;
    public csrf $csrf;

    public function __construct(){
        $this->CK = 'm5s7WVpifcsEE__ZHloZF8oIGcga';
        $this->SK = '9dgvSGsDXBuxgu7idTzjPYsuMR8a';
        $this->content = "grant_type=client_credentials";
        $this->csrf = new csrf();
    }

    public function getAccessToken(){
        $this->authorization = base64_encode("$this->CK:$this->SK");
        $this->header = array("Authorization: Basic {$this->authorization}");

        $curl = curl_init();
        curl_setopt_array($curl, array(

            CURLOPT_URL => "https://developer.co-opbank.co.ke:8243/token",
            CURLOPT_HTTPHEADER => $this->header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->content
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {

            echo "Failed";
            echo curl_error($curl);
            curl_close($curl);
            echo "Failed";
            exit(0);
        }

        $token= json_decode($response)->access_token;
        return $token;
    }

    public function curlRequest($requestPayload, $url){

        $headers = array('Content-Type: application/json',"Authorization: Bearer {$this->getAccessToken()}");

        $curl = curl_init();
      
        curl_setopt($curl, CURLOPT_URL, $url);        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestPayload);        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");        
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);        
        $response = curl_exec($curl);        
        curl_close($curl);
    
        return $response;
    
    }


    public function accountValidation(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9CR_1K_121106kl89812",

        "AccountNumber" =>"3600187000"
        
      ];
      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/Validation/Account/1.0.0'; 

      $response =  $this->curlRequest($requestPayload, $url);

      $valid = json_decode($response)->MessageDescription;

      if($valid === 'VALID ACCOUNT NUMBER'){

        return 'true';

      }else{

        return 'false';
      }

    }


    public function accountBalance(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9CR_1K_121106kl89812",

        "AccountNumber" =>"36001873023"
        
      ];
      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/AccountBalance/1.0.0/';

      return $this->curlRequest($requestPayload, $url);

    }

    public function miniStatement(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9CR_1K_121106kl89812",

        "AccountNumber" =>"36001873000"
        
      ];
      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/MiniStatement/Account/1.0.0'; 

      return $this->curlRequest($requestPayload, $url);

    }


    public function fullStatement(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9CR_1K_121106kl89812",

        "AccountNumber" =>"36001873000",

        "StartDate" => "2021-01-01",

        "EndDate" => "2021-04-22"
        
      ];
      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/FullStatement/Account/1.0.0'; 

      return $this->curlRequest($requestPayload, $url);

    }

    public function transactionStatus($reference){

      $requestPayload = [

        "MessageReference" => $reference
        
      ];
      $requestPayload = json_encode($requestPayload);
       
      $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/TransactionStatus/2.0.0/';

      return $this->curlRequest($requestPayload, $url);

    }

    public function sendToMpesa(){

      $this->reference = time(). "_".substr(md5(uniqid(rand(1,6))), 0, 8);

      $requestPayload = [

        "MessageReference" => $this->reference,
      
        "CallBackUrl" => "https://rentlordke.com/coop/mpesa/mpesa.php",
      
        "Source" => [
      
          "AccountNumber" => "36001873000",
      
          "Amount" => 1,
      
          "TransactionCurrency" => "KES",
      
          "Narration" => "Payment to supplier"
      
        ],
      
        "Destinations" => [
      
            "ReferenceNumber" => $this->reference."_1",
      
            "MobileNumber" => "254703519593",
      
            "Amount" => 1,
      
            "Narration" => "payment to supplier"
      
        ]
      
      ];

      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2M/Mpesa/1.0.0/';    

      $this->curlRequest($requestPayload, $url);

      return $this->transactionStatus($this->reference);

    }

    public function currencyConversion(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9CR_1K_121106kl89812",

        "FromCurrencyCode" =>"USD",

        "ToCurrencyCode" => "KES"
        
      ];
      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/ExchangeRate/1.0.0/';  

      return $this->curlRequest($requestPayload, $url);

    }

    public function internalFundsTransfer(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9CRK_121106kl89812",
      
        "CallBackUrl" => "https://rentlordke.com/coop/mpesa/mpesa.php",
      
        "Source" => [
      
          "AccountNumber" => "36001873000",
      
          "Amount" => 1,
      
          "TransactionCurrency" => "KES",
      
          "Narration" => "Payment to supplier"
      
        ],
      
        "Destinations" => [
      
            "ReferenceNumber" => "p1mDteP_9CRK_121106kl89812_1",
      
            "AccountNumber" => "36001873027",

            "BankCode" => "011",

            "BranchCode" => "00011001",
      
            "Amount" => 1,

            "TransactionCurrency" => "KES",
      
            "Narration" => "payment to supplier"
      
        ]
      
      ];

      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/Internal/A2A/2.0.0/';     

      return $this->curlRequest($requestPayload, $url);

    }


    //aka pesalink
    public function externalFundsTransfer(){

      $requestPayload = [

        "MessageReference" => time(),
      
        "CallBackUrl" => "https://rentlordke.com/coop/mpesa/mpesa.php",
      
        "Source" => [
      
          "AccountNumber" => "36001873000",
      
          "Amount" => 1,
      
          "TransactionCurrency" => "KES",
      
          "Narration" => "Payment to supplier"
      
        ],
      
        "Destinations" => [
      
            "ReferenceNumber" => time()."_1",
      
            "AccountNumber" => "36001873027",

            "BankCode" => "11",

            "BranchCode" => "00011001",
      
            "Amount" => 1,

            "TransactionCurrency" => "KES",
      
            "Narration" => "payment to supplier"
      
        ]
      
      ];

      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2A/PesaLink/1.0.0/';     

      return $this->curlRequest($requestPayload, $url);

    }
    public function pesalinkToPhone(){

      $requestPayload = [

        "MessageReference" => "p1mDteP_9xR_1K_121106kl89812",
      
        "CallBackUrl" => "https://rentlordke.com/coop/mpesa/mpesa.php",
      
        "Source" => [
      
          "AccountNumber" => "36001873000",
      
          "Amount" => 1,
      
          "TransactionCurrency" => "KES",
      
          "Narration" => "Payment to supplier"
      
        ],
      
        "Destinations" => [
      
            "ReferenceNumber" => "p1mDteP_9xR_1K_121106kl89812_1",
      
            "PhoneNumber" => "254703519593",
      
            "Amount" => 1,

            "TransactionCurrency" => "KES",
      
            "Narration" => "payment to supplier"
      
        ]
      
      ];

      $requestPayload = json_encode($requestPayload);

      $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2M/PesaLink/1.0.0';  

      return $this->curlRequest($requestPayload, $url);

    }

    public function coopGet(){
      $method = Application::$app->request->getMethod();
      if($method === 'get'){
        $csrf_token =  $this->csrf->set_csrf();
        return Application::$app->router->renderView('coop-connect', ['message' => $csrf_token]);
      }

    }

    public function coopPost(){

      $method = Application::$app->request->getMethod();
      if($method === 'post'){

        $body = Application::$app->request->getBody();
        //if(!$this->csrf->is_csrf_valid($body['csrf'])){
         // $params = ['message' => 'Security Bridge Issue'];
         // echo json_encode($params);  
          //exit;
        //} else {
            $service = $body['service'];
            switch ($service) {
              case 'account_balance':
                echo $this->accountBalance();
                break;
              case 'mini_statement':
                echo $this->miniStatement();
                break;
              case 'full_statement':
                echo $this->fullStatement();
                break;
              case 'refund':
                echo $this->sendToMpesa();
                break;
              case 'IFTA2A':
                echo $this->internalFundsTransfer();
                break;
              case 'EFTA2A':
                echo $this->externalFundsTransfer();
                break;
               case 'exchange_rate':
                echo $this->currencyConversion();
                break;
              case 'account_valid':
                echo $this->accountValidation();
                break;
              case 'status':
                echo $this->transactionStatus();
                break;
              case 'account_2_phone':
                echo $this->pesalinkToPhone();
                break;
              
              default:
                $params = ['message' => 'Invalid Choice'];
                echo json_encode($params);
                break;
            }
        //}
      } 
    }




}



//$coopConnect = new CoopConnect();

//echo $coopConnect->transactionStatus();
//echo $coopConnect->sendToMpesa();
/*$balance = $coopConnect->accountBalance();
$balance = json_decode($balance)->AverageBalance;
echo $balance;*/
//echo $coopConnect->miniStatement();
//echo $coopConnect->fullStatement();
//echo $coopConnect->accountValidation();
//echo $coopConnect->currencyConversion();
//echo $coopConnect->internalFundsTransfer();
//echo $coopConnect->externalFundsTransfer();
//echo $coopConnect->pesalinkToPhone();




  
  
 
?>