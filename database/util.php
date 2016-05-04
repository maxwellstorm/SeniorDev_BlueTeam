<?php
//A library-esque common function class that handles functionality used by multiple pages

require_once("data.php");
require_once("dbException.php");


/**
 * A function to verify that only alphabetical characters (and a few specified exceptions) are allowed in names
 * @param $name The name to be checked for letters
 * @return true/false Whether or not the name is valid
 */
function checkName($name){
	if(!ctype_alpha(str_replace(array(' ', "'", '-', ".", "(", ")"), '', $name))){
		throw new dbException("only letter allowed in names",1);
		return false;
	}
	return true;
}


/**
 * A function to verify that a room is submitted in the correct format (e.g. GOL 2300)
 * @param $roomNumber The room number to be checked
 * @return true/false Whether or not the room is valid
 */
function checkRoom($roomNumber){
	if(!preg_match("/^[a-zA-Z0-9]{3} [A-Za-z0-9]{1}\d{3}$/",$roomNumber)){
		throw new dbException("needs to be in proper room format",2);
		return false;
	}
	return true;
}


/**
 * A function to verify that an email is in the correct format
 * @param $email The email address to be checked
 * @return true/false Whether or not the email is valid
 */
function checkEmail($email){
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		throw new dbException("email needs to be in email@domain.TLD format",3);

		return false;
	}
	return true;
}

/**
 * A method to log messaegs to a designated file
 * @param $path The file to log messages to
 * @param $message The message to be logged
 */
function logMessage($path,$message){

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

/**
 * A method to strip & remove all HTML and special entities from a given string
 * Used on all text/string inputs for data sanitization
 * @param $string The raw string to be cleaned
 * @return $newString The cleaned/stripped string
 */
function filterString($string) {
	$newString = trim($string);
	$newString = stripslashes($newString);
	$newString = strip_tags($newString);
	$newString = html_entity_decode($newString);
	$newString = htmlspecialchars_decode($newString);

	return $newString;
}

/**
 * An authentication message to get a given user's access level, to allow/restrict their access to certain functionality
 * The current access levels are as follows:
 * Student Worker (lowest permissions) = 1
 * Office Staff = 2
 * System Administrator (full access) = 3
 * @param $username The username of an administrative user
 * @return $accessLevel The accessLevel (an integer) representing the user's access level
 */
function getAccessLevel($username) {
	$database = new data;

	$acct = $database->getData("SELECT accessLevel FROM Admin WHERE username=:username;", array(
			":username"=>$username
		));

	return $acct[0]['accessLevel'];
}

/**
 * An authentication function to get the department of an administrative user, given their username
 * This is used to restrict access to certain functionality for non-system administrators
 * @param $username The username of the inputted Admin user
 * @return $adminDeptartment The department ID (an integer)
 */
function getAdminDepartment($username) {
	$database = new data;

	$acct = $database->getData("SELECT departmentId FROM Admin WHERE username=:username;", array(
			":username"=>$username
		));

	return $acct[0]['departmentId'];
}

/**
 * An authentication function to check whether or not a user exists in the system, and therefore has any access to the administrative portal
 * @param $username The username of an admin user
 * @return true/false Whether or not the username exists in the system
 */
function isAllowed($username) {
	$database = new data;

	$acct = $database->getData("SELECT username FROM Admin WHERE username=:username;", array(
			":username"=>$username
		));

	if(strlen($acct[0]['username']) > 0) { //If a username is returned, then the user exists in the system
		return true;
	} else {
		return false;
	}
}

/**
 * A function to display the navigation tab, showing/hiding certain tabs based on the user's access Level
 * @param $accessLevel The admin user's access level, used to show/hide tabs
 * @param $giveName The admin user's given name (from Shibboleth), used to display a custom greeting
 * @return $navs A styled <ul> populated with <li> elements that serves as the system navigation
 */
function displayNav($accessLevel, $givenName) {
	if(isset($givenName)) { //Display the user's given name if one exists for them
		$navs = "<div style='float: right'>Hello, " .  $_SERVER['givenName'] . "</div>";
	} else {
		$navs = "";
	}

	$navs .= "<ul class='nav nav-tabs'>";
	$navs .= "	<li role='presentation' id='empNav'><a href='addEmployee.php'>Employees</a></li>";
	$navs .= "	<li role='presentation' id='roomNav'><a href='addRoom.php'>Rooms</a></li>";
	
	if($accessLevel == 3) { //Only display the department tab to administrative user, as only they can access it
		$navs .= "	<li role='presentation' id='deptNav'><a href='addDepartment.php'>Departments</a></li>";
	}

	if($accessLevel > 1) { //Only display the Admins tab to office staff & admin users, as student workers can't access the page
		$navs .= "	<li role='presentation' id='adminNav'><a href='addAdmin.php'>Admins</a></li>";
	}
	$navs .= "</ul>";

	echo($navs);
}

/**
 * A method to check whether or not an Employee/Admin name (combination of first & last name) exist 
 * @param $fName The inputted first name to be checked
 * @param $lName The inputted last name to be checked
 * @param $table The table that is being checked (either Employee or Admin)
 * @return true/false Whether or not the inputted name already exists
 */
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
	}

	if(count($match) > 0) { //If a name is returned from the query, than the inputted name is a duplicate
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

/**
 * A function to get all of the departments in the database and return them as a set of <option> tags
 * @return html_content A list of <option> tags containing each department's name
 */
function getAllDepartments() {
	$database = new data;

	$depts = $database->getData("SELECT departmentName FROM department", array());

	foreach($depts as $arr) {
		echo "<option>" . $arr['departmentName'] . "</option>";
	}
}

/**
 * A function to get the department ID of a given department
 * @param $deptName The name of a department 
 * @return $arr['departmentId'] The ID number of the department
 */
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