<?php
require("data.php"); 

$database = new Database();

public function insert($facultyId,$fName,$lName,$roomNumber,$email,$officeHours,$departmentID,$isActive,$isFaculty,$phone,$about,$education,$highlights){
	$database->query('INSERT INTO EMPLOYEES (facultyID,fName,lName,roomNumber,email,officeHours,departmentID,isActive,isFaculty,phone,about,education,highlights) values ($facultyId, $fName, $lName, $roomNumber, $email, $officeHours, $departmentID, $isActive, $isFaculty, $phone, $about, $education, $highlights)');
	$database->bind('$facultyId',999);
	$database->bind('$fName','John');
	$database->bind('$lName','Smith');
	$database->bind('$roomNumber',999);
	$database->bind('$email','test@test.com');
	$database->bind('$officeHours','3:00');
	$database->bind('$departmentID',999);
	$database->bind('$isActive',999);
	$database->bind('$isFaculty',999);
	$database->bind('$phone','5858675309');
	$database->bind('about','about');
	$database->bind('education','test');
	$database->bind('highlights','test');

	$database->execute();
	echo $database->lastInsertId();//test successfully inserted
}

public function update($facultyId,$fName,$lName,$roomNumber,$email,$officeHours,$departmentID,$isActive,$isFaculty,$phone,$about,$education,$highlights){
	$database->query('UPDATE EMPLOYEES (facultyID,fName,lName,roomNumber,email,officeHours,departmentID,isActive,isFaculty,phone,about,education,highlights) values ($facultyId, $fName, $lName, $roomNumber, $email, $officeHours, $departmentID, $isActive, $isFaculty, $phone, $about, $education, $highlights)');
	$database->bind('$facultyId',999);
	$database->bind('$fName','John');
	$database->bind('$lName','Smith');
	$database->bind('$roomNumber',999);
	$database->bind('$email','test@test.com');
	$database->bind('$officeHours','3:00');
	$database->bind('$departmentID',999);
	$database->bind('$isActive',999);
	$database->bind('$isFaculty',999);
	$database->bind('$phone','5858675309');
	$database->bind('about','about');
	$database->bind('education','test');
	$database->bind('highlights','test');

	$database->execute();
}
>