<?php
	//A script to handle the live search functionality for the employee/admin box on addEmployee.php and addAdmin.php
	require_once("data.php");
	require("employees.php");
	require_once("admin.php");
	require_once("util.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	if(!$allowed) { //Authentication - only users who exist in the system are authorized to use this
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}


	if (isset($_GET['page'])) { //Check the 'page' to determine where the AJAX request originated from to assign proper functionality
		$functionToCall = $_GET['page'];
		if ($functionToCall == 'admin') { //addAdmin.php
			adminSearch();
		}
		else if($functionToCall == "employee") { //addEmployee.php
			employeeSearch();
		}
	}

	/**
	 * A function to search through the Admin users in the system and return HTML based on the results
	 * @return html_content A formatted <li> containing information about an Admin user
	 */
	function adminSearch() {
		global $adminDeptId;
		global $accessLevel;

		$name = filterString($_GET['name']);
		$cleanRegexName = "%" . addslashes($name) . "%"; //Clean the name of the user so it can be included as a regex search without quotes disrupting it

		$database = new data;

		if($accessLevel == 3) { //If the user is a system admin, they are authorized to see ALL admin in the system
			//This query matches based on first name, last name, and full name (defined as "[fName] [lName]")
			$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
			));
		} else { //If the user isn't an admin, they are only authorized to see admin in their department, not including system administrators
			//This query matches based on first name, last name and full name (defined as "['fName'] ['lName']")
			$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE (fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName) AND Admin.departmentId=:deptId AND accessLevel < 3 ORDER BY lname ASC;", array(
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":deptId"=>$adminDeptId
			));
		}

		foreach($results as $arr) {
			echo "<li onclick='setAdminActive(this); disableCreate();'><span class='aId' style='display: none'>" . $arr['adminId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['departmentAbbr'] . "</span><hr /></li>";
		}
	}

	/**
	 * A function to search through the Admin users in the system and return HTML based on the results
	 * @return html_content A formatted <li> containing information about an Admin user
	 */
	function employeeSearch() {	
		global $adminDeptId;
		global $accessLevel;
		global $allowed;


		$name = filterString($_GET['name']);
		$cleanRegexName = "%" . addslashes($name) . "%"; //Clean the name of the user so it can be included as a regex search without quotes disrupting it


		$database = new data;

		if($accessLevel == 3) { //If the user is a system admin, they are authorized to see ALL employees in the system
			//This query matches based on first name, last name, and full name (defined as "[fName] [lName]")
			$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
			));
		} else { //If the user is office staff or a student worker, they are authorized to see only employees in their department
			//This query matches based on first name, last name, and full name (defined as "[fName] [lName]")
			$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE (fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName) AND (departmentId=:deptId OR secondaryDepartmentID=:sdId) ORDER BY lname ASC;", array(
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":deptId"=>$adminDeptId,
				":sdId"=>$adminDeptId
			));
		}

		foreach($results as $arr) {
			echo "<li onclick='setEmployeeActive(this);'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
		}
	}

?>