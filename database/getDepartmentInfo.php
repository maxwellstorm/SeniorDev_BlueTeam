<?php
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	require("data.php");
	require("department.php");

	$deptId = $_GET['deptId']; //Validate Int

	$database = new data;

	$results = $database->getData("SELECT * FROM department WHERE departmentId = :deptId;", array(
		":deptId"=>$deptId
	));

	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>