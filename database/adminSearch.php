<?php
	require("data.php");
	require("admin.php");
	require("filters.php");

	//THESE GET REPLACED WITH SHIB-RELATED VARIABLES
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
	    die("Redirecting to notAuthorized.html");
	}


	$name = filterString($_POST['name']);
	$cleanRegexName = "%" . addslashes($name) . "%";

	$database = new data;

	if($accessLevel == 3) {
		$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
		));
	} else {
		$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE (fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName) AND Admin.departmentId=:deptId ORDER BY lname ASC;", array(
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":deptId"=>$adminDeptId
		));
	}

	foreach($results as $arr) {
		echo "<li onclick='setAdminActive(this); disableCreate();'><span class='aId' style='display: none'>" . $arr['adminId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['departmentAbbr'] . "</span><hr /></li>";
	}
?>