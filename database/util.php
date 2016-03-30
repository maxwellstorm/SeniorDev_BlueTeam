<?php

 class util{
	 
	  function __construct()
    {

    }
 const THROW_ONLY_ALPHA    = 1;
	
	public static function checkName($name){
		if(!ctype_alpha($name)){
			echo "jere0";
			throw new Exception("only letter allowed in names",1);
			return false;
		}
		return true;
}

	
	
}


?>