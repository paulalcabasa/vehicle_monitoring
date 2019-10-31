<?php
require_once("../initialize.php");
$post = (object)$_POST;
$driver = new Driver();
$name = "";
$employee_details = $driver->getEmployeeDetailsByNo($post->passenger);
if(!empty($employee_details)){
	$name = $employee_details->first_name ." " . substr($employee_details->middle_name,0,1) . ". " .$employee_details->last_name;
}
if($name != ""){
	echo Format::makeUppercase($name);
}
else {
	echo "error";
}