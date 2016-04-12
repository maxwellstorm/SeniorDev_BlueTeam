<?php
	require("data.php");
	require("employees.php");

	$name = $_POST['name'];

	$database = new data;

	$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE fName LIKE '%" . $name . "%' OR lName LIKE '%" . $name . "%' ORDER BY lname ASC;", array());

	foreach($results as $arr) {
		echo "<li onclick='setActive(this);'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
	}
?>