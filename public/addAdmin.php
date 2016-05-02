<?php
	require_once("../database/data.php");
	require_once("../database/admin.php");
	require_once("../database/util.php");
	require_once("../database/dbException.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";
	//END REMOVE Section
	
	//Authentication - User must have a valid login & be an Office Staff member or System Administrator to access the admin page
	if($accessLevel < 2 || !$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}

	$database = new data;

	//Handling form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//Get common forms across new/edit/delete
		$department = getDepartmentId(filterString($_POST['department']));
		$fName = $_POST['firstName'];
		$lName = $_POST['lastName'];

		if(isset($_POST['new'])) { //If the user is submitting a new Admin (clicking the 'Create New' button)
			try {
				if(isDuplicateName($fName, $lName, "Admin")) { //check for duplicate names
					$returnMessage = alert("danger", "An administrator account for $fName $lName already exists");
				} else {
					if($accessLevel == 3) { //User must be either a system administrator...
						postAdmin();
						$returnMessage = alert("success", "$fName $lName successfully created");
					} else if($accessLevel < 3 && $adminDeptId == $department) { //or office staff in the new admin's department
						postAdmin();
						$returnMessage = alert("success", "$fName $lName successfully created");
					} else {
						$returnMessage = alert("danger", "You cannot create administrators outside of your department");
					}
				}
			}
		catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){ //If the user is editing a current Admin
			try {
				if(isDuplicateName($fName, $lName, "Admin")) {
					$returnMessage = alert("danger", "$fName $lName already exists as an Admin");
				} else {
					if($accessLevel == 3) { //User must be either a system administrator...
						putAdmin();
						$returnMessage = alert("success", "$fName $lName successfully updated");
					} else if($accessLevel < 3 && $adminDeptId == $department) { //or office staff in the admin's department
						putAdmin();
						$returnMessage = alert("success", "$fName $lName successfully updated");
					} else {
						//RETURN ERROR MESSAGE - LOG ERRROR?
						$returnMessage = alert("danger", "You cannot edit administrators outside of your department");
					}
				}
			}
		catch(dbException $db) {
				echo $db->alert();
			}		

		} elseif(isset($_POST['delete']) && isset($_POST['adminId'])) { //If the user is deleting an administrator
			//add try/catch?
			if($accessLevel == 3) { //User must be either a system administrator...
				deleteAdmin();
				$returnMessage = alert("success", "$fName $lName successfully deleted");
			} else if($accessLevel < 3 && $adminDeptId == $department) { //or office staff in the admin's department
				deleteAdmin();
				$returnMessage = alert("success", "$fName $lName successfully deleted");
			} else {
				//RETURN ERROR MESSAGE - LOG ERROR?
				$returnMessage = alert("danger", "You cannot delete administrators outside of your department");
			}
		}
	}

	/**
	 * A function to insert a new Administrator into the database
	 */
	function postAdmin() {
		global $database;

		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$username = filterString($_POST['username']);

		if(is_numeric($_POST['accessLevel'])) { //Validate that the accessLevel is an integer
			$accessLevel = $_POST['accessLevel'];
		} else {
			$accessLevel = 1;
		}

		$department = getDepartmentId(filterString($_POST['department']));

		$admin = new admin($database, null);	
		$admin->postParams($fName, $lName, $username, $accessLevel, $department);
	}

	/**
	 * A function to update a currently existing administrator
	 */
	function putAdmin() {
		global $database;

		if(is_numeric($_POST['adminId'])) {
			$adminId = $_POST['adminId'];
			$fName = filterString($_POST['firstName']);
			$lName = filterString($_POST['lastName']);
			$username = filterString($_POST['username']);

			if(is_numeric($_POST['accessLevel'])) { //Validate the the access level is an integer
				$accessLevel = $_POST['accessLevel'];
			} else {
				$accessLevel = 1;
			}

			$department = getDepartmentId(filterString($_POST['department']));

			$admin = new admin($database, $adminId);
			$admin->fetch();
			$admin->putParams($fName, $lName, $username, $accessLevel, $department);
		} else {
			$returnMessage = alert("danger", "Please input a numerical ID");
		}
	}

	/**
	 * A function to remove an existing administrator from the database
	 */
	function deleteAdmin() {
		global $database;

		$adminId = $_POST['adminId'];

		$admin = new admin($database, $adminId);
		$admin->delete();
	}

	/**
	 * A method to get all administrators in the database
	 * @param $adminDeptId The current user's department ID (ex. IST = 1)
	 * @param $accessLevel The current user's access Level (ex. Student Worker = 1)
	 * @return html_content A set of <li> tags containing the names & ID's of each admin user
	 */
	function getAllAdmins($adminDeptId, $accessLevel) {
		$database = new data;


		if($accessLevel == 3) { //If the current user is a System Administrator, they can see all administrators in the database
			$admins = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId ORDER BY lName ASC;", array());
		} else { //If they are not a System Administrator, they can only see admins from their department (and not system administrators)
			$admins = $database->getData("SELECT fName, lName, departmentAbbr, adminId FROM Admin JOIN department ON Admin.departmentId = department.departmentId WHERE Admin.departmentId=:deptId AND accessLevel < 3 ORDER BY lName ASC;", array(
					":deptId"=>$adminDeptId
				));
		}

		foreach($admins as $arr) {
			echo "<li onclick='setAdminActive(this); disableCreate();'><span class='aId' style='display: none'>" . $arr['adminId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['departmentAbbr'] . "</span><hr /></li>";
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
				<img src="media/rit-logo.png" id="imgRIT" alt="" />
			</div>
		</header>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if(isset($returnMessage)) { //Placeholder for the return message, so it displays at this location on the page
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
						<?php displayNav($accessLevel, $givenName) ?>
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
