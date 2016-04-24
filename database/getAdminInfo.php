<?php
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	require("data.php");
	require("admin.php");

	$adminId = $_GET['adminId']; //VALIDATE INT

	$database = new data;

	$results = $database->getData("SELECT * FROM Admin JOIN department ON Admin.departmentId = department.departmentID WHERE adminId=:adminId;", array(
		":adminId"=>$adminId
	));

	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>