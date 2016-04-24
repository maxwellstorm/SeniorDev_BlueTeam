<?php
	require("../database/data.php");
	require("../database/department.php");
	require_once("../database/dbException.php");
	require("../database/filters.php");
	require("../database/commonAuth.php");


	//THESE GET REPLACED WITH SHIB-RELATED VARIABLES
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	if($accessLevel < 3 || !$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}


	$database = new data;
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['new'])) {
		try{
		
			$name = filterString($_POST['deptName']);
			$abbr = filterString($_POST['deptAbbr']);

			$dept = new department($database, null);
			$dept->postParams($name, $abbr);
		}
		catch(dbException $db){
			echo $db->alert();
		}
	} elseif(isset($_POST['edit'])){	
		try{
			
			$deptId = $_POST['deptId']; //add int validation
			$deptName = filterString($_POST['deptName']);
			$deptAbbr = filterString($_POST['deptAbbr']);

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
				<!-- <h3>Admin Panel</h3> -->
				<img src="media/rit-logo.png" id="imgRIT" alt="" />
			</div>
		</header>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addDepartment" name="addDepartment" action="addDepartment.php" method="POST">
					<div class="col-lg-2 dropdownSelect" id="searchCol">
						<select id="deptSelect" class="form-control">
							<option value="" disabled selected>Select a Department</option>
							<?php getAllDepartments() ?>
						</select>
						<br />

						<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
						<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
						<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
					</div>

					<div class="col-lg-10">
						<?php displayNav($accessLevel) ?>
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