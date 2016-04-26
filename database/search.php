<?php
	require_once("data.php");
	require("employees.php");
	require_once("admin.php");
	require("filters.php");
	require_once("commonAuth.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}


	if (isset($_GET['page'])) {
		$functionToCall = $_GET['page'];
		if ($functionToCall == 'admin') {
			adminSearch();
		}
		else if($functionToCall == "employee") {
			employeeSearch();
		}
	}

	function adminSearch() {
		global $adminDeptId;
		global $accessLevel;



		$name = filterString($_GET['name']);
		$cleanRegexName = "%" . addslashes($name) . "%";

		$database = new data;

		if($accessLevel == 3) {
			$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
				":cleanRegexName"=>$cleanRegexName,
			));
		} else {
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

	function employeeSearch() {
		global $adminDeptId;
		global $accessLevel;
		global $allowed;


		$name = filterString($_GET['name']);
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
	}

?>