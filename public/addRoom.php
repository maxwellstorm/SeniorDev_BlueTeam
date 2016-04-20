<?php
	require("../database/data.php");
	require("../database/room.php");

	$database = new data;

	if(isset($_POST['new'])) {

		$roomNum = $_POST['roomNum'];
		$map = null;
		$desc = $_POST['description'];

		$room = new room($database, null);
		$room->postParams($roomNum, $img, $desc);
	} elseif(isset($_POST['edit'])){	

		//Still needs to be done

	} elseif(isset($_POST['delete']) && isset($_POST['roomNum'])) {
		$roomNum = $_POST['roomNum'];

		$room = new room($database, $roomNum);
		$room->delete();
	}

	function getAllRooms() {
		$database = new data;

		$rooms = $database->getData("SELECT roomNumber FROM room", array());

		foreach($rooms as $arr) {
			echo "<option>" . $arr['roomNumber'] . "</option>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>IST Faculty Management Interface - Admin View: Room</title>
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
			<h5 class="headerText">Admin Portal - Room</h5>
			<a href="addprofessor.php">Add Employee</a>
			<a href="addDepartment.php">Add Department</a>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addRoom" name="addRoom" action="addRoom.php" method="POST">
					<fieldset>
						<legend>ADD A NEW ROOM</legend>
						<div class="col-lg-2" id="searchCol">
							<select class="form-control">
								<?php getAllRooms() ?>
							</select>
							<br />

							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary">
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>

						<div class="col-lg-10">
							<div class="form-group">
								<label for="name" class="col-lg-2 control-label">Room Number</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" id="roomNum" name="roomNum">
								</div>
							</div>

							<div class="form-group">
								<label for="textArea" class="col-lg-2 control-label">Description</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="3" id="description" name="description"></textarea>
								</div>
							</div>

							<div class="form-group">
								<p>Room Image uploading will go here, but idk exactly what form that'll take.</p>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>