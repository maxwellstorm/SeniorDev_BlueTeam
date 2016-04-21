<?php
	require("../database/data.php");
	require("../database/department.php");
	require_once("../database/dbException.php");

	$database = new data;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['new'])) {
		try{
		
			$name = $_POST['deptName'];
			$abbr = $_POST['deptAbbr'];

			$dept = new department($database, null);
			$dept->postParams($name, $abbr);
		}
		catch(dbException $db){
			echo $db->alert();
		}
	} elseif(isset($_POST['edit'])){	
		try{
			$deptId = $_POST['deptId'];
			$deptName = $_POST['deptName'];
			$deptAbbr = $_POST['deptAbbr'];

			$dept = new department($database, $deptId);
			$dept->putParams($deptName, $deptAbbr);
		}
		catch(dbException $db){
			echo $db->alert();
		}

	} elseif(isset($_POST['delete']) && isset($_POST['deptId'])) {
		$id = $_POST['deptId'];

		$dept = new department($database, $id);
		$dept->delete();
	}
}

	function getAllDepartments() {
		$database = new data;

		$depts = $database->getData("SELECT departmentId, departmentName FROM department", array());

		foreach($depts as $arr) {
			echo "<option value='" . $arr['departmentId'] . "'>" . $arr['departmentName'] ."</option>";
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
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body>
		<div id="header">
			<img id="headLogo" src="media/rit_black_no_bar.gif" />
			<h1 class="headerText">Faculty Directory</h1>
			<h5 class="headerText">Admin Portal - Department</h5>
			<a href="addprofessor.php">Add Professor</a>
			<a href="addRoom.php">Add Room</a>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addDepartment" name="addDepartment" action="addDepartment.php" method="POST">
					<fieldset>
						<legend>ADD A NEW DEPARTMENT</legend>
						<div class="col-lg-2" id="searchCol">
							<select id="deptSelect" class="form-control">
								<option value="" disabled selected>Select a Department</option>
								<?php getAllDepartments() ?>
							</select>
							<br />

							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary">
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>

						<div class="col-lg-10">
							
							<input type="hidden" name="deptId" id="deptId">

							<div class="form-group">
								<label for="name" class="col-lg-3 control-label">Department Name</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="deptName" name="deptName">
								</div>
							</div>

							<div class="form-group">
								<label for="textArea" class="col-lg-3 control-label">Department Abbreviation</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="deptAbbr" name="deptAbbr">
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>