<?php
	$allowed = true;

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	require_once("data.php");
	require_once("admin.php");
	require_once("department.php");
	require_once("employees.php");
	require_once("room.php");
	require_once("filters.php");

	$adminDeptId = 1;
	$accessLevel = 3;

	if (isset($_GET['page'])) {
		$functionToCall = $_GET['page'];
		if ($functionToCall == 'admin') {
			getAdminInfo();
		} else if($functionToCall == "department") {
			getDepartmentInfo();
		} else if($functionToCall == 'employee') {
			getEmployeeInfo();
		} else if($functionToCall == 'room') {
			getRoomInfo();
		}
	}

	function getAdminInfo() {
		global $adminDeptId;
		global $accessLevel;

		$adminId = $_GET['adminId']; //VALIDATE INT

		$database = new data;

		$results = $database->getData("SELECT * FROM Admin JOIN department ON Admin.departmentId = department.departmentID WHERE adminId=:adminId;", array(
			":adminId"=>$adminId
		));

		$returnArray = array();

		foreach($results as $arr) {
			foreach($arr as $key => $value) {
				$returnArray[$key] = $value;
			}
		}

		echo json_encode($returnArray);
	}

	function getDepartmentInfo() {
		global $adminDeptId;
		global $accessLevel;

		$deptId = $_GET['deptId']; //Validate Int

		$database = new data;

		$results = $database->getData("SELECT * FROM department WHERE departmentId = :deptId;", array(
			":deptId"=>$deptId
		));

		$returnArray = array();

		foreach($results as $arr) {
			foreach($arr as $key => $value) {
				$returnArray[$key] = $value;
			}
		}

		echo json_encode($returnArray);
	}

	function getEmployeeInfo() {
		global $adminDeptId;
		global $accessLevel;

		$facultyId = $_GET['facultyId']; //VALIDATE INT

		$database = new data;

		$results = $database->getData("SELECT * FROM Employees JOIN department ON Employees.departmentId = department.departmentID WHERE facultyId=:facultyId;", array(
				":facultyId"=>$facultyId
			));
		$results2 = $database->getData("SELECT secondaryDepartmentID, departmentName FROM Employees JOIN department on Employees.secondaryDepartmentID = department.departmentID WHERE facultyId=:facultyId;", array(
				":facultyId"=>$facultyId
			));

		//$array[] = $returnArray;
		$returnArray = array();

		foreach($results as $arr) {
			foreach($arr as $key => $value) {
				$returnArray[$key] = $value;
			}
		}

		$returnArray["secondaryDepartmentName"] = $results2[0]['departmentName'];

		echo json_encode($returnArray);
	}

	function getRoomInfo() {
		global $adminDeptId;
		global $accessLevel;

		$roomNum = filterString($_GET['room']);

		$database = new data;

		$results = $database->getData("SELECT * FROM room WHERE roomNumber = :roomNum;", array(
			":roomNum"=>$roomNum
		));

		$returnArray = array();

		foreach($results as $arr) {
			foreach($arr as $key => $value) {
				$returnArray[$key] = $value;
			}
		}

		echo json_encode($returnArray);
	}
?>