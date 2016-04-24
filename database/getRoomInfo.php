<?php
	require("data.php");
	require("room.php");
	require("filters.php");

	$roomNum = filterString($_GET['room']);

	$database = new data;

	$results = $database->getData("SELECT * FROM room WHERE roomNumber = :roomNum;", array(
		":roomNum"=>$roomNum
	));

	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>