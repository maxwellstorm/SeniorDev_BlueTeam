<?php
	
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

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

		$emps = $database->getData("SELECT * FROM Employees ORDER BY lName;", array());

		return json_encode($emps);
	}
?>