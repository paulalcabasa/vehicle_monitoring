<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$tripticket = new TripTicket();
$vehicle = new Vehicle();
$driver = new Driver();
$email = new Email();

$user_details = $driver->getEmployeeDetailsById($user_data->employee_id);


$tripticket->updateTripTicketStatus(
				$post->trip_ticket_no,
				$post->trip_status_id
			);

$tripticket_details = $tripticket->getTripTicketDetails($post->trip_ticket_no);
if($post->trip_status_id == 2 || $post->trip_status_id == 3){ // if trip is closed or cancelled, tag vehicle as available
	$vehicle->updateVehicleStatus($tripticket_details->vehicle_id,1);
}
else if($post->trip_status_id == 1){ // if trip opened tag vehicle as unavailable
	$vehicle->updateVehicleStatus($tripticket_details->vehicle_id,0);	
}

$trip_ticket_passengers = "";

$list_of_passengers = $tripticket->getTripTicketPassengers($post->trip_ticket_no);

if(!empty($list_of_passengers)){
	foreach($list_of_passengers as $passenger){
		$passenger = (object)$passenger;
		$trip_ticket_passengers .= "<li>" . $passenger->passenger_name . "</li>";
	}
}
else {
	$trip_ticket_passengers = "<p>None</p>";
}

$status = "";
switch($post->trip_status_id){
	case 1:
		$status = "Opened";
	break;
	case 2:
		$status = "Closed";
	break;
	case 3:
		$status = "Canceled";
	break;
	default:
		$status = "Error : No status catched.";
	break;
}
// details of trip ticket
$trip_ticket_details = $tripticket->getCompleteTripTicketDetails($post->trip_ticket_no);
$checklist_attachments = $vehicle->getChecklistAttachments($trip_ticket_details->checklist_id);
// get details of requestor
$requestor_details = $driver->getEmployeeDetailsById($trip_ticket_details->requestor);
$email->sendChangeStatusMail(
	array(
		"trip_ticket_no" 		=> $post->trip_ticket_no,
		"jo_no"			 		=> $trip_ticket_details->jo_no,
		"plate_no"	     		=> $trip_ticket_details->plate_no,
		"cs_no"			 		=> $trip_ticket_details->cs_no,
		"driver_name"     		=> $trip_ticket_details->driver_name,
		"nature_of_trip"		=> $trip_ticket_details->trip_type,
		"purpose"				=> $trip_ticket_details->purpose,
		"destination"			=> $trip_ticket_details->destination,
		"etr"					=> Format::format_date($trip_ticket_details->expected_time_of_return),
		"user_trigger"			=> $user_details->emp_name,
		"user_trigger_email"   	=> $user_details->email,
		"prepared_by"  			=> $trip_ticket_details->prepared_by,
		"passengers"			=> $trip_ticket_passengers,
		"requestor_name"		=> $requestor_details->emp_name,
		"status"				=> $status,
		"attachments" 			=> $checklist_attachments
	)
);