<?php
	//REMOVE THIS FOR FINAL COMMIT -THIS IS ONLY HERE FOR THE DEV ENVIRONEMNT
	$adminDeptId = 1;
	$accessLevel = 3;
	$allowed = true;

	require_once("data.php");
	require_once("employees.php");
	require_once("filters.php");
	require_once("commonAuth.php");

	if(!$allowed) {
		header("Location: ../public/notAuthorized.html");
		die("Redirecting to notAuthorized.html");
	} //put these in an else?

	$database = new data;

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$depts = $_POST['depts'];
		$primaryDept = getDepartmentId(filterString($depts[0]));
		$secondaryDept = getDepartmentId(filterString($depts[1]));

		if(isset($_POST['new'])) {
			try {
				if($accessLevel == 3) {
					postEmployee();
				} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
					postEmployee();
				} else {
					echo("You aren't authorized to do that!");
				}
			} catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['edit'])){
			try {
				if($accessLevel == 3) {
					putEmployee();
				} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
					putEmployee();
				} else {
					//RETURN ERROR MESSAGE
					echo("You aren't authorized to do that!");
				}
			} catch(dbException $db) {
				echo $db->alert();
			}
		} elseif(isset($_POST['delete']) && isset($_POST['facultyId'])) {
			if($accessLevel == 3) {
				deleteEmployee();
			} else if($accessLevel < 3 && ($adminDeptId == $primaryDept || $adminDeptId == $secondaryDept)) {
				deleteEmployee();
			} else {
				//RETURN ERROR MESSAGE
				echo("You aren't authorized to do that!");
			}
		}
	}

	header('Location: ../public/addEmployee.php');
	exit();


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
	}
?>