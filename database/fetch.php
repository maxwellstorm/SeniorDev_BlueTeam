<?php
	//A class to return employee information for the main touchscreen display
	require("data.php");
	require("employees.php");

	if (isset($_GET['function'])) { //Get the parameter passed to indicate the appropriate AJAX call
		$functionToCall = $_GET['function'];
		if ($functionToCall == 'fetchAll') { //get Employee Information
			echo fetchAll();
		} else if ($functionToCall == 'fetchDepts') { //get Department Information
			echo fetchDepts();
		} else if ($functionToCall == 'fetchRooms') { //get Room Information
			echo fetchRooms();
		} 
	}

	/**
	 * A function to return information about all Employees, ordered by last name
	 * @return $emps A JSON encoded object containing information about all Employees
	 */
	function fetchAll() {
		$database = new data;

		$emps = $database->getData("SELECT * FROM Employees ORDER BY lName;", array());

		return json_encode($emps);
	}

	/**
	 * A function to return information about all Departments
	 * @return $depts A JSON encoded object containing information about all Departments
	 */
	function fetchDepts() {
		$database = new data;

		$depts = $database->getData("SELECT * FROM department;", array());

		return json_encode($depts);
	}

	/**
	 * A function to return information about all Rooms
	 * @return $rooms A JSON encoded object containing information about all Rooms
	 */
	function fetchRooms() {
		$database = new data;

		$rooms = $database->getData("SELECT * FROM room;", array());

		return json_encode($rooms);
	}
?>