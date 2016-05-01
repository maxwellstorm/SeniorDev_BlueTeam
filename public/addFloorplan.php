<?php
	require("../database/data.php");
	require("../database/floorPlan.php");
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
				$id = $_POST['fpId']; //valdate int
				$name = filterString($_POST['fpName']);
				$imagePath = uploadImage();

				if(isDuplicateFile($imagePath)) {
					$returnMessage = alert("danger", "ERROR: Duplicate Image Names Uploaded");
				} else if(strlen($imagePath) == 0) {
					$returnMessage = alert("danger", "Please upload an image");
				} else {
					$fp = new floorPlan($database, null);
					$fp->postParams($imagePath, $name);
					$returnMessage = alert("success", "Floor Plan successfully uploaded");
				}
			}
			catch(dbException $db){
				echo $db->alert();
			}
		} elseif(isset($_POST['delete']) && isset($_POST['fpId'])) {
			$fpId = $_POST['fpId'];

			$fp = new floorPlan($database, $fpId);
			$fp->delete();
			$returnMessage = alert("success", "Floor Plan Image successfully deleted");
		}
	}

	function getAllFloorPlans() {
		$database = new data;

		$fps = $database->getData("SELECT fpId, name FROM floorPlan;", array());

		foreach($fps as $arr) {
			echo "<option value='" . $arr['fpId'] . "'>" . $arr['name'] ."</option>";
		}
	}

	function isDuplicateFile($imagePath) {
		$database = new data;

		$match = $database->getData("SELECT fpId FROM floorPlan WHERE imagePath=:imagePath;", array(
			":imagePath"=>$imagePath
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
	function uploadImage() {
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
				echo("wrong file extension");
			}
		} else { //return null if the file is empty or there's an error
		    //return null;
			echo("null or error");
	    }
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>IST Faculty Management Interface - Admin View: Floor Plan</title>
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
				<form class="form-horizontal" id="addFloorplan" name="addFloorPlan" enctype="multipart/form-data" action="addFloorplan.php" method="POST">
					<div class="col-lg-2 dropdownSelect" id="searchCol">
						<select id="fpSelect" class="form-control">
							<option value="" disabled selected>Select a Floor Plan</option>
							<?php getAllFloorPlans() ?>
						</select>
						<br />

						<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
						<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
					</div>

					<div class="col-lg-10">
						<!--<?php displayNav($accessLevel, $givenName) ?>-->
						<a href="addRoom.php">Go back to Room</a>
						<fieldset>
							<legend><h2>ADD A NEW FLOOR PLAN</h2></legend>
							<input type="hidden" id="fpId" name="fpId">

							<div class="form-group">
								<label for="floorPlan" class="control-label col-lg-2">Floor Plan</label>
								<div class="col-lg-10">
									<input type="file" accept="image/*" name="image" id="fpImagePath" value="Upload Image">
								</div>
							</div>

							<div class="form-group">
								<label for="textArea" class="col-lg-2 control-label">Floor Name</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" id="fpName" name="fpName" placeholder="Golisano - 2nd Floor" required>
								</div>
							</div>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>