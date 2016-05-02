<!DOCTYPE html>
<?php
	require("../database/data.php");
	require("../database/room.php");
	require_once("../database/dbException.php");
	require_once("../database/util.php");


	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";
	//END REMOVE

	//Authentication - The user must have a valid login to access the room page
	if(!$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}

	$database = new data;
	
	//Handle form submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$roomNum = filterString($_POST['room']);

		if(isset($_POST['new'])) { //If the user is creating a new room
			try{
				if(!doesRoomExist(filterString($roomNum))) { //If there are no rooms with the same number (prevent duplicate rooms)
					$desc = filterString($_POST['description']);
					$posX = filterString($_POST['posX']);
					$posY = filterString($_POST['posY']);
					$map = $_POST['imgSrc'];

					$room = new room($database, null);	

					$room->postParams($roomNum, $map, $desc, $posX, $posY);
					$returnMessage = alert("success", "$roomNum successfully created");
				} else {
					$returnMessage = alert("danger", "$roomNum already exists as a room");
				}
			}
		catch(dbException $db){
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){ //If the user is editing an existing room
			try{
				$desc = filterString($_POST['description']);
				$posX = filterString($_POST['posX']);
				$posY = filterString($_POST['posY']);
				$map = $_POST['imgSrc'];

				$room = new room($database, $roomNum);
				$room->fetch();

				$room->putParams($map, $desc, $posX, $posY);
				$returnMessage = alert("success", "$roomNum successfully updated");
			}
		catch(dbException $db){
				echo $db->alert();
			}		

		} elseif(isset($_POST['delete']) && isset($_POST['room'])) { //If the user is deleting a room
			
			if(isRoomInUse($roomNum)) { //A room cannot be deleted if it has occupants
				//Get the occupants of the room to display in the error message
				$occupants = getOccupants($roomNum);
				$returnMessage = alert("danger", "You can't delete a room that's in use<br />The following Employees are assigned to this room: $occupants");
			} else {
				$room = new room($database, $roomNum);
				$room->delete();
				$returnMessage = alert("success", "$roomNum successfully deleted");
			}
		}
	}

	/**
	 * A function to check if a given room has any occupants
	 * @param $roomNum The number of the room we're checking
	 * @return true/false Whether or not a room has occupants
	 */
	function isRoomInUse($roomNum) {
		$database = new data;

		$match = $database->getData("SELECT facultyId FROM Employees WHERE roomNumber=:roomNum;", array(
			":roomNum"=>$roomNum
		));

		if(count($match) > 0) { //If any ID's are returned from the query, then there are occupants in the room
			return true;
		} else {
			return false;
		}
	}

	/**
	 * A function to list the occupants of a given room
	 * @param $roomNum The number of the room who's occupants we're getting
	 * @return $returnString The list of occupants
	 */
	function getOccupants($roomNum) {
		$database = new data;

		$occupants = $database->getData("SELECT fName, lName FROM Employees WHERE roomNumber=:roomNum;", array(
			":roomNum"=>$roomNum
		));

		$rawString = "";

		foreach($occupants as $arr) { //Append each name to a string in the form "[first name] [last name], "
			$rawString .= $arr['fName'] . " " . $arr['lName'] . ", ";
		}

		if(strlen($rawString) > 3) { //If there are any names on the list, remove the ending comma
			$returnString = substr($rawString, 0, -2);
		} else {
			$returnString = $rawString;
		}

		return $returnString;
	}

	/**
	 * A function to determine whether or not a room exists in the database
	 * @param $roomNum The number of the room we are checking for
	 * @return true/false Whether or not the room exists
	 */
	function doesRoomExist($roomNum) {
		$database = new data;

		$match = $database->getData("SELECT roomNumber FROM room WHERE roomNumber=:roomNum;", array(
			":roomNum"=>$roomNum
		));

		if(count($match) > 0) { //If a number is returned from the query, the room exists
			return true;
		} else {
			return false;
		}
	}

	/**
	 * A method to return a set of <option> tags containing each floor plan
	 * @return html_content A set of <option> tags, each containing the image path and name of a floor plan
	 */
	function getAllFloorplans() {
		$database = new data;

		$fps = $database->getData("SELECT imagePath, name FROM floorPlan ORDER BY name ASC;", array());

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
				<img src="media/rit-logo.png" id="imgRIT" alt="" />
			</div>
		</header>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if(isset($returnMessage)) { //Placeholder for user feedback, so it appears here on the page
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
								<span><em>Annotate the map by clicking &amp; dragging where you want to place a location marker</em></span>
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

				prepMap(s, 5); //prepare the map for SVG annotation
			});
		</script>
	</body>
</html>