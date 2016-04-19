<?php
require_once("dbException.php");
 class util{
	 
	  function __construct()
    {

    }


 
	public static function checkName($name){
		if(!ctype_alpha($name)){
			throw new dbException("only letter allowed in names",1);
			return false;
		}
		return true;
}


	public static function checkRoom($roomNumber){
	
		if(!preg_match("/^[a-zA-Z0-9]{3}\-\d{4}$/",$roomNumber)){
			throw new dbException("needs to be in proper room format",2);
			return false;
		}
		return true;
	}
	
	
	public static function checkEmail($email){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			throw new dbException("email needs to be in email@domain.TLD format",3);

			return false;
		}
		return true;
	}
	
	
	

	
	
}


?>