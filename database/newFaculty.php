<?php
	require("data.php");
	require("employees.php");

	$database = new data;

	if(isset($_POST['new'])) {
		$fName = $_POST['firstName'];
		$lName = $_POST['lastName'];
		$email = $_POST['email'];
		$active = $_POST['active'];
		$faculty = $_POST['faculty'];
		$phone = $_POST['phone'];
		$about = $_POST['about'];
		$education = $_POST['education'];
		$highlights = $_POST['highlights'];
		$roomNum = $_POST['room'];
		$title = null; //$_POST[''];
		$depts = $_POST['dept'];
		$primaryDept = $depts[0];
		$secondaryDept = $depts[1];

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

		//still need to add imagePath
		$employee = new employees($database, null);
		$employee->postParams($fName, $lName, $email, $active, $faculty, $phone, $about, $education, $highlights, $primaryDept, $roomNum, $title, $secondaryDept);
		uploadImage();
	} elseif(isset($_POST['edit'])){	
		$id = $_POST['facultyId'];
		$fName = $_POST['firstName'];
		$lName = $_POST['lastName'];
		$email = $_POST['email'];
		$active = $_POST['active'];
		$faculty = $_POST['faculty'];
		$phone = $_POST['phone'];
		$about = $_POST['about'];
		$education = $_POST['education'];
		$highlights = $_POST['highlights'];
		$roomNum = $_POST['room'];
		$title = null; //$_POST[''];
		$depts = $_POST['dept'];
		$primaryDept = $depts[0];
		$secondaryDept = $depts[1];

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

		//still need to add imagePath
		$employee = new employees($database, $id);
		$employee->putParams($fName,$lName,$email,$active,$faculty,$phone,$about,$education,$highlights,$primaryDept,$roomNum,$title,$secondaryDept);
		uploadImage();
	} elseif(isset($_POST['delete']) && isset($_POST['facultyId'])) {
		$id = $_POST['facultyId'];

		$employee = new employees($database, $id);
		$employee->delete();
	}

	//header('Location: http://kelvin.ist.rit.edu/~blueteam/public/addprofessor.php');

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
				
				$newname = "./../public/media/userImages/$filename";

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