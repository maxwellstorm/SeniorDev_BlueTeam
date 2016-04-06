<?php
	require("data.php");
	require("employees.php");

	$facultyId = $_GET['facultyId'];

	$database = new data;

	$results = $database->getData("SELECT * FROM Employees JOIN department ON Employees.departmentId = department.departmentID WHERE facultyId= " . $facultyId . ";", array());

	//$array[] = $returnArray;
	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>
