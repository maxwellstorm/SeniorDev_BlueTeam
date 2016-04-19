<?php

 class util{
	 
	  function __construct()
    {

    }
 const THROW_ONLY_ALPHA    = 1;
 const THROW_ROOM_INNCORRECT_FORMAT    = 2;
 const THROW_INNCORRECT_EMAIL_FORMAT    = 3;

 
	public static function checkName($name){
		if(!ctype_alpha($name)){
			throw new Exception("only letter allowed in names",1);
			return false;
		}
		return true;
}


	public static function checkRoom($roomNumber){
	
		if(!preg_match("/^[a-zA-Z0-9]{3}\-\d{4}$/",$roomNumber)){
			throw new Exception("needs to be in proper room format",2);
			return false;
		}
		return true;
	}
	
	
	public static function checkEmail($email){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			throw new Exception("email needs to be in email@domain.TLD format",3);

			return false;
		}
		return true;
	}
	
	
	

	
	
}


?>