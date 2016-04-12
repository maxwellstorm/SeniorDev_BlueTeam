<?php

 class util{
	 
	  function __construct()
    {

    }
 const THROW_ONLY_ALPHA    = 1;
	
	public static function checkName($name){
		if(!ctype_alpha($name)){
			throw new Exception("only letter allowed in names",1);
			return false;
		}
		return true;
}


	public static function checkRoom($roomNumber){
		//this is in progress waiting for answer from andy of room format
		if(!ctype_alpha($name)){
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
	
	
	public static function checkNull($paramName,$input){
		try{
			if(strlen($input) == 0){
				throw new Exception("$paramName cannot be null",4);
				return false;
			}
			return true;
		}
		catch(Exception $e){
			throw new Exception("$paramName cannot be null",4);
			return false;
		}
	}
	

	
	
}


?>