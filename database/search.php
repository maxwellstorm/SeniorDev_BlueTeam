<?php
	require("data.php");
	require("employees.php");
	require("filters.php");

	$name = filterString($_POST['name']);
	$cleanRegexName = "%" . addslashes($name) . "%";

	$database = new data;

	$results = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName
		));

	foreach($results as $arr) {
		echo "<li onclick='setActive(this);'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
	}
?>