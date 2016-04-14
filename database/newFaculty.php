<?php
	require("data.php");
	require("employees.php");

	//if(isset($_POST['new'])) {
	if(!empty($_POST)) {
		$database = new data;
		
		$fName = $_POST['firstName'];
		$lName = $_POST['lastName'];
		$email = $_POST['email'];
		$active = $_POST['active'];
		$faculty = $_POST['faculty'];
		$phone = $_POST['phone'];
		$about = $_POST['about'];
		$education = $_POST['education'];
		$highlights = $_POST['highlights'];
		$roomNum = $_POST['room'];
		$title = null; //$_POST[''];
		$secDeptId = null; //$_POST[''];
		$dept = $_POST['dept'];

		if(strcmp($dept, "Information Sciences & Technology") == 0) {
			$dept = 1;
		} elseif(strcmp($dept, "Interactive Games & Media") == 0) { 
			$dept = 2;
		} elseif(strcmp($dept, "Computing Security") == 0) {
			$dept = 3;
		}

		var_dump($dept);
		echo("DEPARTMENT");
		if(strcmp($active, "activeYes") == 0) {
			$active = 1;
		} else {
			$active = 0;
		}

		if(strcmp($faculty, "facYes") == 0) {
			$faculty = 1;
		} else {
			$faculty = 0;
		}

		var_dump($_POST);

		echo("<br /><br />");
		echo($fName . "<br />");
		echo($lName . "<br />");
		echo($email . "<br />");
		echo($active . "<br />"); 
		echo($faculty . "<br />"); 
		echo($phone . "<br />");
		echo($about . "<br />");
		echo($education . "<br />");
		echo($highlights . "<br />");
		echo($roomNum . "<br />");
		echo($title . "<br />");
		echo($secDeptId . "<br />");
		echo($dept . "<br />");

		$employee = new employees($database, null);

		$employee->postParams($fName, $lName, $email, $active, $faculty, $phone, $about, $education, $highlights, $dept, $roomNum, $title, $secDeptId);

		//header('Location: kelvin.ist.rit.edu/~blueteam/public/addprofessor.php');
	}
?>