<?php

	require_once("data.php");
	require_once("admin.php");
	require_once("department.php");
	require_once("employees.php");
	require_once("room.php");
	require_once("util.php");
	require_once("commonAuth.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;


	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

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
		} else if($functionToCall == 'floorplan') {
			getFloorPlanInfo();
		}
	}

	function getAdminInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['adminId'])) {
			$adminId = $_GET['adminId'];

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
		} else {
			echo json_encode(alert("danger", "INT ERROR"));
		}
	}

	function getDepartmentInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['deptId'])) {
			$deptId = $_GET['deptId'];

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
		} else {
			echo json_encode(alert("danger", "INT ERROR"));
		}
	}

	function getEmployeeInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['facultyId'])) {
			$facultyId = $_GET['facultyId'];

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
		} else {
			echo json_encode(alert("danger", "INT ERROR"));
		}
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

	function getFloorPlanInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['fpId'])) {
			$fpId = $_GET['fpId'];

			$database = new data;

			$results = $database->getData("SELECT * FROM floorPlan WHERE fpId = :fpId;", array(
				":fpId"=>$fpId
			));

			$returnArray = array();

			foreach($results as $arr) {
				foreach($arr as $key => $value) {
					$returnArray[$key] = $value;
				}
			}

			echo json_encode($returnArray);
		} else {
			echo json_encode(alert("danger", "INT ERROR"));
		}
	}
?>