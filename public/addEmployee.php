<?php
	require_once("../database/data.php");
	require_once("../database/employees.php");
	require_once("../database/util.php");

	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;
	$givenName = "Andy";
	//END REMOVE
	
	//Authentication - The user must have a valid login to access the Employee Page
	if(!$allowed) {
		header("Location: notAuthorized.html");
        die("Redirecting to notAuthorized.html");
	}

	$database = new data;

	//Handling Form Submission
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$depts = $_POST['depts'];
		$primaryDept = getDepartmentId(filterString($depts[0]));
		$secondaryDept = getDepartmentId(filterString($depts[1]));
		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);

		if(isset($_POST['new'])) { //If the user creates a new Employee (clicks the "Create New" button)
			try {
				if(isDuplicateName($fName, $lName, "Employees")) { //Verify that the submitted name is not already in use
					$returnMessage = alert("danger", "$fName $lName already exists as an Employee");
				} else {
					if($accessLevel == 3) { //Allow the user to post if they are a system administrator
						postEmployee();
						$returnMessage = alert("success", "$fName $lName successfully created");
					//Allow the user to post if they are an office staff member or student worker of the same department as the new employee
					} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
						postEmployee();
						$returnMessage = alert("success", "$fName $lName successfully created");
					} else {
						$returnMessage = alert("danger", "You cannot create Employees outside of your department");
					}
				}
			} catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){ //If the user edits an existing employee
			try {
				if($accessLevel == 3) { //Allow editing if the user is a system administrator
					putEmployee();
					$returnMessage = alert("success", "$fName $lName successfully updated");
				//Allow the user to edit if they are an office staff member or student worker of the same department as the employee
				} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
					putEmployee();
					$returnMessage = alert("success", "$fName $lName successfully updated");
				} else {
					$returnMessage = alert("danger", "You cannot edit Employees outside of your department");
				}
			} catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['delete']) && isset($_POST['facultyId'])) { //If the user deletes an existing employee
			if($accessLevel == 3) { //Allow deletion for system administrators
				deleteEmployee();
				$returnMessage = alert("success", "$fName $lName successfully deleted");
			//Allow deletion if the user is an office staff member or student worker of the same department as the employee
			} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
				deleteEmployee();
				$returnMessage = alert("success", "$fName $lName successfully deleted");
			} else {
				$returnMessage = alert("danger", "You cannot delete Employees outside of your department");
			}
		}
	}
	
	/**
	 * A method to create a new employee in the database
	 */
	function postEmployee() {
		global $database;

		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$email = $_POST['email'];
		
		if(is_numeric($_POST['active'])) { //Validate that the active status is numeric (otherwise default to active)
			$active = $_POST['active'];
		} else {
			$active = 1;
		}

		if(is_numeric($_POST['faculty'])) { //Validate that the active status is numeric (otherwise default to faculty)
			$faculty = $_POST['faculty'];
		} else {
			$faculty = 1;
		}

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

	/** 
	 * A method to edit an existing employee in the database
	 */
	function putEmployee() {
		global $database;

		$id = $_POST['facultyId'];
		$fName = filterString($_POST['firstName']);
		$lName = filterString($_POST['lastName']);
		$email = $_POST['email'];

		if(is_numeric($_POST['active'])) { //Validate that the active status is numeric (otherwise default to active)
			$active = $_POST['active'];
		} else {
			$active = 1;
		}

		if(is_numeric($_POST['faculty'])) { //Validate that the active status is numeric (otherwise default to faculty)
			$faculty = $_POST['faculty'];
		} else {
			$faculty = 1;
		}
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

	/**
	 * A method to delete an employee from the database
	 */
	function deleteEmployee() {
		global $database;

		$id = $_POST['facultyId'];

		$employee = new employees($database, $id);
		$employee->delete();
	}


	/**
	 * A method to upload an image through the admin form
	 * The method will return the filepath of the uploaded image provided the upload was successful
	 * otherwise, it'll return null, which will trigger an error message
	 * @param $emp An employee that the image path will be associated with
	 * @return $newname The image path of the file
	 */
	function uploadImage($emp) {
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
				//LOG HERE
				$returnMessage = alert("danger", "Only image files are accepted for upload");
				return null;
			}
		} else if(empty($_FILES['image']['type']) && $emp->getImageName() != null) { //If no image was submitted but the employee already has an associated image, just keep that image path
			return $emp->getImageName();
		} else { //return null if the file is empty or there's an error
		    //LOG HERE
		    $returnMessage = alert("danger", "An error occured while uploading the image");
		    return null;
	    }
	}


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
				<?php if(isset($returnMessage)) { //Placeholder for user feedback, so it appears here on the page
					echo($returnMessage); 
				} ?>
				<form class="form-horizontal" id="addEmployee" name="addEmployee" enctype="multipart/form-data" action="addEmployee.php" method="POST" onsubmit="removeOnlyBullets('highlights'); removeOnlyBullets('education')">
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