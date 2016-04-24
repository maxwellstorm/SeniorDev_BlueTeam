<?php
	
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	require("data.php");
	require("employees.php");
	require("filters.php");

	//THESE GET REPLACED WITH SHIB-RELATED VARIABLES
	$adminDeptId = 1;
	$accessLevel = 3;


	$name = filterString($_POST['name']);
	$cleanRegexName = "%" . addslashes($name) . "%";

	$database = new data;

	if($accessLevel == 3) {
		$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
		));
	} else {
		$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE (fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName) AND (departmentId=:deptId OR secondaryDepartmentID=:sdId) ORDER BY lname ASC;", array(
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":deptId"=>$adminDeptId,
			":sdId"=>$adminDeptId
		));
	}

	foreach($results as $arr) {
		echo "<li onclick='setActive(this);'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
	}
?>