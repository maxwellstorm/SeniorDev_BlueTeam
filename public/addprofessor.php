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
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body>
		<div id="header">
			<img id="headLogo" src="media/rit_black_no_bar.gif" />
			<h1 class="headerText">Faculty Directory</h1>
			<h5 class="headerText">Admin Portal</h5>
		</div>
		<div class="panel panel-default">
			<!--<div class="panel-heading"></div>-->
			<div class="panel-body">
				<form class="form-horizontal">
					<div class="col-lg-2" id="searchCol">
						<h3>Search</h3>
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="filter" placeholder="filter by name">
							</div>
						</div>
						<br />
						<ul multiple class="form-control" id="results">
							<?php getAllEmps() ?>
						</ul>
						<br />

						<form method="POST" action="addProfessor.html">
							<input name="edit" id="editBtn" type="button" value="Update" onclick="">
						</form>
						<input type="button" value="Create New" name="new" id="createNew" class="btn btn-primary" onclick="submitNew('createNew')">
					</div>
					<div class="col-lg-10">
						<form class="form-horizontal" action="newFaculty.php" method="POST">
							<fieldset>
								<legend>ADD A NEW PROFESSOR</legend>
								<div class="col-lg-6" id="leftCol">
									<div class="form-group">
										<div class="col-lg-3">
											<label for="image" class="control-label">Avatar (160 x 160)</label>
											<br />
											<br />
											<input type="file" accept="image/*" onchange="loadFile(event)" value="Upload Image" style="width:95px; float: right">
										</div>
										<div class="col-lg-4">
											<img id="output" src="media/no-preview.png" />
										</div>
									</div>

									<div class="form-group">
										<label for="firstName" class="col-lg-3 control-label">First Name</label>
										<div class="col-lg-4">
											<input type="text" class="form-control" id="firstName">
										</div>
									</div>

									<div class="form-group">
										<label for="lastName" class="col-lg-3 control-label">Last Name</label>
										<div class="col-lg-4">
											<input type="text" class="form-control" id="lastName">
										</div>
									</div>

									<div class="form-group">
										<label for="email" class="col-lg-3 control-label">Email</label>
										<div class="col-lg-4">
											<input type="text" class="form-control" id="email">
										</div>
									</div>

									<div class="form-group">
										<label for="phone" class="col-lg-3 control-label">Phone Number</label>
										<div class="col-lg-4">
											<input type="text" class="form-control" id="phone">
										</div>
									</div>

									<div class="form-group">
										<label for="room" class="col-lg-3 control-label">Room Number</label>
										<div class="col-lg-4">
											<select class="form-control" id="room">
												<?php getAllRooms() ?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="dept" class="col-lg-3 control-label">Department</label>
										<div class="col-lg-4">
											<select class="form-control" id="dept">
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
														<input type="radio" name="actives" id="activeYes" value="activeYes" checked="">
														Yes
													</label>
												</div>
												<div class="radio">
													<label>
														<input type="radio" name="actives" id="activeNo" value="activeNo">
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
														<input type="radio" name="faculties" id="facultyYes" value="facYes" checked="">
														Yes
													</label>
												</div>
												<div class="radio">
													<label>
														<input type="radio" name="faculties" id="facultyNo" value="facNo">
														No
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="textArea" class="col-lg-2 control-label">About</label>
										<div class="col-lg-10">
											<textarea class="form-control" rows="3" id="about"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="textArea" class="col-lg-2 control-label">Education</label>
										<div class="col-lg-10">
											<textarea class="form-control" rows="3" id="education"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="textArea" class="col-lg-2 control-label">Highlights</label>
										<div class="col-lg-10">
											<textarea class="form-control" rows="3" id="highlights"></textarea>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>