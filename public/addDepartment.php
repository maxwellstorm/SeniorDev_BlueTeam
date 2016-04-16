<?php
	require("../database/data.php");
	require("../database/department.php");

	if(isset($_POST['newDept'])) {
		$database = new data;

		$name = $_POST['name'];
		$abbr = $_POST['abbr'];

		$dept = new department($database, null);
		$dept->postParams($name, $abbr);
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
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addDepartment" name="addDepartment" action="addDepartment.php" method="POST">
					<fieldset>
						<legend>ADD A NEW DEPARTMENT</legend>
						<div class="col-lg-12">


							<div class="form-group">
								<label for="name" class="col-lg-3 control-label">Department Name</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="name" name="name">
								</div>
							</div>

							<div class="form-group">
								<label for="textArea" class="col-lg-3 control-label">Department Abbreviation</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="abbr" name="abbr">
								</div>
							</div>
						</div>
						<!--<input type="submit" value="Update" name="edit" id="editBtn">-->
						<input type="submit" value="Create New" name="newDept" id="newBtn" class="btn btn-primary">
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>