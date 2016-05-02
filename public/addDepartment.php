<?php
	require("../database/data.php");
	require("../database/department.php");
	require_once("../database/dbException.php");
	require_once("../database/util.php");


	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";
	//END REMOVE

	//Authentication - User must have a valid login & be a System Administrator to access the department page
	if($accessLevel < 3 || !$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}

	$database = new data;

	//Handling form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$name = filterString($_POST['deptName']);

		if(isset($_POST['new'])) { //If the user is creating a new department (clicking the 'Create New' button)
			try{
				$abbr = filterString($_POST['deptAbbr']);

				$dept = new department($database, null);
				$dept->postParams($name, $abbr);
				$returnMessage = alert("success", "$name Department successfully created");
			} catch(dbException $db) {
				$returnMessage = $db->alert();
			}
		} elseif(isset($_POST['edit'])){ //If the user is editing an existing deparmtent	
			try{
				$deptId = $_POST['deptId'];
				$deptAbbr = filterString($_POST['deptAbbr']);

				$dept = new department($database, $deptId);
				$dept->putParams($name, $deptAbbr);
				$returnMessage = alert("success", "$name Department successfully updated");
			} catch(dbException $db){
				$returnMessage = $db->alert();
			}
		} elseif(isset($_POST['delete']) && isset($_POST['deptId'])) { //If the user is deleting an existing department		
			try{
				$id = $_POST['deptId'];

				if(doesHaveMembers($id)) { //Departments that contain Employees or Admins cannot be deleted
					$returnMessage = alert("danger", "You can't delete the $name Department because it has employees or administrators in it");
				} else {
					$dept = new department($database, $id);
					$dept->delete();
					$returnMessage = alert("success", "$name Department successfully deleted");
				}
			} catch(dbException $db){
				$returnMessage = $db->alert();
			}
		}
	}

	/**
	 * A function to return a set of <li> elements containing the department ID & department Name
	 * @return html_content A set of <li> tags to be placed inside a <select>
	 */
	function getAllDepartmentsId() {
		try{
			$database = new data;

			$depts = $database->getData("SELECT departmentId, departmentName FROM department", array());

			foreach($depts as $arr) {
				echo "<option value='" . $arr['departmentId'] . "'>" . $arr['departmentName'] ."</option>";
			}
		} catch(dbException $db){
			echo $db->alert();
		}
	}

	/**
	 * A method to check if a given department has employees or administrators assigned to it
	 * @param $deptId The ID number of a department
	 * @return true/false A boolean indicating whether or not the department has members assigned to it
	 */
	function doesHaveMembers($deptId) {
		try{
			$database = new data;

			$empMatch = $database->getData("SELECT facultyId FROM Employees WHERE departmentId=:departmentId;", array(
				":departmentId"=>$deptId
			));

			$adminMatch = $database->getData("SELECT adminId FROM Admin WHERE departmentId=:departmentId;", array(
				":departmentId"=>$deptId
			));

			if(count($empMatch) > 0 || count($adminMatch) > 0) { //If any ID numbers (representing department members) are returned, return true
				return true;
			} else {
				return false;
			}
		} catch(dbException $db){
			echo $db->alert();
			return false;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>IST Faculty Management Interface - Admin View: Department</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/main.css">
		<link rel="stylesheet" type="text/css" media="screen" href="js/formvalidation/css/formValidation.min.css">
		<link rel="icon" href="media/favicon.ico">
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/formvalidation/js/formValidation.min.js"></script>
		<script type="text/javascript" src="js/formvalidation/js/framework/bootstrap.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body class="admin">
		<header class="dropShadow">
			<div id="headerInner">
				<h1>FACULTY DIRECTORY</h1>
				<img src="media/rit-logo.png" id="imgRIT" alt="" />
			</div>
		</header>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if(isset($returnMessage)) { //Placeholder for the return message, so it displays at this location on the page
					echo($returnMessage); 
				} ?>
				<form class="form-horizontal" id="addDepartment" name="addDepartment" action="addDepartment.php" method="POST">
					<div class="col-lg-2 dropdownSelect" id="searchCol">
						<select id="deptSelect" class="form-control">
							<option value="" disabled selected>Select a Department</option>
							<?php getAllDepartmentsId() ?>
						</select>
						<br />

						<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
						<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
						<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
					</div>

					<div class="col-lg-10">
						<?php displayNav($accessLevel, $givenName) ?>
						<fieldset>
							<legend><h2>ADD A NEW DEPARTMENT</h2></legend>
							<input type="hidden" name="deptId" id="deptId">

							<div class="form-group">
								<label for="name" class="col-lg-3 control-label">Department Name</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="deptName" name="deptName" required>
								</div>
							</div>

							<div class="form-group">
								<label for="textArea" class="col-lg-3 control-label">Department Abbreviation</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="deptAbbr" name="deptAbbr">
								</div>
							</div>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$('#deptNav').addClass('active');
			});
		</script>
	</body>
</html>