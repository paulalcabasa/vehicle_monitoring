<?php
require_once("../initialize.php");
$post = (object)$_POST;
$trip_ticket = new TripTicket();
$driver = new Driver();
$vehicle = new Vehicle();
$approver = new Approver();
$trip_ticket_passengers = "* * * NONE * * *";
// details of driver
$driver_details = array();
// details of trip ticket
$trip_ticket_details = $trip_ticket->getTripTicketDetails($post->trip_ticket_id);
if(!empty($trip_ticket_details)){
	if($trip_ticket_details->expected_time_of_return!=""){
		$trip_ticket_details->expected_time_of_return = Format::format_date($trip_ticket_details->expected_time_of_return);
	}
	$passenger_list = $trip_ticket->getTripTicketPassengers($post->trip_ticket_id);
	if(!empty($passenger_list)){
		$trip_ticket_passengers = "";
		foreach($passenger_list as $passenger){
			$passenger = (object)$passenger;
			$trip_ticket_passengers .= "<li>" . $passenger->passenger_name . "</li>";
		}
	}
	$trip_ticket_details->date_requested = Format::format_date($trip_ticket_details->date_requested);
	$last_trip_log = $trip_ticket->getLastTripLog($post->trip_ticket_id);
	$last_log_details = array();
	if(!empty($last_trip_log)){
		$last_log_details = $last_trip_log[0];
	}
	else {
		$last_log_details = "none";
	}
	// details of vehicle
	$vehicle_details = array();
	if($trip_ticket_details->vehicle_id!=""){
		$vehicle_details = $vehicle->getVehicleDetails($trip_ticket_details->vehicle_id);
	}	
	// if the driver_no has DRV then it is a driver
	$driver_name = $driver->getDriverName($trip_ticket_details->driver_no);

	$logout_details = $trip_ticket->getTripTicketFirstLogout($post->trip_ticket_id);
	$approval_status = $approver->checkApproval($post->trip_ticket_id);
	echo json_encode(array(
							"trip_ticket_details"=>$trip_ticket_details,
							"trip_ticket_passengers"=>$trip_ticket_passengers,
							"driver_name"=>$driver_name,
							"vehicle_details"=>$vehicle_details,
							"logout_details" => $logout_details,
							"last_log_details" => $last_log_details,
							"approval_status" => $approval_status
						  )
	);
}
else {
	// trip ticket does no exist
	echo "error";
}