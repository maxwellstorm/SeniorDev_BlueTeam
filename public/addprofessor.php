<?php
	require("../database/data.php");
	require("../database/employees.php");


	function getAllEmps() {
		$database = new data;

		//Will need to change statement to only show those in admin's department (role stuff)
		$emps = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees ORDER BY lName ASC;", array());

		foreach($emps as $arr) {
			echo "<li onclick='setActive(this);'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
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
		<title>IST Faculty Management Interface - Admin View</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/main.css">
		<link rel="stylesheet" type="text/css" media="screen" href="js\bootstrap-select-1.10.0\css\bootstrap-select.css">
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/bootstrap-select-1.10.0/js/bootstrap-select.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body>
		<div id="header">
			<img id="headLogo" src="media/rit_black_no_bar.gif" />
			<h1 class="headerText">Faculty Directory</h1>
			<h5 class="headerText">Admin Portal</h5>
			<a href="addDepartment.php">Add Department</a>
			<a href="addRoom.php">Add Room</a>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addFaculty" name="addFaculty" enctype="multipart/form-data" action="../database/newFaculty.php" method="POST" onsubmit="removeOnlyBullets('highlights'); removeOnlyBullets('education')">
					<div class="col-lg-2" id="searchCol">
						<h3>Search</h3>
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="filter" placeholder="filter by name">
							</div>
						</div>
						<br />
						<div class="form-group">
								<ul multiple class="form-control" id="results">
									<?php getAllEmps() ?>
								</ul>
							<br />
							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary">
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<br /><br />
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>
					</div>
					<div class="col-lg-10">
						<fieldset>
							<legend>ADD A NEW EMPLOYEE</legend>
							<div class="col-lg-6" id="leftCol">
								<div class="form-group">
									<div class="col-lg-4">
										<label for="image" class="control-label" style="float: right">Avatar (160 x 160)</label>
										<br />
										<br />
										<input type="file" accept="image/*" onchange="loadFile(event)" name="image" value="Upload Image" style="width:95px; float: right">
									</div>
									<div class="col-lg-4">
										<img id="userImage" src="media/no-preview.png" />
									</div>
								</div>
								
								 <input type="hidden" id="facultyId" name="facultyId">

								<div class="form-group">
									<label for="firstName" class="col-lg-4 control-label">First Name</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="firstName" name="firstName">
									</div>
								</div>

								<div class="form-group">
									<label for="lastName" class="col-lg-4 control-label">Last Name</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="lastName" name="lastName">
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="col-lg-4 control-label">Email</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="email" name="email">
									</div>
								</div>

								<div class="form-group">
									<label for="phone" class="col-lg-4 control-label">Phone Number</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="phone" name="phone">
									</div>
								</div>

								<div class="form-group">
									<label for="room" class="col-lg-4 control-label">Room Number</label>
									<div class="col-lg-6">
										<select class="selectpicker" data-live-search="true" id="room" name="room">
											<?php getAllRooms() ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="dept" class="col-lg-4 control-label">Department</label>
									<div class="col-lg-6">
										<select class="selectpicker" multiple data-max-options="2" id="dept" name="dept[]" data-width="fit">
											<?php getAllDepartments() ?>
										</select>
									</div>

								</div>
							</div>

							<div class="col-lg-6" id="rightCol">
								<div class="radios">
									<div class="form-group col-lg-6">
										<label class="col-lg-4 control-label">Active</label>
										<div class="col-lg-8">
											<div class="radio">
		 										<label>
													<input type="radio" name="active" id="activeYes" value="activeYes" checked="">
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
													<input type="radio" name="faculty" id="facultyYes" value="facYes" checked="">
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
										<textarea class="form-control" rows="3" id="about" name="about"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">Education</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="3" id="education" name="education"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">Highlights</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="3" id="highlights" name="highlights"></textarea>
									</div>
								</div>
							</div>
						</fieldset>
						<!--<input type="submit" name="new" value="new" id="hiddenNew" style="visibility:hidden">
						<input type="submit" name="edit" value="edit" id="hiddenEdit" style="visibility:hidden">-->
					</div>
				</form>
			</div>
		</div>
	</body>
</html>