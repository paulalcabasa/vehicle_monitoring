<?php
include("../initialize.php");
$encryption = new Encryption();
$approver = new Approver();
$driver = new Driver();
$trip_ticket = new TripTicket();
$vehicle = new Vehicle();
// message to display
$msg = "";
// image to display
$image = "";
// type cast get to be object
$get = (object)$_GET;
// decrypt ID
$trip_ticket_no = $encryption->decrypt($get->d);
$approver_id = $get->a;

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

if(!$approver->isApproved($trip_ticket_no,$approver_id)){

	// get sequence no of the approver
	$sequence_no = $approver->getApproverSequence($trip_ticket_no,$approver_id);
	if($sequence_no == $approver_count){ // it means that this is the last approver
		$trip_ticket->updateTripTicketStatus($trip_ticket_no,1); // 1 means = Open Trip / Refer to Trip Status Table
		$vehicle->updateVehicleStatus($trip_ticket_details->vehicle_id,0); // tag the vehicle as unavailable
	}
	// check first if the sequence no is less than or equal to the number of approvers
	if($sequence_no <= $approver_count){
		
		// approve the trip ticket
		$approver->approveTripTicket($trip_ticket_no,$approver_id);

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
	$msg = "Trip Ticket has been <br/> successfully approved. Thank you!";
	$image = "../images/approve-success.jpg";
}
else {
	$msg = "It seems that you have already <br/> approved the Trip Ticket.";
	$image = "../images/approve-error.png";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Trip Ticket Approval</title>
<link rel='shortcut icon' type='image/x-icon' href='../../../img/favicon.ico' />
<style>
		body{
			background-color: #ecf0f5;
		}

		.container{
			width: 100%;
			margin-top: 100px;
			font-family: arial;
		}
		
		.center-container{
			width: 500px;
			height: 200px;
			margin: 0 auto;
			padding: 20px;
			background-color: #ffffff;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.125);
		}
		
		.header{
			background-color: #d73925;
			width: 500px;
			height: 20px;
			margin: 0 auto;
			padding: 20px;
			padding-bottom: 10px;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.125);
		}
		
		.header p{
			text-align: center;
			margin: 0;
			padding: 0;
			color: white;
			font-weight: bold;
		}
		
		.col-4{
			width: 20%;
			float: left;
			min-height: 1px;
			padding-left: 0;
			padding-right: 0;
			position: relative;
		
		}
		
		.col-6{
			width: 80%;
			float: right;
			min-height: 1px;
			padding-left: 0;
			padding-right: 0;
			position: relative;
			text-align: center;
			
		}
		
		.text{
			margin-top: 70px;
			margin-right: 100px;
			font-size: 20px;
			color: #777;
			width: 100%;
			text-align: center;
		}
	</style>
</style>
	<body>
		<div class="container">
			<div class="header">
				<p>Vehicle Monitoring System</p>
			</div>
			<div class="center-container">
				<div class="col-4">
					<img src="<?php echo $image;?>" width="150">
				</div>

				<div class="col-6">
					<div class="text"><?php echo $msg;?></div>
				</div>
			</div>
		</div>
	</body>
</html>

