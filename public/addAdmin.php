<?php
	require("../database/data.php");
	require("../database/admin.php");

	$database = new data;

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['new'])) {
			try{
				$fName = $_POST['firstName'];
				$lName = $_POST['lastName'];
				$username = $_POST['username'];
				$accessLevel = $_POST['accessLevel'];
				$department = $_POST['dept'];

				if(strcmp($department, "Information Sciences & Technology") == 0) {
					$department = 1;
				} elseif(strcmp($department, "Interactive Games & Media") == 0) { 
					$department = 2;
				} elseif(strcmp($department, "Computing Security") == 0) {
					$department = 3;
				}

				$admin = new admin($database, null);	
				$admin->postParams($fName, $lName, $username, $accessLevel, $department);
			}
		catch(dbException $db){
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){	
			try{
				$adminId = $_POST['adminId'];
				$fName = $_POST['firstName'];
				$lName = $_POST['lastName'];
				$username = $_POST['username'];
				$accessLevel = $_POST['accessLevel'];
				$department = $_POST['dept'];

				if(strcmp($department, "Information Sciences & Technology") == 0) {
					$department = 1;
				} elseif(strcmp($department, "Interactive Games & Media") == 0) { 
					$department = 2;
				} elseif(strcmp($department, "Computing Security") == 0) {
					$department = 3;
				}
				echo($adminId);
				echo($fName);
				echo($lName);
				echo($username);
				echo($accessLevel);
				echo($department);
				$admin = new admin($database, $adminId);
				$admin->fetch();
				$admin->putParams($fName, $lName, $username, $accessLevel, $department);
			}
		catch(dbException $db){
				echo $db->alert();
			}		

		} elseif(isset($_POST['delete']) && isset($_POST['adminId'])) {
			$adminId = $_POST['adminId'];

			$admin = new admin($database, $adminId);
			$admin->delete();
		}
	}

	function getAllAdmins() {
		$database = new data;

		//Will need to change statement to only show those in admin's department (role stuff)
		$admins = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId ORDER BY lName ASC;", array());

		foreach($admins as $arr) {
			echo "<li onclick='setAdminActive(this); disableCreate();'><span class='aId' style='display: none'>" . $arr['adminId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['departmentAbbr'] . "</span><hr /></li>";
		}
	}
	
	function getAllDepartments() {
		$database = new data;

		$depts = $database->getData("SELECT departmentName FROM department", array());

		foreach($depts as $arr) {
			echo "<option>" . $arr['departmentName'] . "</option>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Touchscreen Directory - Admin Panel</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/main.css">
		<link rel="stylesheet" type="text/css" media="screen" href="js/bootstrap-select-1.10.0/css/bootstrap-select.css">
		<link rel="icon" href="media/favicon.ico">
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
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
		<a href="addprofessor.php">Add Professor</a>
		<a href="addRoom.php">Add Room</a>
		<a href="addDepartment.php">Add Department</a>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addFaculty" name="addFaculty" action="addAdmin.php" method="POST">
					<div class="col-lg-2" id="searchCol">
						<h3>SEARCH</h3>
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="adminFilter" placeholder="type a name">
							</div>
						</div>
						<div class="form-group">
							<ul multiple class="form-control" id="results">
								<?php getAllAdmins() ?>
							</ul>
							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>
					</div>
					<div class="col-lg-10">
						<fieldset>
							<legend><h2>ADD A NEW ADMINISTRATOR</h2></legend>
							<div class="col-lg-6" id="leftCol">
								<input type="hidden" id="adminId" name="adminId">

								<div class="form-group">
									<label for="firstName" class="col-lg-4 control-label">First Name</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="firstName" name="firstName" required>
									</div>
								</div>

								<div class="form-group">
									<label for="lastName" class="col-lg-4 control-label">Last Name</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="lastName" name="lastName" required>
									</div>
								</div>

								<div class="form-group">
									<label for="username" class="col-lg-4 control-label">RIT Username</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="username" name="username" required>
									</div>
								</div>
							</div>

							<div class="col-lg-6" id="rightCol">
								<div class="form-group">
									<label for="accessLevel" class="col-lg-4 control-label">Access Level</label>
									<div class="col-lg-7">
										<select class="form-control" id="accessLevel" name="accessLevel" required>
											<option selected disabled>Select an Access Level</option>
											<option value="1">Student Worker</option>
											<option value="2">Office Staff</option>
											<option value="3">System Administrator</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="dept" class="col-lg-4 control-label">Department</label>
									<div class="col-lg-7">
										<select class="form-control" id="dept" name="dept" required>
											<option selected disabled>Select a Department</option>
											<?php getAllDepartments() ?>
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>