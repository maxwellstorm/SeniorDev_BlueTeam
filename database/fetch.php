<?php
	require("data.php");
	require("employees.php");

	if (isset($_GET['function'])) {
		$functionToCall = $_GET['function'];
		if ($functionToCall == 'fetchAll') {
			echo fetchAll();
		} else if ($functionToCall == 'fetchDepts') {
			echo fetchDepts();
		} else if ($functionToCall == 'fetchRooms') {
			echo fetchRooms();
		} 
	}

	function fetchAll() {
		$database = new data;

		$emps = $database->getData("SELECT * FROM Employees ORDER BY lName;", array());

		return json_encode($emps);
	}

	function fetchDepts() {
		$database = new data;

		$depts = $database->getData("SELECT * FROM department;", array());

		return json_encode($depts);
	}

	function fetchRooms() {
		$database = new data;

		$rooms = $database->getData("SELECT * FROM room;", array());

		return json_encode($rooms);
	}
?>