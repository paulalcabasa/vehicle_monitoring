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

	// creation of trip ticket
	$trip_ticket_id = $tripticket->addTripTicket(
		$post->jo_no,
		$post->driver_no,
		$post->vehicle_id,
		$post->nature_of_trip_id,
		$post->purpose,
		$post->destination,
		$post->expected_time_return,
		$user_data->employee_id,
		$post->requestor,
		$post->checklist_id,
		$post->start_date,
		$post->end_date
	); 

	// add attendance to manager if Official business
	if($post->nature_of_trip_id == 2){
		$manager = new Manager();
		$input_start_date = $post->start_date;
		$input_end_date = $post->end_date;
		$employee_id = $requestor_details->id;
		$employee_no = $requestor_details->employee_no;
		$create_user = $user_data->employee_id;
		$remarks = "Official Business";
		$manager->addObAttendance($employee_id,$employee_no,$input_start_date,$input_end_date,$remarks,$create_user,$trip_ticket_id);
	}

	$vehicle->updateVehicleStatus($post->vehicle_id,0); // tag the vehicle as unavailable

	$list_of_passengers = "";
	// adding of passengers in the trip ticket
	if(!empty($post->passenger_list)){
		$tripticket->addPassengerToTripTicket($trip_ticket_id,$post->passenger_list);
		foreach($post->passenger_list as $passenger){
			$list_of_passengers .= "<li>" . $passenger . "</li>";
		}
	}
	else {
		$list_of_passengers = "<p>None</p>";
	}

	// add approver to approval table
	$approver->addTripTicketApprover($trip_ticket_id,$list_of_approvers);

	//details of trip ticket
	$trip_ticket_details = $tripticket->getCompleteTripTicketDetails($trip_ticket_id);


	$checklist_attachments = $vehicle->getChecklistAttachments($trip_ticket_details->checklist_id);

	// send mail to the approver
	$emailToApprover = new Email();
 	$emailToApprover->sendMailToApprover(
 		array(
			"trip_ticket_no" 		=> $trip_ticket_id,
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
			"enc_trip_ticket_no"	=> $encryption->encrypt($trip_ticket_id),
			"approver_id"			=> $first_approver->employee_id,
			"passengers"			=> $list_of_passengers,
			"attachments" 			=> $checklist_attachments
 		),			
 		$first_approver_details
 	);
	

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
