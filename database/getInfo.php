<?php
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	require("data.php");
	require("employees.php");

	$facultyId = $_GET['facultyId']; //VALIDATE INT

	$database = new data;

	$results = $database->getData("SELECT * FROM Employees JOIN department ON Employees.departmentId = department.departmentID WHERE facultyId=:facultyId;", array(
			":facultyId"=>$facultyId
		));
	$results2 = $database->getData("SELECT secondaryDepartmentID, departmentName FROM Employees JOIN department on Employees.secondaryDepartmentID = department.departmentID WHERE facultyId=:facultyId;", array(
			":facultyId"=>$facultyId
		));

	//$array[] = $returnArray;
	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	$returnArray["secondaryDepartmentName"] = $results2[0]['departmentName'];

	echo json_encode($returnArray);
?>