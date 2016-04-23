<?php
	require("data.php");
	require("employees.php");
	require("filters.php");

	$database = new data;

	if(isset($_POST['new'])) {
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
		$depts = $_POST['dept'];
		$primaryDept = filterString($depts[0]);
		$secondaryDept = filterString($depts[1]);

		if(strcmp($primaryDept, "Information Sciences & Technology") == 0) {
			$primaryDept = 1;
		} elseif(strcmp($primaryDept, "Interactive Games & Media") == 0) { 
			$primaryDept = 2;
		} elseif(strcmp($primaryDept, "Computing Security") == 0) {
			$primaryDept = 3;
		}

		if(strlen($secondaryDept) > 0) {
			if(strcmp($secondaryDept, "Information Sciences & Technology") == 0) {
				$secondaryDept = 1;
			} elseif(strcmp($secondaryDept, "Interactive Games & Media") == 0) { 
				$secondaryDept = 2;
			} elseif(strcmp($secondaryDept, "Computing Security") == 0) {
				$secondaryDept = 3;
			}
		} else {
			$secondaryDept = null;
		}

		if(strcmp($active, "activeYes") == 0) {
			$active = 1;
		} else {
			$active = 0;
		}

		if(strcmp($faculty, "facYes") == 0) {
			$faculty = 1;
		} else {
			$faculty = 0;
		}

		/*var_dump($_POST);

		echo("<br /><br />");
		echo($fName . "<br />");
		echo($lName . "<br />");
		echo($email . "<br />");
		echo($active . "<br />"); 
		echo($faculty . "<br />"); 
		echo($phone . "<br />");
		echo($about . "<br />");
		echo($education . "<br />");
		echo($highlights . "<br />");
		echo($roomNum . "<br />");
		echo($title . "<br />");
		echo($secDeptId . "<br />");
		echo($dept . "<br />");*/

		$employee = new employees($database, null);
		$imagePath = uploadImage($employee);

		$employee->postParams($fName, $lName, $email, $active, $faculty, $phone, $about, $education, $highlights, $primaryDept, $roomNum, $title, $secondaryDept, $imagePath);
	} elseif(isset($_POST['edit'])){	
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
		$depts = $_POST['dept'];
		$primaryDept = filterString($depts[0]);
		$secondaryDept = filterString($depts[1]);

		if(strcmp($primaryDept, "Information Sciences & Technology") == 0) {
			$primaryDept = 1;
		} elseif(strcmp($primaryDept, "Interactive Games & Media") == 0) { 
			$primaryDept = 2;
		} elseif(strcmp($primaryDept, "Computing Security") == 0) {
			$primaryDept = 3;
		}

		if(strlen($secondaryDept) > 0) {
			if(strcmp($secondaryDept, "Information Sciences & Technology") == 0) {
				$secondaryDept = 1;
			} elseif(strcmp($secondaryDept, "Interactive Games & Media") == 0) { 
				$secondaryDept = 2;
			} elseif(strcmp($secondaryDept, "Computing Security") == 0) {
				$secondaryDept = 3;
			}
		} else {
			$secondaryDept = null;
		}

		if(strcmp($active, "activeYes") == 0) {
			$active = 1;
		} else {
			$active = 0;
		}

		if(strcmp($faculty, "facYes") == 0) {
			$faculty = 1;
		} else {
			$faculty = 0;
		}

		$employee = new employees($database, $id);
		$employee->fetch();
		$imagePath = uploadImage($employee);

		$employee->putParams($fName,$lName,$email,$active,$faculty,$phone,$about,$education,$highlights,$primaryDept,$roomNum,$title,$secondaryDept, $imagePath);
	} elseif(isset($_POST['delete']) && isset($_POST['facultyId'])) {
		$id = $_POST['facultyId']; //VALIDATE INT

		$employee = new employees($database, $id);
		$employee->delete();
	}

	header('Location: http://kelvin.ist.rit.edu/~blueteam/public/addprofessor.php');

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
?>