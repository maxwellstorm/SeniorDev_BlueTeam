<?php
	//Includes & Requires
	require_once("../database/data.php");
	require_once("../database/employees.php");
	require_once("../database/util.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";
	

	if(!$allowed) { //Check if user is allowed access - redirect if not in DB at all
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}



	/* THIS IS ESSENTIALLY THE CONTENTS OF NEWEMPLOYEE.PHP, IT'LL GET CHANGED BACK WHEN I FIGURE OUT HOW TO CARRY THE RETURN MESSAGE THROUGH THE PAGES*/
	/*$database = new data;

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$depts = $_POST['depts'];
		$primaryDept = getDepartmentId(filterString($depts[0]));
		$secondaryDept = getDepartmentId(filterString($depts[1]));
		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);

		if(isset($_POST['new'])) {
			try {
				if(isDuplicateName($fName, $lName, "Employees")) {
					$returnMessage = alert("danger", "ERROR: Duplicate Entry");
				} else {
					if($accessLevel == 3) {
						postEmployee();
						$returnMessage = alert("success", "Employee successfully created");
					} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
						postEmployee();
						$returnMessage = alert("success", "Employee successfully created");
					} else {
						$returnMessage = alert("danger", "You cannot create Employees outside of your department!");
					}
				}
			} catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){
			try {
				if($accessLevel == 3) {
					putEmployee();
					$returnMessage = alert("success", "Employee successfully updated");
				} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
					putEmployee();
					$returnMessage = alert("success", "Employee successfully updated");
				} else {
					$returnMessage = alert("danger", "You cannot edit Employees outside of your department!");
				}
			} catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['delete']) && isset($_POST['facultyId'])) {
			if($accessLevel == 3) {
				deleteEmployee();
				$returnMessage = alert("success", "Employee successfully deleted");
			} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
				deleteEmployee();
				$returnMessage = alert("success", "Employee successfully deleted");
			} else {
				$returnMessage = alert("danger", "You cannot delete Employees outside of your department!");
			}
		}
	}

	function postEmployee() {
		global $database;

		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$email = $_POST['email']; //VALIDATE EMAIL
		$active = $_POST['active']; //VALIDATE INT
		$faculty = $_POST['faculty']; //VALIDATE INT
		$phone = filterString($_POST['phone']);
		$about = filterString($_POST['about']);
		$education = filterString($_POST['education']);
		$highlights = filterString($_POST['highlights']);
		$roomNum = filterString($_POST['room']);
		$title = filterString($_POST['title']);
		$depts = $_POST['depts'];
		$primaryDept = getDepartmentId(filterString($depts[0]));
		$secondaryDept = getDepartmentId(filterString($depts[1]));

		$employee = new employees($database, null);
		$imagePath = uploadImage($employee);

		$employee->postParams($fName, $lName, $email, $active, $faculty, $phone, $about, $education, $highlights, $primaryDept, $roomNum, $title, $secondaryDept, $imagePath);
	}

	function putEmployee() {
		global $database;

		$id = $_POST['facultyId']; //Validate Int
		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$email = $_POST['email']; //Validate Email
		$active = $_POST['active']; //validate int
		$faculty = $_POST['faculty']; //validate int
		$phone = filterString($_POST['phone']);
		$about = filterString($_POST['about']);
		$education = filterString($_POST['education']);
		$highlights = filterString($_POST['highlights']);
		$roomNum = filterString($_POST['room']);
		$title = filterString($_POST['title']);
		$depts = $_POST['depts'];
		$primaryDept = getDepartmentId(filterString($depts[0]));
		$secondaryDept = getDepartmentId(filterString($depts[1]));

		$employee = new employees($database, $id);
		$employee->fetch();
		$imagePath = uploadImage($employee);

		$employee->putParams($fName,$lName,$email,$active,$faculty,$phone,$about,$education,$highlights,$primaryDept,$roomNum,$title,$secondaryDept, $imagePath);
	}

	function deleteEmployee() {
		global $database;

		$id = $_POST['facultyId']; //VALIDATE INT

		$employee = new employees($database, $id);
		$employee->delete();
	}


	/*
	 * A method to upload an image through the admin form
	 * The method will return the filepath of the uploaded image provided the upload was successful
	 * otherwise, it'll return null, which will trigger an error message
	 */
	/*function uploadImage($emp) {
		if(!empty($_FILES['image']) && $_FILES['image']['error'] == 0) { //If there is a file and there is no error uploading it...
			//check size and type of file
			$filename = basename($_FILES['image']['name']);
			$ext = substr($filename, strrpos($filename, '.') + 1);

			//only accept files and MIMETypes that are images - jpg, jpeg, png, & gif
			if(($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') && ($_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/pjpeg' || $_FILES['image']['type'] == 'image/png' || $_FILES['image']['type'] == 'image/gif')) {
				
				$newname = "./../public/media/userImages/$filename";

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
		} else if(empty($_FILES['image']['type']) && $emp->getImageName() != null) { 
			return $emp->getImageName();
		} else { //return null if the file is empty or there's an error
		    //return null;
		//echo("null or error");
	    }
	}

	function getDepartmentId($deptName) {
		$database = new data;

		$depts = $database->getData("SELECT departmentId, departmentName FROM department", array());

		foreach($depts as $arr) {
			if(strcmp($deptName, $arr['departmentName']) == 0) {
				return $arr['departmentId'];
			}
		}
	}*/
	//END NEWEMPLOYEE.PHP BULLSHIT HACK CODE



	/**
	 * A function to get all employees (either in total or in a given department), and return them as a set of list items for the search column
	 * @param $adminDeptId The department ID of the logged in administrative user
	 * @param $accessLevel The access level of the logged in administrative user
	 * @return HTML_Content A set of <li> that contain information about each faculty member
	 */
	function getAllEmps($adminDeptId, $accessLevel) {
		$database = new data;

		if($accessLevel == 3) { //If the user is an administrator (highest auth level), allow them to see all employees
			$emps = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees ORDER BY lName ASC;", array());
		} else { //If the user is not an administrator, allow them to only see employees who are in their department
			$emps = $database->getData("SELECT fName, lName, roomNumber, facultyId FROM Employees WHERE (departmentId=:deptId OR secondaryDepartmentID=:sdId) ORDER BY lName ASC;", array(
				":deptId"=>$adminDeptId,
				":sdId"=>$adminDeptId
			));
		}

		foreach($emps as $arr) {
			echo "<li onclick='setEmployeeActive(this); disableCreate();'><span class='fId' style='display: none'>" . $arr['facultyId'] . "</span><strong>" . $arr['lName'] . ", " . $arr['fName'] . "</strong><br /><span class='rmNum initialism'>" . $arr['roomNumber'] . "</span><hr /></li>";
		}
	}

	/**
	 * A function to get all rooms and return them as a set of <option>'s
	 * @return HTML_Content A set of <option>'s each containing information about a room
	 */
	function getAllRooms() {
		$database = new data;

		$rooms = $database->getData("SELECT roomNumber FROM room", array());

		foreach($rooms as $arr) {
			echo "<option>" . $arr['roomNumber'] . "</option>";
		}
	}

	/**
	 * A function to get all departments and return them as a set of <option>'s
	 * @return HTML_Content A set of <options>'s each containing information about a department
	 */
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
		<title>Touchscreen Directory - Admin Panel</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/main.css">
		<link rel="stylesheet" type="text/css" media="screen" href="js/bootstrap-select-1.10.0/css/bootstrap-select.css">
		<link rel="stylesheet" type="text/css" media="screen" href="js/formvalidation/css/formValidation.min.css">
		<link rel="icon" href="media/favicon.ico">
		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/bootstrap-select-1.10.0/js/bootstrap-select.js"></script>
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
				<?php if(isset($returnMessage)) {
					echo($returnMessage); 
				} ?>
				<form class="form-horizontal" id="addEmployee" name="addEmployee" enctype="multipart/form-data" action="../database/newEmployee.php" method="POST" onsubmit="removeOnlyBullets('highlights'); removeOnlyBullets('education')">
					<div class="col-lg-2" id="searchCol">
						<h3>SEARCH</h3>
						<div class="form-group">
							<div>
								<input type="text" class="form-control" id="filter" placeholder="Enter a name">
							</div>
						</div>
						<div class="form-group">
							<ul multiple class="form-control" id="results">
								<?php getAllEmps($adminDeptId, $accessLevel) ?>
							</ul>
							<input type="submit" value="Update" name="edit" id="editBtn" class="btn btn-primary" disabled>
							<input type="submit" value="Create New" name="new" id="newBtn" class="btn btn-primary">
							<input type="submit" value="Delete" name="delete" id="deleteBtn" class="btn btn-primary">
						</div>
					</div>
					<div class="col-lg-10">
						<?php displayNav($accessLevel, $givenName) ?>
						<fieldset>
							<legend><h2>ADD A NEW EMPLOYEE</h2></legend>
							<div class="col-lg-5" id="leftCol">
								<div class="form-group">
									<div class="col-lg-4">
										<label for="image" class="control-label" style="float: right">Avatar<br />(160 x 160)</label>
										<br />
										<br />
										<input type="file" accept="image/*" onchange="loadFile(event)" name="image" value="Upload Image" style="float: right">
									</div>
									<div class="col-lg-4">
										<img id="userImage" src="media/no-preview.png" />
									</div>
								</div>
								 <input type="hidden" id="facultyId" name="facultyId">

								<div class="form-group">
									<label for="firstName" class="col-lg-4 control-label">First Name</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="firstName" name="firstName" required>
									</div>
								</div>

								<div class="form-group">
									<label for="lastName" class="col-lg-4 control-label">Last Name</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="lastName" name="lastName" required>
									</div>
								</div>

								<div class="form-group">
									<label for="title" class="col-lg-4 control-label">Title</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="title" name="title">
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="col-lg-4 control-label">Email</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="email" name="email" patten="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}">
									</div>
								</div>

								<div class="form-group">
									<label for="phone" class="col-lg-4 control-label">Phone</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" id="phone" name="phone" onBlur="formatPhoneNum(this);">
									</div>
								</div>

								<div class="form-group">
									<label for="room" class="col-lg-4 control-label">Room #</label>
									<div class="col-lg-6">
										<select class="selectpicker" data-live-search="true" id="room" name="room" required>
											<?php getAllRooms() ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="depts" class="col-lg-4 control-label">Department</label>
									<div class="col-lg-6">
										<select class="selectpicker" multiple data-max-options="2" id="depts" name="depts[]" data-width="fit" required>
											<?php getAllDepartments() ?>
										</select>
									</div>

								</div>
							</div>

							<div class="col-lg-7" id="rightCol">
								<div class="radios">
									<div class="form-group col-lg-6">
										<label class="col-lg-4 control-label">Active</label>
										<div class="col-lg-8">
											<div class="radio">
		 										<label>
													<input type="radio" name="active" id="activeYes" value="1" checked="" required>
													Yes
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="active" id="activeNo" value="0">
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
													<input type="radio" name="faculty" id="facultyYes" value="1" checked="" required>
													Yes
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="faculty" id="facultyNo" value="0">
													No
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">About</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" id="about" name="about"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">Education</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" id="education" name="education"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="textArea" class="col-lg-2 control-label">Highlights</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" id="highlights" name="highlights"></textarea>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
		<script>
			$(document).ready(function(){ //sets tab for this page to active
				$('#empNav').addClass('active');
			});
		</script>
	</body>
</html>