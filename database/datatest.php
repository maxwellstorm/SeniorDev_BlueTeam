<?php
require("data.php");
require("department.php");

$testDb = new data; //connects to database in constructor


echo "<br/><br/><strong>select without where stmt example:</strong>";
$res = $testDb->getData("select * from department",array());
printExample($res);


echo "<br/><br/><strong>select with where stmt example:</strong>";
$res = $testDb->getData("select * from department where departmentId = :depId",array("depId"=>1));
printExample($res);


$testDept = new department($testDb,"1");
$testDept->fetch();
echo $testDept->getDeptAbbr();
$testDept->setDeptId("2");
$testDept->fetch();
echo $testDept->getDeptAbbr();


function printExample($resultsArr){
	foreach($resultsArr as $arr){
			echo "<br/> <br/>";
			foreach($arr as $key => $value){
				echo "$key: $value <br/>";
			}
		}
}
?>