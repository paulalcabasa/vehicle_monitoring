<?php
	require_once("../initialize.php");
	$tripticket = new TripTicket();
	$driver = new Driver();
	$post = (object)$_POST;
	$user_data =  (object)$_SESSION['user_data'];
	$trip_ticket_details = $tripticket->getCompleteTripTicketDetails($post->trip_ticket_no);
	$tripticket->updateCheckListStatus($trip_ticket_details->checklist_id,1); // make the changed checklist valid
	$tripticket->updateTripTicket(
		$post->trip_ticket_no,
		$post->jo_no,
		$post->driver_no,
		$post->vehicle_id,
		$post->nature_of_trip_id,
		$post->purpose,
		$post->destination,
		$post->expected_time_return,
		$post->checklist_id,
		$user_data->employee_id,
		$post->requestor,
		$post->start_date,
		$post->end_date
	); 
	$tripticket->updateCheckListStatus($post->checklist_id,2); // make the new checklist end of validity

	// add attendance to manager if Official business
	if($post->nature_of_trip_id == 2){
		// get details of requestor
		$requestor_details = $driver->getEmployeeDetailsById($post->requestor);
		$manager = new Manager();

		$input_start_date = $post->start_date;
		$input_end_date = $post->end_date;
		$employee_id = $requestor_details->id;
		$employee_no = $requestor_details->employee_no;
		$create_user = $user_data->employee_id;
		$manager->removeObAttendance($post->trip_ticket_no,$employee_id,$employee_no);
		$remarks = "Official Business";
		$manager->addObAttendance($employee_id,$employee_no,$input_start_date,$input_end_date,$remarks,$create_user);
	}

	echo "Trip ticket has been successfully updated!";
