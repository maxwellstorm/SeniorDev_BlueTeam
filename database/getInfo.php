<?php
	require("data.php");
	require("employees.php");

	$facultyId = $_GET['facultyId']; //VALIDATE INT

	$database = new data;

	$results = $database->getData("SELECT * FROM Employees JOIN department ON Employees.departmentId = department.departmentID WHERE facultyId= " . $facultyId . ";", array());
	$results2 = $database->getData("SELECT secondaryDepartmentID, departmentName FROM Employees JOIN department on Employees.secondaryDepartmentID = department.departmentID WHERE facultyId= " . $facultyId . ";", array());

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