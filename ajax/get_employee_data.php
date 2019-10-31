<?php
require_once("../initialize.php");
$post = (object)$_POST;
$driver = new Driver();

if(strlen($post->employee_no) == 6){ 
	$driver_details = $driver->getEmployeeDetailsByNo($post->employee_no);
	echo Format::makeUpperCase($driver_details->first_name . " " . substr($driver_details->middle_name,0,1) . ". " . $driver_details->last_name);
}
else {
	echo "error";
}

