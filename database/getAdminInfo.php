<?php
	require("data.php");
	require("admin.php");

	$adminId = $_GET['adminId']; //VALIDATE INT

	$database = new data;

	$results = $database->getData("SELECT * FROM Admin JOIN department ON Admin.departmentId = department.departmentID WHERE adminId= " . $adminId . ";", array());

	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>