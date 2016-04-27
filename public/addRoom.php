<!DOCTYPE html>
<?php
	require("../database/data.php");
	require("../database/room.php");
	require_once("../database/dbException.php");
	require("../database/filters.php");
	require_once("../database/commonAuth.php");


	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	if(!$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}

	$database = new data;
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['new'])) {
			try{
				if(!doesRoomExist(filterString($_POST['room']))) {
					$roomNum = filterString($_POST['room']);
				$map = "asdasda"; //STILL NEED TO DO MAP
				$desc = filterString($_POST['description']);

				$room = new room($database, null);	
				$room->postParams($roomNum, $map, $desc);
				$returnMessage = alert("success", "Room successfully created");
				} else {
					$returnMessage = alert("danger", "Duplicate Entry!");
				}
			}
		catch(dbException $db){
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){	
			try{
				$roomNum = filterString($_POST['room']);
				$map = "asdas"; //STILL NEED TO DO MAP
				$desc = filterString($_POST['description']);

				$room = new room($database, $roomNum);
				$room->putParams($map, $desc);
				$returnMessage = alert("success", "Room successfully updated");
			}
		catch(dbException $db){
				echo $db->alert();
			}		

		} elseif(isset($_POST['delete']) && isset($_POST['room'])) {
			$roomNum = filterString($_POST['room']);
			
			if(isRoomInUse($roomNum)) {
				$returnMessage = alert("danger", "You can't delete a room that's in use!");
			} else {
				$room = new room($database, $roomNum);
				$room->delete();
				$returnMessage = alert("success", "Room successfully deleted");
			}
		}
	}

	function getAllRooms() {
		$database = new data;

		$rooms = $database->getData("SELECT roomNumber FROM room;", array());

		foreach($rooms as $arr) {
			echo "<option>" . $arr['roomNumber'] . "</option>";
		}
	}

	function isRoomInUse($roomNum) {
		$database = new data;

		$match = $database->getData("SELECT facultyId FROM Employees WHERE roomNumber=:roomNum;", array(
			":roomNum"=>$roomNum
		));

		if(count($match) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function doesRoomExist($roomNum) {
		$database = new data;

		$match = $database->getData("SELECT roomNumber FROM room WHERE roomNumber=:roomNum;", array(
			":roomNum"=>$roomNum
		));

		if(count($match) > 0) {
			return true;
		} else {
			return false;
		}
	}
?>
<html lang="en">
	<head>
		<title>IST Faculty Management Interface - Admin View: Room</title>
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
				<?php if(isset($returnMessage)) {
					echo($returnMessage); 
				} ?>
				<form class="form-horizontal" id="addRoom" name="addRoom" action="addRoom.php" method="POST">
					<div class="col-lg-2 dropdownSelect" id="searchCol">
						<select class="form-control" id="roomSelect">
							<option value="" disabled selected>Select a Room</option>
							<?php getAllRooms() ?>
						</select>
						<br />

						<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
						<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
						<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
					</div>

					<div class="col-lg-10">
						<?php displayNav($accessLevel, $givenName) ?>
						<fieldset>
							<legend><h2>ADD A NEW ROOM</h2></legend>
							<div class="form-group">
								<label for="room" class="col-lg-2 control-label">Room Number</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" id="room" name="room" required pattern="[a-zA-Z0-9]{3} [A-Za-z0-9]{1}\d{3}">
								</div>
							</div>

							<div class="form-group">
								<label for="textArea" class="col-lg-2 control-label">Description</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="3" id="description" name="description"></textarea>
								</div>
							</div>

							<div class="form-group">
								<!--Will be required-->
								<p>Room Image uploading will go here, but idk exactly what form that'll take.</p>
							</div>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$('#roomNav').addClass('active');
			});
		</script>
	</body>
</html>