<?php
//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
//$allowed = true;


require_once("commonAuth.php");
require_once("util.php");



 const THROW_ONLY_ALPHA    = 1;
 const THROW_ROOM_INNCORRECT_FORMAT    = 2;
 const THROW_INNCORRECT_EMAIL_FORMAT    = 3;
class dbException extends Exception
{
	
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
		util::logMessage("../log/exceptionLog.txt",$message);
    }

    
    public function __toString() {
         return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
	

	
	public function alert() {
		$alert = "<div class='alert alert-dismissible'>";
		$alert .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
		$alert .= $this->message."</div>";

		return $alert;
}



}
?>