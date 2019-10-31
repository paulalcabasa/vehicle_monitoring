<?php
	require_once("../initialize.php");

	$tripticket = new TripTicket();
	$driver = new Driver();
	$approver = new Approver();

	$user_data =  (object)$_SESSION['user_data'];
	$encryption = new Encryption();
	// list of approvers
	$list_of_approvers =  Constants::getTripTicketSignatory();
	
	// creation of trip ticket
	$trip_ticket_id = $tripticket->addTripTicket(
		null,
		null,
		null,
		3, // errands because it is emergency trip
		"Emergency Trip",
		null,
		null,
		$user_data->employee_id,
		$user_data->employee_id,
		null
	); 

	// add approver to approval table that is already approved
	$approver->addApprovedTripTicket($trip_ticket_id,$list_of_approvers);

	// open the trip ticket
	$tripticket->updateTripTicketStatus($trip_ticket_id,1);

	//details of trip ticket
	$trip_ticket_details = $tripticket->getCompleteTripTicketDetails($trip_ticket_id);

	//get details of requestor
	$requestor_details = $driver->getEmployeeDetailsById($user_data->employee_id);
	
	// email notif
	$email = new Email();
	$email->sendEmergencyTripMail(
		array(
			"trip_ticket_no" 		=> $trip_ticket_id,
			"jo_no"			 		=> "*** TO FOLLOW ***",
			"plate_no"	     		=> "*** TO FOLLOW ***",
			"cs_no"			 		=> "*** TO FOLLOW ***",
			"driver_name"     		=> "*** TO FOLLOW ***",
			"nature_of_trip"		=> $trip_ticket_details->trip_type,
			"purpose"				=> $trip_ticket_details->purpose,
			"destination"			=> "*** TO FOLLOW ***",
			"etr"					=> "*** TO FOLLOW ***",
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
			"prepared_by"  			=> $trip_ticket_details->prepared_by,
			"passengers"			=> "<p>*** TO FOLLOW ***</p>"
		)
	);


	echo "Trip ticket has been successfully created! <br/><strong>Trip Ticket No. is <u style='font-size:14pt;' class='text-danger'>".$trip_ticket_id."</u></strong>";
