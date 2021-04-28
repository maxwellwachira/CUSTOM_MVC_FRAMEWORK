<?php

namespace app\core;
/**
 * 
 */
class Request {

	public function getPath(){
		//returns the value of $_SERVER['REQUEST_URI'] if is present and not null otherwise return /
		$path = $_SERVER['REQUEST_URI'] ?? '/'; 

		//determine the position of the question mark in the url
		$position = strpos($path, '?');
		//Check if there is no question mark in the url
		if ($position === false){
			return $path;
		}

		return substr($path, 0, $position);

	}

	public function getMethod(){

		return strtolower($_SERVER['REQUEST_METHOD']);
		
	}
	public function getBody(){

		$body = [];
		if($this->getMethod() === 'get'){
			foreach ($_GET as $key => $value) {
				$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		if($this->getMethod() === 'post'){
			foreach ($_POST as $key => $value) {
				$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}
		return $body;

	}

	public function redirect(string $url){

		header('location: '.$url);

	}

}