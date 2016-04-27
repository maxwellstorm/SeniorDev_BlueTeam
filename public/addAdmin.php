<?php
	require_once("../database/data.php");
	require_once("../database/admin.php");
	require_once("../database/filters.php");
	require_once("../database/commonAuth.php");
	require_once("../database/dbException.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	
	if($accessLevel < 2 || !$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}


	$database = new data;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$department = getDepartmentId(filterString($_POST['department']));
		$fName = $_POST['firstName'];
		$lName = $_POST['lastName'];
		if(isset($_POST['new'])) {
			try {
				if(isDuplicateName($fName, $lName, "Admin")) {
					$returnMessage = alert("danger", "You can't create duplicate Admins!");
					//throw new dbException("You can't create duplicate Admins", 1);
				} else {
					if($accessLevel == 3) {
						postAdmin();
						$returnMessage = alert("success", "Administrative user successfully created");
					} else if($accessLevel < 3 && $adminDeptId == $department) {
						postAdmin();
						$returnMessage = alert("success", "Administrative user successfully created");
					} else {
						//RETURN ERROR MESSAGE - LOG ERROR?
						$returnMessage = alert("danger", "You aren't authorized to do that!");
					}
				}
			}
		catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){	
			try {
				if($accessLevel == 3) {
					putAdmin();
					$returnMessage = alert("success", "Administrative user successfully updated");
				} else if($accessLevel < 3 && $adminDeptId == $department) {
					putAdmin();
					$returnMessage = alert("success", "Administrative user successfully updated");
				} else {
					//RETURN ERROR MESSAGE - LOG ERRROR?
					$returnMessage = alert("danger", "You aren't authorized to do that!");
				}
			}
		catch(dbException $db) {
				echo $db->alert();
			}		

		} elseif(isset($_POST['delete']) && isset($_POST['adminId'])) {
			//add try/catch?
			if($accessLevel == 3) {
				deleteAdmin();
				$returnMessage = alert("success", "Administrative user successfully deleted");
			} else if($accessLevel < 3 && $adminDeptId == $department) {
				deleteAdmin();
				$returnMessage = alert("success", "Administrative user successfully deleted");
			} else {
				//RETURN ERROR MESSAGE - LOG ERROR?
				$returnMessage = alert("danger", "You aren't authorized to do that!");
			}
		}
	}

	function postAdmin() {
		global $database;

		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$username = filterString($_POST['username']);
		$accessLevel = $_POST['accessLevel']; //verify int
		$department = getDepartmentId(filterString($_POST['department']));

		$admin = new admin($database, null);	
		$admin->postParams($fName, $lName, $username, $accessLevel, $department);
	}

	function putAdmin() {
		global $database;

		$adminId = $_POST['adminId']; //int validate
		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$username = filterString($_POST['username']);
		$accessLevel = $_POST['accessLevel']; //verify int
		$department = getDepartmentId(filterString($_POST['department']));

		$admin = new admin($database, $adminId);
		$admin->fetch(); //Do I need to fetch?
		$admin->putParams($fName, $lName, $username, $accessLevel, $department);
	}

	function deleteAdmin() {
		global $database;

		$adminId = $_POST['adminId'];

		$admin = new admin($database, $adminId);
		$admin->delete();
	}

	function getAllAdmins($adminDeptId, $accessLevel) {
		$database = new data;


		if($accessLevel == 3) {
			$admins = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId ORDER BY lName ASC;", array());
		} else {
			$admins = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE Admin.departmentId=:deptId AND accessLevel < 3 ORDER BY lName ASC;", array(
					":deptId"=>$adminDeptId
				));
		}

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

	function getDepartmentId($deptName) {
		$database = new data;

		$depts = $database->getData("SELECT departmentId, departmentName FROM department", array());

		foreach($depts as $arr) {
			if(strcmp($deptName, $arr['departmentName']) == 0) {
				return $arr['departmentId'];
			}
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
				<?php if(isset($returnMessage)) {
					echo($returnMessage); 
				} ?>
				<form class="form-horizontal" id="addAdmin" name="addAdmin" action="addAdmin.php" method="POST">
					<div class="col-lg-2" id="searchCol">
						<h3>SEARCH</h3>
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="adminFilter" placeholder="Enter a name">
							</div>
						</div>
						<div class="form-group">
							<ul multiple class="form-control" id="results">
								<?php getAllAdmins($adminDeptId, $accessLevel) ?>
							</ul>
							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>
					</div>
					<div class="col-lg-10">
						<?php displayNav($accessLevel) ?>
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
									<label for="department" class="col-lg-4 control-label">Department</label>
									<div class="col-lg-7">
										<select class="form-control" id="department" name="department" required>
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
		<script>
			$(document).ready(function(){
				$('#adminNav').addClass('active');
			});
		</script>
	</body>
</html>