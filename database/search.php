<?php
	require("data.php");
	require("employees.php");
	require("filters.php");

	$name = filterString($_POST['name']);
	$cleanName = addslashes($name);

	$database = new data;

	$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE fName LIKE '%" . $cleanName . "%' OR lName LIKE '%" . $cleanName . "%' OR CONCAT(fName, ' ', lName) LIKE '%" . $cleanName . "%' ORDER BY lname ASC;", array());

	foreach($results as $arr) {
		echo "<li onclick='setActive(this);'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
	}
?>