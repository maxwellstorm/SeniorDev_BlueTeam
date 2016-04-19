<?php
require_once("data.php");
require_once("admin.php");
require_once("department.php");
require_once("employees.php");
require_once("room.php");
require_once("util.php");

util::checkRoom("GOL-1234");

/*$testDb = new data; //connects to database in constructor/*

echo "<br/><br/><strong>select without where stmt example:Admin</strong>";
//$res = $testDb->getData("select * from Admin",array());

$testEmp = new employees($testDb,1);
$testEmp->putParams("test","dsaf","test@test.com",1,"sadsa","sadsa","sadsa","sadsa","sadsa","","sadsa","sadsa","sadsa");

//printExample($res);

*/
/*
echo "<br/><br/><strong>select without where stmt example:Employee</strong>";
$res = $testDb->getData("select * from Employees",array());
printExample($res);

echo "<br/><br/><strong>select without where stmt example:Room</strong>";
$res = $testDb->getData("select * from room",array());
printExample($res);

echo "<br/><br/><strong>select without where stmt examplee:Admin</strong>";
$res = $testDb->getData("select * from admin",array());
printExample($res);
*/

/*
echo "<br/><br/><strong>select with where stmt example:</strong>";
$res = $testDb->getData("select * from department where departmentId = :depId",array("depId"=>3));
printExample($res);

echo "<br/><br/><strong>select with where stmt example:</strong>";
$res = $testDb->getData("select * from Employees where facultyId = :facId",array("facId"=>3));
printExample($res);

echo "<br/><br/><strong>select with where stmt example:</strong>";
$res = $testDb->getData("select * from room where roomNumber = :roomNum",array("roomNum"=>"EAS 1327"));
printExample($res);

$testDept = new department($testDb,"3");
$testDept->fetch();
echo $testDept->getDeptAbbr();
$testDept->setDeptId("2");
$testDept->fetch();
echo $testDept->getDeptAbbr();
echo "<br/><br/> insert test <br/><br/>";
$testDb->setData("insert into department (departmentId,departmentName,departmentAbbr) values (:id,:name,:abbr)",array(
	":id"=>"3",
	":name"=>"insertTest",
	":abbr"=>"te3"
));
*/

function printExample($resultsArr){
	foreach($resultsArr as $arr){
			echo "<br/> <br/>";
			foreach($arr as $key => $value){
				echo "$key: $value <br/>";
			}
		}
}
?>