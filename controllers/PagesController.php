<?php

namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
//use app\controllers\MailController;

class PagesController extends Controller
{

	public static function home(){
		$params = ['name' => 'Bazenga'];
		return Application::$app->router->renderView('home', $params);
	}

	public static function about(){
		$params = [];
		return Application::$app->router->renderView('about', $params);
	}

	public static function contact(){
		$params = [];
		return Application::$app->router->renderView('contact', $params);
	}

	public static function handleContact(Request $request){
		$params = [];
		$body = $request->getBody();	
	}
}
?>