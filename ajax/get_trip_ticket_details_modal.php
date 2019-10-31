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
	/*echo json_encode(array(
							"trip_ticket_details"=>$trip_ticket_details,
							"trip_ticket_passengers"=>$trip_ticket_passengers,
							"driver_name"=>$driver_name,
							"vehicle_details"=>$vehicle_details,
							"logout_details" => $logout_details,
							"last_log_details" => $last_log_details,
							"approval_status" => $approval_status
						  )
	);*/


?>

	<div class="row">
		<div class="col-sm-4">
		
			<strong>Trip Ticket No</strong>
			<p class="text-bold text-danger">
				<?php echo $trip_ticket_details->id; ?>
			</p>
			
			<strong>Trip Type</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->trip_type; ?>
			</p>

			<strong>Purpose</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->purpose; ?>
			</p>
			
			<strong>Destination</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->destination; ?>
			</p>

			<strong>Expected Time of Return</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->expected_time_of_return; ?>
			</p>

			<strong>Status</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->status; ?>
			</p>

		</div>
		<div class="col-sm-4">
			<strong>Requestor</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->requestor_name; ?>
			</p>

			<strong>Date Requested</strong>
			<p class="text-muted">
				<?php echo $trip_ticket_details->date_requested; ?>
			</p>

			<strong>Driver</strong>
			<p class="text-muted">
				<?php echo $driver_name; ?>
			</p>


			<strong>Passengers</strong>
			<p class="text-muted">
				<ol>
					<?php echo $trip_ticket_passengers;?>
				</ol>
			</p>
			
		</div>

		<div class="col-sm-4">
			<strong>CS No</strong>
			<p class="text-muted">
				<?php echo $vehicle_details->cs_no; ?>
			</p>

			<strong>Plate No</strong>
			<p class="text-muted">
				<?php echo $vehicle_details->plate_no; ?>
			</p>

			<strong>Classification</strong>
			<p class="text-muted">
				<?php echo $vehicle_details->classification; ?>
			</p>


			<strong>Model</strong>
			<p class="text-muted">
				<?php echo $vehicle_details->model;?>
			</p>
			
			<strong>Color</strong>
			<p class="text-muted">
				<?php echo $vehicle_details->body_color;?>
			</p>

		</div>

	</div>
<?php
}
else {
	// trip ticket does no exist
	echo "error";
}