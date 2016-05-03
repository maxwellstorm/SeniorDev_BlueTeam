<?php
//A class that defines a custom exception to return specific error messages



 const THROW_ONLY_ALPHA    = 1;
 const THROW_ROOM_INNCORRECT_FORMAT    = 2;
 const THROW_INNCORRECT_EMAIL_FORMAT    = 3;
/**
 * A class to custom define a database exception
 * The class extends the base class exceptions
 */
class dbException extends Exception
{
	
    /**
     * A constructor that redefines the exception so that an error message isn't optional
     * @param $message The error message to be used
     * @param $code The error code
     * @param $previous The previous exception (if any)
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
		logExcepMessage("../log/exceptionLog.txt",$message);
    }

    /**
     * A tostring function to return an error message
     * @return errorMessage The error message
     */
    public function __toString() {
         return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
	

	/**
	 * A message to return an HTML-styled alert for user feedback.  This function should've been totally replaced by alert in util.php, but is being left to ensure nothing breaks
	 * @return $alert The HTML-styled alert
	 */
	public function alert() {
		/*$alert = "<div class='alert alert-dismissible'>";
		$alert .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
		$alert .= $this->message."</div>";*/
		return "alert('danger','A database error has occurred');";
	}
	
	/**
	 * A method to log messaegs to a designated file
	 * @param $path The file to log messages to
	 * @param $message The message to be logged
	 */
	function logExcepMessage($path,$message){
		try{
	
			$dataStamp = date('Y-m-d g:i a');
			$file = fopen($path, "a+");
			if( $file == false ) 
				die( "Error in opening file" );
			fwrite( $file, "$dataStamp: $message\n" );
			fclose( $file );
		}
		catch(Exception $e){
		}
	}
}
?>