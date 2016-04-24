<?php
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
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