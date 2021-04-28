<?php

namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;
use app\models\LoginModel;
use app\models\otp;
use app\models\PasswordRecovery;
use app\core\csrf;
use app\controllers\MessageController;

/**
 * 
 */

class AuthController 
{
	public array $errors = array();
	public csrf $csrf;
	public otp $otp;
	public MessageController $sendtext;

	public function __construct(){
		$this->csrf = new csrf();
		$this->otp = new otp();
		$this->sendtext = new MessageController();
	}
	
	public function loginGet(){
		$method = Application::$app->request->getMethod();
		if($method === 'get'){
			$csrf_token =  $this->csrf->set_csrf();
			return Application::$app->router->renderView('login', ['message' => $csrf_token]);
		}
	} 

	public function loginPost(){
		
		$loginModel = new LoginModel();
		$method = Application::$app->request->getMethod();
		if($method === 'post'){

			$body = Application::$app->request->getBody();
			if(!$this->csrf->is_csrf_valid($body['csrf_token'])){
				$params = ['message' => 'Security Bridge Issue'];
				echo json_encode($params);	
				exit;
			} else {

				if(!$loginModel->userExists($body['email'])){
					$params = ['message' => 'No account found with that email address'];
					echo json_encode($params);	
					exit;
				}else{
					if($loginModel->login($body['email'], $body['password'])){
						if(!$this->otp->generateOTP()){
							$params = ['message' => 'System Error'];
							echo json_encode($params);
							exit;
						}else {
							$params = ['message' => 'success'];
							echo json_encode($params);
							//$this->sendtext->sendSMS('+254703519593', 'Hello Bazu', 'GEARBOX_LTD');
							//Application::$app->request->redirect('/');
							exit;
						}
					}else{
						$params = ['message' => 'Wrong Password'];
						echo json_encode($params);
						exit;
					}
				}
			}
		}
	}

	public function registerGet(){
		$method = Application::$app->request->getMethod();
		if($method === 'get'){
			$csrf_token =  $this->csrf->set_csrf();
			return Application::$app->router->renderView('register', ['message' => $csrf_token]);
		}
	}

	public function registerPost(){

		$registerModel = new RegisterModel();
		$method = Application::$app->request->getMethod();
		if($method === 'post'){

			$body = Application::$app->request->getBody();
			if(!$this->csrf->is_csrf_valid($body['csrf_token'])){
				$params = ['message' => 'Security Bridge Issue'];
				echo json_encode($params);	
				exit;
			} else {

				if(!filter_var($body['email'], FILTER_VALIDATE_EMAIL)){
					$params = ['message' => 'Invalid Email'];
					echo json_encode($params);	
					exit;
				} else {
					if($registerModel->userExists($body['email'])){
						$params = ['message' => 'Account Exists'];
						echo json_encode($params);
						exit;
					} else{
						if(!$registerModel->register($body['firstname'], $body['secondname'], $body['email'], $body['phonenumber'], $body['password'])){
							$params = ['message' => 'Server Error. Contact Admin'];		
						}else{
							$params = ['message' => 'success'];
						}
						echo json_encode($params);	
						exit;	
					}
				}
			}
		}	
	}

	public function forgotPasswordGet(){
		$method = Application::$app->request->getMethod();
		if($method === 'get'){
			return Application::$app->router->renderView('forgot-password');
		}	
	}

	public function forgotPasswordPost(){
		$passwordRecovery= new PasswordRecovery();
		$method = Application::$app->request->getMethod();

		if($method === 'post'){

			$body = Application::$app->request->getBody();

			if(!$passwordRecovery->userExists($body['email'])){
				$params = ['message' => 'Account Does not Exists'];
				echo json_encode($params);	
				exit;
			} else {
				if($passwordRecovery->sendRecoveryEmail()){
					$params = ['message' => 'success'];
					echo json_encode($params);	
					exit;
				}else {
					$params = ['message' => 'Send Error. Try Again'];
					echo json_encode($params);		
				}
			}
		}	
	}


	public function passwordResetGet(){

		$passwordRecovery= new PasswordRecovery();
		$method = Application::$app->request->getMethod();

		if($method === 'get'){

			$body = Application::$app->request->getBody();
			if($passwordRecovery->tokenExists($body['rst'])){
				return Application::$app->router->renderView('password-reset', ['message' => '<input type="hidden" id = "rst_token" name="rst_token" value="'.$body['rst'].'">']);
			}else{
				return Application::$app->router->renderView('forgot-password');
			}

		}

	}

	public function passwordResetPost(){

		$passwordRecovery= new PasswordRecovery();
		$method = Application::$app->request->getMethod();

		if($method === 'post'){
			$body = Application::$app->request->getBody();
			if($passwordRecovery->updatePassword($body['rst_token'], $body['password'])){
				$params = ['message' => 'success'];
				echo json_encode($params);	
				exit;
			}else{
				$params = ['message' => 'Error. Contact Admin or try again'];
				echo json_encode($params);	
				exit;	
			}

		}

	}

	public function logout(){
		session_start();
		session_destroy();

		Application::$app->request->redirect('/login');
		exit();
	}


	public function otpGet(){

		$method = Application::$app->request->getMethod();

		if($method === 'get'){
			return Application::$app->router->renderView('otp-verification');
		}
		//return Application::$app->router->renderView('otp-verification');
	}

	public function otpPost(){
		$method = Application::$app->request->getMethod();
		if($method === 'post'){
			$body = Application::$app->request->getBody();
			if($this->otp->isValid($body['otp'])){
				$params = ['message' => 'success'];
				echo json_encode($params);	
				exit;
			}else{
				$params = ['message' => 'Invalid Code'];
				echo json_encode($params);
				exit();
			}
		}
	}


}
?>