<?php
	require_once("../initialize.php");

	$tripticket = new TripTicket();
	$driver = new Driver();
	$approver = new Approver();
	$vehicle = new Vehicle();
	$post = (object)$_POST;
	$user_data =  (object)$_SESSION['user_data'];
	$encryption = new Encryption();
	// list of approvers
	$list_of_approvers =  Constants::getTripTicketSignatory();
	$first_approver = (object)$list_of_approvers[0];
	$first_approver_details = $driver->getEmployeeDetailsById($first_approver->employee_id);

	// get details of requestor
	$requestor_details = $driver->getEmployeeDetailsById($post->requestor);

	// add approver to approval table
	$approver->addTripTicketApprover($trip_ticket_id,$list_of_approvers);

	//details of trip ticket
	$trip_ticket_details = $tripticket->getCompleteTripTicketDetails($trip_ticket_id);


	$checklist_attachments = $vehicle->getChecklistAttachments($trip_ticket_details->checklist_id);



	// Manager of the Requestor
	$signatory_details = array();
	
	$signatory_id = $driver->getSignatory($requestor_details->section_id);
	if(!empty($signatory_id)){
		$signatory_id = (object)$signatory_id;
		$signatory_details = $driver->getEmployeeDetailsById($signatory_id->signatory);
	}

	if($requestor_details->email != ""){
		
		$data = array(
			"trip_ticket_no" 		=> $trip_ticket_id,
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
			"passengers"			=> $list_of_passengers,
			"attachments" 			=> $checklist_attachments
		);

		$emailToRequestor = new Email();
		$emailToRequestor->sendMailToRequestor($data);
	}
	

	

	
	echo "Trip ticket has been successfully created! <br/><strong>Trip Ticket No. is <u style='font-size:14pt;' class='text-danger'>".$trip_ticket_id."</u></strong>";
