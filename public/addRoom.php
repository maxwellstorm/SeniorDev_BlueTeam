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

					$room = new room($database, null);	

					$map = uploadImage($room);

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

				$room = new room($database, $roomNum);
				$room->fetch();
				$map = uploadImage($room);

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

	/*
	 * A method to upload an image through the admin form
	 * The method will return the filepath of the uploaded image provided the upload was successful
	 * otherwise, it'll return null, which will trigger an error message
	 */
	function uploadImage($room) {
		if(!empty($_FILES['image']) && $_FILES['image']['error'] == 0) { //If there is a file and there is no error uploading it...
			//check size and type of file
			$filename = basename($_FILES['image']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);

			//only accept files and MIMETypes that are images - jpg, jpeg, png, & gif
			if(($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') && ($_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/pjpeg' || $_FILES['image']['type'] == 'image/png' || $_FILES['image']['type'] == 'image/gif')) {
				
				$newname = "./../public/media/floorplans/$filename";

				//if the moving of the file is successful
				if(move_uploaded_file($_FILES['image']['tmp_name'], $newname)) {
					chmod($newname, 0644);
				}

				return $newname;
			} else { //return null if it is the wrong file extension
				//alert('danger', "Only image files are accpeted for upload");
				//return null;
				//echo("wrong file extension");
			}
		} else if(empty($_FILES['image']['type']) && $room->getRoomMap() != null) { 
			return $room->getRoomMap();
		} else { //return null if the file is empty or there's an error
		    //return null;
		//echo("null or error");
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
				<form class="form-horizontal" id="addRoom" enctype="multipart/form-data" name="addRoom" action="addRoom.php" method="POST">
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
						<a href="addFloorplan.php">Add a new Floor Plan</a>
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

							<input type="hidden" id="posX" name="posX">
							<input type="hidden" id="posY" name="posY">

							<div class="form-group">
								<label for="floorPlan" class="control-label col-lg-2">Room Map</label>
								<div class="col-lg-10">
									<input type="file" accept="image/*" name="image" value="Upload Image">
									<div>
										<!--<div style="float: left; background-color: yellow; width: 50px; height:20px;position:absolute;" id="tip">tip</div>-->
										<svg id="floorPlan" width="720" height="536">
										    <image xlink:href="media/floorplans/golisano-2nd-floor-large.png" src="media/floorplans/golisano-2nd-floor-large.png" width="720" height="536"/>
										</svg>
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
				$('#roomNav').addClass('active');
				$('#tip').hide();
			});

			var s = Snap("#floorPlan");

			// Creation of red "You are here" dot:
			var youAreHere = s.circle(562, 340, 5);
			var t1 = s.text(523, 355, "You are here");

			youAreHere.attr({
				fill: "red"
			});

			$('body').bind('touchstart', function() {}); //makes touchscreen taps behave like hover

			//Need to update based on whether or not the've clicked in the past
			var clicked = false;
			var newCircle;


			$('#floorPlan').click(function(e) {
				if(!clicked) {
					var parentOffset = $(this).parent().offset(); 

				   var relX = e.pageX - parentOffset.left;
				   var relY = e.pageY - parentOffset.top;

				   console.log(relX);
				   console.log(relY);

				   newCircle = s.circle(relX, relY, 5);
				   newCircle.attr('id', 'officeLocation');
				   $('#posX').val(relX);
				   	$('#posY').val(relY);
				   //Proof of concept for setting CSS - $('#test').css('fill', '#ff0000');
				   

				   var moveFunc = function (dx, dy, posx, posy) {
				   		var parentOffset = $(this).parent().offset(); 

						this.attr( { cx: relX+dx , cy: relY+dy } );
					};

				   newCircle.drag(moveFunc, function() {}, function() {
				   		var thisBox = this.getBBox();
				   		$('#posX').val(thisBox.x);
				   		$('#posY').val(thisBox.y);
				   		//do these need the deltas from the matrix? I don't think so...
				   });
				   clicked = true;


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
				}
			});
		</script>
	</body>
</html>