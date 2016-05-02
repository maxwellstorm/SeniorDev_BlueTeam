<?php
	require("../database/data.php");
	require("../database/floorPlan.php");
	require_once("../database/dbException.php");
	require_once("../database/util.php");


	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";
	//END REMOVE

	//Authentication
	if(!$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}

	$database = new data;

	//Handle Form Submission
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try{
			$name = filterString($_POST['fpName']);

			if(isset($_POST['new'])) { //If the user submits a new floor plan
				try{
					$id = $_POST['fpId'];
					$imagePath = uploadImage();

					if(isDuplicateFileOrName($imagePath, $name)) { //Check for duplicate file name or title associated with the image
						$returnMessage = alert("danger", "Please select a different name. That one is already in use.");
					} else if(strlen($imagePath) == 0) { //require the user to upload an image
						$returnMessage = alert("danger", "Please upload an image");
					} else {
						$fp = new floorPlan($database, null);
						$fp->postParams($imagePath, $name);
						$returnMessage = alert("success", "Floor Plan for $name successfully uploaded");
					}
				} catch(dbException $db){
					echo $db->alert();
				}
			} elseif(isset($_POST['delete']) && isset($_POST['fpId'])) { //If the user deletes a floor plan
				$fpId = $_POST['fpId'];

				$fp = new floorPlan($database, $fpId);
				$fp->delete();
				$returnMessage = alert("success", "Floor Plan for $name successfully deleted");
			}
		} catch(dbException $db){
			$returnMessage = $db->alert();
		}
	}

	/**
	 * A function to return a set of <option> tags for all floor plans in the database
	 * @return html_content A set of <option> tags, each containing the floor plan's ID and name
	 */
	function getAllFloorPlans() {
		try{
			$database = new data;

			$fps = $database->getData("SELECT fpId, name FROM floorPlan ORDER BY name ASC;", array());

			foreach($fps as $arr) {
				echo "<option value='" . $arr['fpId'] . "'>" . $arr['name'] ."</option>";
			}
		} catch(dbException $db){
			echo $db->alert();
		}
	}

	/**
	 * A method to check if there is a submission with a duplicate image path or name
	 * @param $imagePath The filepath of the image
	 * @param $name The name given to the image (e.g. "Golisano - 2nd floor")
	 * @return true/false Whether or not a duplicate name exists
	 */
	function isDuplicateFileOrName($imagePath, $name) {
		try{
			$database = new data;

			$match = $database->getData("SELECT fpId FROM floorPlan WHERE imagePath=:imagePath OR name=:name;", array(
				":imagePath"=>$imagePath,
				":name"=>$name
			));

			if(count($match) > 0) { //Return true if at least one ID is returned, indicating that a floor plan in the database already uses that name/image path
				return true;
			} else {
				return false;
			}
		} catch(dbException $db){
			echo $db->alert();
			return false;
		}
	}

	/**
	 * A method to upload an image through the admin form
	 * The method will return the filepath of the uploaded image provided the upload was successful
	 * otherwise, it'll return null, which will trigger an error message
	 * @return $newname The image path to the file
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
				//LOG HERE
				$returnMessage = alert("danger", "Only image files are accepted for upload");
				return null;
			}
		} else { //return null if the file is empty or there's an error
		    //LOG HERE
		    $returnMessage = alert("danger", "An error occured while uploading the image");
		    return null;
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
				<img src="media/rit-logo.png" id="imgRIT" alt="" />
			</div>
		</header>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php if(isset($returnMessage)) { //Placeholder for User Feedback, so it appears here on the page
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
						<a href="addRoom.php">&laquo; Go back to Room</a>
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