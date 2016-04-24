<?php
	require("data.php");
	require("admin.php");
	require("filters.php");

	$name = filterString($_POST['name']);
	$cleanRegexName = "%" . addslashes($name) . "%";

	$database = new data;

	$results = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE fName LIKE :cleanRegexName OR lName LIKE :cleanRegexName OR CONCAT(fName, ' ', lName) LIKE :cleanRegexName ORDER BY lname ASC;", array(
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName,
			":cleanRegexName"=>$cleanRegexName
		));

	foreach($results as $arr) {
		echo "<li onclick='setAdminActive(this); disableCreate();'><span class='aId' style='display: none'>" . $arr['adminId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['departmentAbbr'] . "</span><hr /></li>";
	}
?>