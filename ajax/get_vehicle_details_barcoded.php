<?php
require_once("../initialize.php");

// convert $_POST global variable to object
$post = (object)$_POST;
$vehicle = new Vehicle();
if(strpos($post->vehicle_id,"IPCCU") === 0 || strpos($post->vehicle_id,"ipccu") === 0){
	$vehicle_id = Format::reformatVehicleId($post->vehicle_id);
	$vehicle_details = $vehicle->getVehicleDetails($vehicle_id);
	$last_log = $vehicle->getLastVehicleLog($vehicle_id);
	echo json_encode(array("vehicle"=>$vehicle_details,"log_details"=>$last_log));
}
else {
	echo "error";
}
