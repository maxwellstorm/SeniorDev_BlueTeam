<?php
require_once("data.php");
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

//Check that the following work:
function getAccessLevel($username) {
	$database = new data;

	$acct = $database->getData("SELECT accessLevel FROM Admin WHERE username=:username;", array(
			":username"=>$username
		));

	return $acct[0]['accessLevel'];
}

function getAdminDepartment($username) {
	$database = new data;

	$acct = $database->getData("SELECT departmentId FROM Admin WHERE username=:username;", array(
			":username"=>$username
		));

	return $acct[0]['departmentId'];
}

function isAllowed($username) {
	$database = new data;

	$acct = $database->getData("SELECT username FROM Admin WHERE username=:username;", array(
			":username"=>$username
		));

	if(strlen($acct[0]['username']) > 0) {
		return true;
	} else {
		return false;
	}
}

function displayNav($accessLevel, $givenName) {
	if(isset($givenName)) {
		$navs = "<div style='float: right'>Hello, " .  $_SERVER['givenName'] . "</div>";
	} else {
		$navs = "";
	}

	$navs .= "<ul class='nav nav-tabs'>";
	$navs .= "	<li role='presentation' id='empNav'><a href='addEmployee.php'>Employees</a></li>";
	$navs .= "	<li role='presentation' id='roomNav'><a href='addRoom.php'>Rooms</a></li>";
	
	if($accessLevel == 3) {
		$navs .= "	<li role='presentation' id='deptNav'><a href='addDepartment.php'>Departments</a></li>";
	}

	if($accessLevel > 1) {
		$navs .= "	<li role='presentation' id='adminNav'><a href='addAdmin.php'>Admins</a></li>";
	}
	$navs .= "</ul>";

	echo($navs);
}

function isDuplicateName($fName, $lName, $table) {
	$database = new data;

	if(strcmp($table, "Admin") == 0) {
		$match = $database->getData("SELECT adminId FROM Admin WHERE fName=:fName AND lName=:lName;", array(
		":fName"=>$fName,
		":lName"=>$lName
	));
	} else if(strcmp($table, "Employees") == 0) {
		$match = $database->getData("SELECT facultyId FROM Employees WHERE fName=:fName AND lName=:lName;", array(
		":fName"=>$fName,
		":lName"=>$lName
	));
	} else {
		//throw error
	}

	if(count($match) > 0) {
		return true;
	} else {
		return false;
	}
}

/**
 * A method to return an message to provide feedback to the user in the form of an alert-dismissable box
 * @param $type The type of message (danger, warning, info, success, etc.) 
 * @param $text The text to be displayed
 * @return $alert a small section of formatted HTML containing the message
 */
function alert($type, $text) {
	$alert = "<div class='alert alert-dismissible alert-$type'>";
	$alert .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
	$alert .= "$text</div>";

	return $alert;
}

function getAllDepartments() {
	$database = new data;

	$depts = $database->getData("SELECT departmentName FROM department", array());

	foreach($depts as $arr) {
		echo "<option>" . $arr['departmentName'] . "</option>";
	}
}

function getDepartmentId($deptName) {
	$database = new data;

	$depts = $database->getData("SELECT departmentId, departmentName FROM department", array());

	foreach($depts as $arr) {
		if(strcmp($deptName, $arr['departmentName']) == 0) {
			return $arr['departmentId'];
		}
	}
}

/**
 * A function to get all rooms and return them as a set of <option>'s
 * @return HTML_Content A set of <option>'s each containing information about a room
 */
function getAllRooms() {
	$database = new data;

	$rooms = $database->getData("SELECT roomNumber FROM room;", array());

	foreach($rooms as $arr) {
		echo "<option>" . $arr['roomNumber'] . "</option>";
	}
}
?>