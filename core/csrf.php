<?php
namespace app\core;

class csrf
{

	function set_csrf(){
	  if(session_status() == 1){ 
	  	session_start(); 
	  }
	  if(isset($_SESSION['csrf'])){
	  	return '<input type="hidden" id = "csrf" name="csrf" value="'.$_SESSION['csrf'].'">';
	  } else {
	  	$csrf_token = bin2hex(random_bytes(25));
	  	$_SESSION['csrf'] = $csrf_token;
	  	return '<input type="hidden" id = "csrf" name="csrf" value="'.$csrf_token.'">';
	  }

	  
	}

	function is_csrf_valid($csrf_token){
	  if(session_status() == 1){ 
	  	session_start(); 
	  }
	  if( ! isset($_SESSION['csrf']) || ! isset($csrf_token)){ 
	  	return false; 
	  }
	  if( $_SESSION['csrf'] != $csrf_token){ 
	  	return false; 
	  }
	
	  return true;
	}
}