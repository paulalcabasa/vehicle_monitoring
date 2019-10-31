<?php

	require_once("../initialize.php");
	
	// convert $_POST global variable to object
	$post = (object)$_POST;
	$vehicle = new Vehicle();
	$carplan_details = $vehicle->getCarPlanDetails($post->employee_no);
	
	if(empty($carplan_details[0])){
		echo "false";
	}
	else {
		echo json_encode($carplan_details[0]);
	}