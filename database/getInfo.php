<?php
	//A script that handles all AJAX calls for populating the form with information about a selected entity
	require_once("data.php");
	require_once("admin.php");
	require_once("department.php");
	require_once("employees.php");
	require_once("room.php");
	require_once("util.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	//END REMOVE

	if(!$allowed) { //Authentication - users cannot access this if they don't exist in the system
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	}

	if (isset($_GET['page'])) { //Check the 'page' to determine which page the AJAX request originated from, so the appropriate message can be called
		$functionToCall = $_GET['page'];
		if ($functionToCall == 'admin') { //addAdmin.php
			getAdminInfo();
		} else if($functionToCall == "department") { //addDepartment.php
			getDepartmentInfo();
		} else if($functionToCall == 'employee') { //addEmployee.php
			getEmployeeInfo();
		} else if($functionToCall == 'room') { //addRoom.php
			getRoomInfo();
		} else if($functionToCall == 'floorplan') { //addFloorplan.php
			getFloorPlanInfo();
		}
	}

	/**
	 * A method to get the information regarding an admin user
	 * @return $returnArray A JSON encoded object returned to the page, which contains the information that is used to populate the form
	 */
	function getAdminInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['adminId'])) { //Validate that the given ID is numeric
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
		} else { //If the ID is not an int (someone has tryed to tamper with the system), return an error message
			echo json_encode(alert("danger", "Please input an integer value for the Administrator"));
		}
	}

	/**
	 * A method to get the information regarding a department
	 * @return $returnArray A JSON encoded object returned to the page, which contains the information that is used to populate the form
	 */
	function getDepartmentInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['deptId'])) { //validation that the department ID is a number
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
			echo json_encode(alert("danger", "Please input an integer value for the Administrator"));
		}
	}
	
	/**
	 * A method to get the information regarding an Employee
	 * @return $returnArray A JSON encoded object returned to the page, which contains the information that is used to populate the form
	 */
	function getEmployeeInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['facultyId'])) { //Validation that the employee ID is a number
			$facultyId = $_GET['facultyId'];

			$database = new data;

			//Query one that gets all information and the primary department name
			$results = $database->getData("SELECT * FROM Employees JOIN department ON Employees.departmentId = department.departmentID WHERE facultyId=:facultyId;", array(
					":facultyId"=>$facultyId
				));
			//Query two, that gets the name (rather than just the ID) of the secondary department
			$results2 = $database->getData("SELECT secondaryDepartmentID, departmentName FROM Employees JOIN department on Employees.secondaryDepartmentID = department.departmentID WHERE facultyId=:facultyId;", array(
					":facultyId"=>$facultyId
				));

			$returnArray = array();

			foreach($results as $arr) { //Append everything from query 1 to the array
				foreach($arr as $key => $value) {
					$returnArray[$key] = $value;
				}
			}

			//Append the second department name to the array
			$returnArray["secondaryDepartmentName"] = $results2[0]['departmentName'];

			echo json_encode($returnArray);
		} else {
			echo json_encode(alert("danger", "Please input an integer value for the Employee"));
		}
	}

	/**
	 * A method to get the information regarding a Room
	 * @return $returnArray A JSON encoded object returned to the page, which contains the information that is used to populate the form
	 */
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

	/**
	 * A method to get the information regarding a Floor plan
	 * @return $returnArray A JSON encoded object returned to the page, which contains the information that is used to populate the form
	 */
	function getFloorPlanInfo() {
		global $adminDeptId;
		global $accessLevel;

		if(is_numeric($_GET['fpId'])) { //validation that the floor plan's ID is numeric
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
			echo json_encode(alert("danger", "Please input an integer value for the Floor Plan"));
		}
	}
?>