<?php
	require_once("../initialize.php");

	$post = (object)$_POST;
	$user_data = (object)$_SESSION['user_data'];
	$errand = new Errand();

	// $employee_details = $driver->getEmployeeDetailsByNo($post->driver_no);

	$vehicle_id = Format::reformatVehicleId($post->vehicle_id);
	$passenger_list = array();
	
	if(!empty($post->passenger_list)){
		$passenger_list = $post->passenger_list;
	}

	$errand->addTimeLog(
	   $post->log_classification_id,
	   $post->log_type,
//	   $employee_details->id,
	   $post->driver_no,
	   $post->driver_name,
	   $vehicle_id,
	   $post->km_reading,
	   $post->fuel_status_id,
	   $user_data->employee_id,
	   $passenger_list,
	   $post->remarks
	);


 

