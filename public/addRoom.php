<!DOCTYPE html>
<?php
	require("../database/data.php");
	require("../database/room.php");
	require_once("../database/dbException.php");
	require("../database/util.php");
	require_once("../database/commonAuth.php");


	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";

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
					$desc = filterString($_POST['description']);
					$posX = filterString($_POST['posX']);
					$posY = filterString($_POST['posY']);
					$map = $_POST['imgSrc'];

					$room = new room($database, null);	

					$room->postParams($roomNum, $map, $desc, $posX, $posY);
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
				$desc = filterString($_POST['description']);
				$posX = filterString($_POST['posX']);
				$posY = filterString($_POST['posY']);
				$map = $_POST['imgSrc'];

				$room = new room($database, $roomNum);
				$room->fetch();

				$room->putParams($map, $desc, $posX, $posY);
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

	function getAllFloorplans() {
		$database = new data;

		$fps = $database->getData("SELECT imagePath, name FROM floorPlan;", array());

		foreach($fps as $arr) {
			echo"<option value='" . $arr['imagePath'] . "'>" . $arr['name'] . "</option>";
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
		<script type="text/javascript" src="js/snap-svg-min.js"></script>
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
						<br />
						<a href="addFloorplan.php" id="fpLink">Add a new Floor Plan</a>
						<select class="form-control" id="planSelect">
							<option value="" disabled selected>Select a Floor Plan</option>
							<?php getAllFloorplans() ?>
						</select>
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

							<input type="hidden" id="imgSrc" name="imgSrc" value="./../public/media/floorplans/golisano-2nd-floor-large.png">
							<input type="hidden" id="posX" name="posX">
							<input type="hidden" id="posY" name="posY">
						</fieldset>

						<div class="form-group">
							<label for="floorPlan" class="control-label col-lg-2">Room Map</label>
							<div class="col-lg-10">
								<div id="svgContainer">
									<!--<div style="float: left; background-color: yellow; width: 50px; height:20px;position:absolute;" id="tip">tip</div>-->
									<svg id="floorPlan" width="720" height="536">
									    <image xlink:href="media/floorplans/golisano-2nd-floor-large.png" src="media/floorplans/golisano-2nd-floor-large.png" width="720" height="536"/>
									</svg>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$('#roomNav').addClass('active');
				var s = Snap("#floorPlan");
				$('body').bind('touchstart', function() {}); //makes touchscreen taps behave like hover

				prepMap(s, 5);
			});
			
			/*
			// Creation of red "You are here" dot:
			var youAreHere = s.circle(562, 340, 5);
			var t1 = s.text(523, 355, "You are here");
			youAreHere.attr({
				fill: "red"
			});
			//note that you can set stuff with .css

			$('body').bind('touchstart', function() {}); //makes touchscreen taps behave like hover

			//Hover example
			$('#floorPlan').click(function(e) {
				newCircle.hover( function(){
			        //$("#tip").show();
			        //var xpos = e.pageX - parentOffset.left;
			   		//var ypos = e.pageY - parentOffset.top;                 

			   		var thisBox = this.getBBox();
			   		console.log(thisBox.x);
			   		console.log(thisBox.y);
			        //$("#tip").css("left", thisBox.x );
			        //$("#tip").css("top" , thisBox.y - 25 );
			        
			    }, function(){
			       // $("#tip").hide();
			    });
			}*/
		</script>
	</body>
</html>