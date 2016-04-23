<?php
	require("data.php");
	require("admin.php");
	require("filters.php");

	$name = filterString($_POST['name']);

	$database = new data;

	$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE fName LIKE '%" . $name . "%' OR lName LIKE '%" . $name . "%' OR CONCAT(fName, ' ', lName) LIKE '%" . $name . "%' ORDER BY lname ASC;", array());

	foreach($results as $arr) {
		echo "<li onclick='setAdminActive(this); disableCreate();'><span class='aId' style='display: none'>" . $arr['adminId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['departmentAbbr'] . "</span><hr /></li>";
	}
?>