<?php
//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
$allowed = true;

require_once("dbException.php");
require_once("commonAuth.php");

if(!$allowed) {
	header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
}

 class util{
	 
	  function __construct()
    {

    }


 
	public static function checkName($name){
		if(!ctype_alpha(str_replace(array(' ', "'", '-', ".", "(", ")"), '', $name))){
			throw new dbException("only letter allowed in names",1);
			return false;
		}
		return true;
}


	public static function checkRoom($roomNumber){
		//replace space with '\-' after we've updated all the rooms.
		if(!preg_match("/^[a-zA-Z0-9]{3} [A-Za-z0-9]{1}\d{3}$/",$roomNumber)){
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
	
	public static function logMessage($path,$message){
		$dataStamp = date('Y-m-d g:i a');
		$file = fopen("log.txt", "w+");
		if( $file == false ) 
            die( "Error in opening file" );
		fwrite( $file, "$dataStamp: $message\n" );
		fclose( $file );
         
		
	}
	

	
	
}


?>