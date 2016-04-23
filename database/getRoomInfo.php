<?php
	require("data.php");
	require("room.php");
	require("filters.php");

	$roomNum = filterString($_GET['roomNum']);

	$database = new data;

	$results = $database->getData("SELECT * FROM room WHERE roomNumber = '" . $roomNum . "';", array());

	$returnArray = array();

	foreach($results as $arr) {
		foreach($arr as $key => $value) {
			$returnArray[$key] = $value;
		}
	}

	echo json_encode($returnArray);
?>