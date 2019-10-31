<?php
require_once("../initialize.php");
$post = (object)$_POST;
$driver = new Driver();

if(strpos($post->driver_no,"DRV") === 0 || strpos($post->driver_no,"drv") === 0) {
	$driver_no = Format::reformatDriverId($post->driver_no);
	$driver_details = $driver->getDriverDetails($driver_no);
	echo Format::makeUpperCase($driver_details->first_name . " " . substr($driver_details->middle_name,0,1) . ". " . $driver_details->last_name);
}
// the driver is an employee
else if(strlen($post->driver_no) == 6){ 
	$driver_details = $driver->getEmployeeDetailsByNo($post->driver_no);
	echo Format::makeUpperCase($driver_details->first_name . " " . substr($driver_details->middle_name,0,1) . ". " . $driver_details->last_name);
}

else {
	echo "error";
}

