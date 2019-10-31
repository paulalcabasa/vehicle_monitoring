<?php
	require_once("../initialize.php");

	$tripticket = new TripTicket();
	$driver = new Driver();
	$approver = new Approver();
	$vehicle = new Vehicle();
	$post = (object)$_POST;
	$user_data =  (object)$_SESSION['user_data'];
	$encryption = new Encryption();

	// approver details
	$approver_details = $driver->getEmployeeDetailsById($post->employee_id);

	//details of trip ticket
	$trip_ticket_details = $tripticket->getCompleteTripTicketDetails($post->trip_ticket_no);

	// get details of requestor
	$requestor_details = $driver->getEmployeeDetailsById($trip_ticket_details->requestor);
	

	// list of passengers
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

	// send mail to the approver
	$emailToApprover = new Email();
 	$emailToApprover->sendMailToApprover(
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
			"requestor_name"		=> Format::format_formal_name(
													$requestor_details->first_name,
													$requestor_details->middle_name,
													$requestor_details->last_name
									),
			"prepared_by"  			=> $trip_ticket_details->prepared_by,
			"enc_trip_ticket_no"	=> $encryption->encrypt($post->trip_ticket_no),
			"approver_id"			=> $post->employee_id,
			"passengers"			=> $trip_ticket_passengers,
			"attachments"           => array()
 		),			
 		$approver_details
 	);



	

	

