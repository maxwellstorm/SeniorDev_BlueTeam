<?php
	require("data.php");
	require("department.php");

	$deptId = $_GET['deptId']; //Validate Int

	$database = new data;

	$results = $database->getData("SELECT * FROM department WHERE departmentId = '" . $deptId . "';", array());

	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>