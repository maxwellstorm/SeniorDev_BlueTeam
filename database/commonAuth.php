<?php
	require_once("admin.php");
	require_once("data.php");

	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	function getAccessLevel($username) {
		$acct = $database->getData("SELECT accessLevel FROM Admin WHERE username=:username;", array(
				":username"=>$username
			));

		return $acct[0]['accessLevel'];
	}

	function getAdminDepartment($username) {
		$acct = $database->getData("SELECT departmentId FROM Admin WHERE username=:username;", array(
				":username"=>$username
			));

		return $acct[0]['departmentId'];
	}

	function isAllowed($username) {
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
		$navs .= "	<li role='presentation' id='profNav'><a href='addprofessor.php'>Employees</a></li>";
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
?>