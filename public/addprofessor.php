<?php
	require_once("../database/data.php");
	require_once("../database/employees.php");
	require_once("../database/commonAuth.php");

	//THESE GET REPLACED WITH SHIB-RELATED VARIABLES
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	if(!$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}


	function getAllEmps($adminDeptId, $accessLevel) {
		$database = new data;


		if($accessLevel == 3) {
			$emps = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees ORDER BY lName ASC;", array());
		} else {
			$emps = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE (departmentId=:deptId OR secondaryDepartmentID=:sdId) ORDER BY lName ASC;", array(
				":deptId"=>$adminDeptId,
				":sdId"=>$adminDeptId
			));
		}

		foreach($emps as $arr) {
			echo "<li onclick='setActive(this); disableCreate();'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
		}
	}

	function getAllRooms() {
		$database = new data;

		$rooms = $database->getData("SELECT roomNumber FROM room", array());

		foreach($rooms as $arr) {
			echo "<option>" . $arr['roomNumber'] . "</option>";
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
		<link rel="stylesheet" type="text/css" media="screen" href="js/formvalidation/css/formValidation.min.css">
		<link rel="icon" href="media/favicon.ico">
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/bootstrap-select-1.10.0/js/bootstrap-select.js"></script>
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
				<form class="form-horizontal" id="addFaculty" name="addFaculty" enctype="multipart/form-data" action="../database/newFaculty.php" method="POST" onsubmit="removeOnlyBullets('highlights'); removeOnlyBullets('education')">
					<div class="col-lg-2" id="searchCol">
						<h3>SEARCH</h3>
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="filter" placeholder="Enter a name">
							</div>
						</div>
						<div class="form-group">
							<ul multiple class="form-control" id="results">
								<?php getAllEmps($adminDeptId, $accessLevel) ?>
							</ul>
							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>
					</div>
					<div class="col-lg-10">
						<?php displayNav($accessLevel) ?>
						<fieldset>
							<legend><h2>ADD A NEW EMPLOYEE</h2></legend>
							<div class="col-lg-5" id="leftCol">
								<div class="form-group">
									<div class="col-lg-4">
										<label for="image" class="control-label" style="float: right">Avatar<br />(160 x 160)</label>
										<br />
										<br />
										<input type="file" accept="image/*" onchange="loadFile(event)" name="image" value="Upload Image" style="float: right">
									</div>
									<div class="col-lg-4">
										<img id="userImage" src="media/no-preview.png" />
									</div>
								</div>
								 <input type="hidden" id="facultyId" name="facultyId">

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
									<label for="title" class="col-lg-4 control-label">Title</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="title" name="title">
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="col-lg-4 control-label">Email</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="email" name="email" patten="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}">
									</div>
								</div>

								<div class="form-group">
									<label for="phone" class="col-lg-4 control-label">Phone</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="phone" name="phone" onBlur="formatPhoneNum(this);">
									</div>
								</div>

								<div class="form-group">
									<label for="room" class="col-lg-4 control-label">Room #</label>
									<div class="col-lg-6">
										<select class="selectpicker" data-live-search="true" id="room" name="room" required>
											<?php getAllRooms() ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="depts" class="col-lg-4 control-label">Department</label>
									<div class="col-lg-6">
										<select class="selectpicker" multiple data-max-options="2" id="depts" name="depts[]" data-width="fit" required>
											<?php getAllDepartments() ?>
										</select>
									</div>

								</div>
							</div>

							<div class="col-lg-7" id="rightCol">
								<div class="radios">
									<div class="form-group col-lg-6">
										<label class="col-lg-4 control-label">Active</label>
										<div class="col-lg-8">
											<div class="radio">
		 										<label>
													<input type="radio" name="active" id="activeYes" value="activeYes" checked="" required>
													Yes
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="active" id="activeNo" value="activeNo">
													No
												</label>
											</div>
										</div>
									</div>

									<div class="form-group col-lg-6">
										<label class="col-lg-4 control-label">Faculty</label>
										<div class="col-lg-8">
											<div class="radio">
		 										<label>
													<input type="radio" name="faculty" id="facultyYes" value="facYes" checked="" required>
													Yes
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="faculty" id="facultyNo" value="facNo">
													No
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">About</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" id="about" name="about"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">Education</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" id="education" name="education"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">Highlights</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" id="highlights" name="highlights"></textarea>
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
				$('#profNav').addClass('active');
			});
		</script>
	</body>
</html>