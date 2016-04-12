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
		if(!ctype_alpha($name)){
			throw new Exception("needs to be in proper room format",2);
			return false;
		}
		return true;
	}

	
	
}


?>