<?php
	require("../database/data.php");
	require("../database/room.php");

	if(isset($_POST['newDept'])) {
		$database = new data;

		$roomNum = $_POST['name'];
		$map = null;
		$desc = $_POST['description'];

		$room = new room($database, null);
		$room->postParams($roomNum, $img, $desc);
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
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<form class="form-horizontal" id="addRoom" name="addRoom" action="addRoom.php" method="POST">
					<fieldset>
						<legend>ADD A NEW ROOM</legend>
						<div class="col-lg-12">


							<div class="form-group">
								<label for="name" class="col-lg-3 control-label">Room Number</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" id="name" name="name">
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
						<!--<input type="submit" value="Update" name="edit" id="editBtn">-->
						<input type="submit" value="Create New" name="newDept" id="newBtn" class="btn btn-primary">
					</fieldset>
				</form>
			</div>
		</div>
	</body>
</html>