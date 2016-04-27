<?php
	require_once("admin.php");
	require_once("data.php");

	/* THESE NEED TO BE UNCOMMENTED FOR PRODUCTION
	$username = $_SERVER["uid"];
	$firstName = $_SERVER['givenName'];
	$adminDeptId = getAdminDepartment($username);
	$accessLevel = getAccessLevel($username);
	$allowed = isAllowed($username); */
	

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

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

	function displayNav($accessLevel) {
		$navs = "<ul class='nav nav-tabs'>";
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
			$match  =$database->getData("SELECT adminId FROM Admin WHERE fName=:fName AND lName=:lName;", array(
			":fName"=>$fName,
			":lName"=>$lName
		));
		} else if(strcmp($table, "Employees") == 0) {
			$match  =$database->getData("SELECT facultyId FROM Employees WHERE fName=:fName AND lName=:lName;", array(
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
?>