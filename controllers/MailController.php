<?php

namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use \Mailjet\Resources;

class MailController
{
	
	public function send_email($mj_from_email, $mj_from_name, $mj_to_email, $mj_to_name, $mj_subject, $mj_text, $mj_html){

		$mj = new \Mailjet\Client('8286ebdb5c39d228fc5f4235c8e086b8', 'bcfdeaa25473a150c6d285fdb5942f24',true,['version' => 'v3.1']);

		$body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $mj_from_email,
                        'Name' => $mj_from_name
                    ],
                    'To' => [
                        [
                            'Email' => $mj_to_email,
                            'Name' => $mj_to_name
                        ]
                    ],
                    /*'Bcc' => [
                        [
                            'Email' => "maxwellwachira67@gmail.com",
                            'Name' => "Maxwell Wachira"
                        ], 
                        [
                            'Email' => "maxwell@gearbox.co.ke",
                            'Name' => "Maxwell Wachira"
                        ]
                    ],*/
                    'Subject' => $mj_subject,
                    'TextPart' => $mj_text,
                    'HTMLPart' => $mj_html
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if($response->success()){
            return true;
        }else {
            return false;
        }
    }

    public function sendContactform(){

        $body = Application::$app->request->getBody();
       
        
        $sender_name = $body['name'];
        $sender_email = $body['email'];
        $mj_from_email = 'management@rentlordke.com';
    	$mj_from_name = 'Contact Form'; 
        $mj_to_email = 'management@rentlordke.com';
        $mj_to_name = 'Rentlord Management';
        $phonenumber = $body['phonenumber'];
        $mj_subject = $body['subject'] ;
        $message = $body['message'];
        $mj_text = '';
        $mj_html =  'You have recevied a New message from <br>Name: '.$sender_name.' <br> Email: '.$sender_email.'<br> Phone Number: '.$this->phonenumber.'<br>Message: '.$this->message.'';


        $this->send_email($mj_from_email, $mj_from_name, $mj_to_email, $mj_to_name, $mj_subject, $mj_text, $mj_html);

    }

}