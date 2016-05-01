<?php
//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
//$allowed = true;

/*require_once("dbException.php");
require_once("commonAuth.php");*/


function checkName($name){
	if(!ctype_alpha(str_replace(array(' ', "'", '-', ".", "(", ")"), '', $name))){
		throw new dbException("only letter allowed in names",1);
		return false;
	}
	return true;
}


function checkRoom($roomNumber){
	if(!preg_match("/^[a-zA-Z0-9]{3} [A-Za-z0-9]{1}\d{3}$/",$roomNumber)){
		throw new dbException("needs to be in proper room format",2);
		return false;
	}
	return true;
}


function checkEmail($email){
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		throw new dbException("email needs to be in email@domain.TLD format",3);

		return false;
	}
	return true;
}

function logMessage($path,$message){

	try{
		echo "  attemoting to log excpetion";
		$dataStamp = date('Y-m-d g:i a');
		$file = fopen($path, "a+");
		if( $file == false ) 
			die( "Error in opening file" );
		fwrite( $file, "$dataStamp: $message\n" );
		fclose( $file );
	}
	catch(Exception $e){
		echo "ERROR LOGGING EXCPTION IN UTIL";
	}
	
}

function filterString($string) {
	$newString = trim($string);
	$newString = stripslashes($newString);
	$newString = strip_tags($newString);
	$newString = html_entity_decode($newString);
	$newString = htmlspecialchars_decode($newString);

	return $newString;
}
?>