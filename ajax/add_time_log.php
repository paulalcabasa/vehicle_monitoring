<?php
	require_once("../initialize.php");
	$tripticket = new TripTicket();
	$post = (object)$_POST;
	$user_data = (object)$_SESSION['user_data'];
	$driver = new Driver();

	$fleet_card_no = ($post->fleet_card_no != "" ? $post->fleet_card_no : NULL);
	$e_pass_no = ($post->e_pass_no != "" ? $post->e_pass_no : NULL);
	$vehicle_id = Format::reformatVehicleId($post->vehicle_id);
	$passenger_list = array();
	
	if(!empty($post->passenger_list)){
		$passenger_list = $post->passenger_list;
	}

	// if company car
	if($post->log_classification_id == 1){
		$tripticket->addCompanyCarLog(
		   $post->log_classification_id,
		   $post->trip_ticket_no,
		   $post->log_type,
		   $post->driver_no,
		   $post->driver_name,
		   $vehicle_id,
		   $post->km_reading,
		   $post->fuel_status_id,
		   $user_data->employee_id,
		   $passenger_list,
		   $e_pass_no,
		   $fleet_card_no,
		   $post->remarks
		);
	}
	// if car plan
	else if($post->log_classification_id == 2){
		

		$record_as_attendance = $post->record_as_attendance == "true" ? true : false;

		$employee_details = $driver->getEmployeeDetailsByNo($post->driver_no);
		
		$tripticket->addCarPlanLog(
		   $post->log_classification_id,
		   $post->log_type,
		   $employee_details->id,
		   $post->driver_no,
		   $post->driver_name,
		   $vehicle_id,
		   $post->km_reading,
		   $post->fuel_status_id,
		   $user_data->employee_id,
		   $passenger_list,
		   $e_pass_no,
		   $fleet_card_no,
		   $post->remarks
		);

		if($record_as_attendance){ // if recording of attendance in selected by the guard
			$manager = new Manager();
			$manager->addAttendance(
					$employee_details->id, // employee id
					$post->driver_no, // employee no
					$user_data->employee_id // employee id of user
			);
		}	

	}
	echo "Log successfully saved!";