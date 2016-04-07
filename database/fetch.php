<?php
	require("data.php");
	require("employees.php");

	if (isset($_GET['function'])) {
		$functionToCall = $_GET['function'];
		if ($functionToCall == 'fetchAll') {
			echo fetchAll();
		}
	}

	function fetchAll() {
		$database = new data;

		//Will need to change statement to only show those in admin's department (role stuff)
		$emps = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees;", array());

		return json_encode($emps);
	}
?>