<?php
include("../initialize.php");
$encryption = new Encryption();
$approver = new Approver();
$driver = new Driver();
$trip_ticket = new TripTicket();
$vehicle = new Vehicle();
$user_data = (object)$_SESSION['user_data'];
// type cast get to be object
$post = (object)$_POST;
$encryption = new Encryption();
// needed variables for approval
$trip_ticket_no = $post->trip_ticket_no;
$approver_id = $post->approver_id;

//count approvers
$approver_count = $approver->countApprovers($trip_ticket_no);

//details of trip ticket
$trip_ticket_details = $trip_ticket->getCompleteTripTicketDetails($trip_ticket_no);
$checklist_attachments = $vehicle->getChecklistAttachments($trip_ticket_details->checklist_id);
$trip_ticket_passengers = "";
$list_of_passengers = $trip_ticket->getTripTicketPassengers($trip_ticket_no);
if(!empty($list_of_passengers)){
	foreach($list_of_passengers as $passenger){
		$passenger = (object)$passenger;
		$trip_ticket_passengers .= "<li>" . $passenger->passenger_name . "</li>";
	}
}
else {
	$trip_ticket_passengers = "<p>None</p>";
}

// details of the requestor
$requestor_details = $driver->getEmployeeDetailsById($trip_ticket_details->requestor);

// Manager of the Requestor
$signatory_details = array();

$signatory_id = $driver->getSignatory($requestor_details->section_id);
if(!empty($signatory_id)){
	$signatory_id = (object)$signatory_id;
	$signatory_details = $driver->getEmployeeDetailsById($signatory_id->signatory);
}

// get sequence no of the approver
$sequence_no = $approver->getApproverSequence($trip_ticket_no,$approver_id);
if($sequence_no == 2){ // if CSS MANAGER
//if($sequence_no == $approver_count){ // it means that this is the last approver
	$trip_ticket->updateTripTicketStatus($trip_ticket_no,1); // 1 means = Open Trip / Refer to Trip Status Table
	$vehicle->updateVehicleStatus($trip_ticket_details->vehicle_id,0); // tag the vehicle as unavailable
}
// check first if the sequence no is less than or equal to the number of approvers
if($sequence_no <= $approver_count){
	
	if($user_data->employee_id == $approver_id){ // the one who approved is the rightful approver
		// approve the trip ticket
		$approver->approveTripTicket($trip_ticket_no,$approver_id);
	}
	else { // someone approved for the approver, approve on behalf of
		$approver->approveOnBehalfOf($trip_ticket_no,$approver_id,$user_data->employee_id);
		$approved_by_details = $driver->getEmployeeDetailsById($user_data->employee_id);
		$approver_details = $driver->getEmployeeDetailsById($approver_id);
		$data = array(
				"trip_ticket_no" 		=> $trip_ticket_no,
				"jo_no"			 		=> $trip_ticket_details->jo_no,
				"plate_no"	     		=> $trip_ticket_details->plate_no,
				"cs_no"			 		=> $trip_ticket_details->cs_no,
				"driver_name"     		=> $trip_ticket_details->driver_name,
				"nature_of_trip"		=> $trip_ticket_details->trip_type,
				"purpose"				=> $trip_ticket_details->purpose,
				"destination"			=> $trip_ticket_details->destination,
				"etr"					=> Format::format_date($trip_ticket_details->expected_time_of_return),
				"approver_email"   		=> $approver_details->email,
				"approver_name"   	 	=> Format::replaceInye(
											Format::format_formal_name(
													$approver_details->first_name,
													$approver_details->middle_name,
													$approver_details->last_name
											)
										),
				"approved_by_email"   	=> $approved_by_details->email,
				"approved_by_name"   	=> Format::makeUpperCase(
												Format::replaceInye(
													Format::format_formal_name(
															$approved_by_details->first_name,
															$approved_by_details->middle_name,
															$approved_by_details->last_name
													)
												)
											),
				"requestor_name"		=> Format::format_formal_name(
														$requestor_details->first_name,
														$requestor_details->middle_name,
														$requestor_details->last_name
										),
				"prepared_by"  			=> $trip_ticket_details->prepared_by,
				"passengers"			=> $trip_ticket_passengers,
				"attachments" 			=> $checklist_attachments
			);

			$emailNotifToApprover = new Email();
			$emailNotifToApprover->sendNotifMailToApprover($data);

	}

	// check first if the approver is not the last
	if( ($sequence_no + 1) <= $approver_count ){
		// next approver
		$next_approver = $approver->getApproverBySequence($trip_ticket_no,($sequence_no+1));
		// approver details
		$approver_details = $driver->getEmployeeDetailsById($next_approver);
		// send mail to the approver
		$emailToApprover = new Email();
	 	$emailToApprover->sendMailToApprover(
	 		array(
				"trip_ticket_no" 		=> $trip_ticket_no,
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
				"enc_trip_ticket_no"	=> $encryption->encrypt($trip_ticket_no),
				"approver_id"			=> $next_approver,
				"passengers"			=> $trip_ticket_passengers,
				"attachments" 			=> $checklist_attachments
	 		),			
	 		$approver_details
	 	);
	}
	else {
		// mail to requestor that the trip ticket has been successfully approved
		if($requestor_details->email != ""){
			$data = array(
				"trip_ticket_no" 		=> $trip_ticket_no,
				"jo_no"			 		=> $trip_ticket_details->jo_no,
				"plate_no"	     		=> $trip_ticket_details->plate_no,
				"cs_no"			 		=> $trip_ticket_details->cs_no,
				"driver_name"     		=> $trip_ticket_details->driver_name,
				"nature_of_trip"		=> $trip_ticket_details->trip_type,
				"purpose"				=> $trip_ticket_details->purpose,
				"destination"			=> $trip_ticket_details->destination,
				"etr"					=> Format::format_date($trip_ticket_details->expected_time_of_return),
				"requestor_name_email"	=> Format::replaceInye(
											Format::format_formal_name(
													$requestor_details->first_name,
													$requestor_details->middle_name,
													$requestor_details->last_name
											)
										),
				"requestor_name"		=> Format::format_formal_name(
													$requestor_details->first_name,
													$requestor_details->middle_name,
													$requestor_details->last_name
											),
				"requestor_email"   	=> $requestor_details->email,
				"signatory_name"   	 	=> Format::replaceInye(
											Format::format_formal_name(
													$signatory_details->first_name,
													$signatory_details->middle_name,
													$signatory_details->last_name
											)
										),
				"signatory_email"   	=> $signatory_details->email,
				"prepared_by"  			=> $trip_ticket_details->prepared_by,
				"passengers"			=> $trip_ticket_passengers,
				"attachments" 			=> $checklist_attachments
			);

			$emailToRequestor = new Email();
			$emailToRequestor->sendMailNotifToRequestor($data);
		}
	}	
}	
