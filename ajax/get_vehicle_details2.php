<?php

	require_once("../initialize.php");
	
	// convert $_POST global variable to object
	$post = (object)$_POST;
	$vehicle = new Vehicle();
	if($post->is_reserved == "yes"){
		$vehicle_details = $vehicle->getVehicleDetails($post->vehicle_id);
		$open_trip_list = $vehicle->getOpenTripsBasedOnEtr2($post->vehicle_id);
		$open_trips = "";

		$ctr = 0;
		foreach($open_trip_list as $trip){
			$trip = (object)$trip;
			$open_trips .= "<tr>";
			$open_trips .= "<td style='width:10%;'>" . $trip->trip_ticket_no . "</td>";
			$open_trips .= "<td style='width:50%;'>" . $trip->destination . "</td>";
			$open_trips .= "<td style='width:40%;'>" . Format::format_date($trip->etr) . "</td>";
			$open_trips .= "</tr>";
			$ctr++;
		}

		if($ctr == 0){
			$open_trips = "<tr>
								<td colspan='3' align='center'>No trips reserved yet.</td>
						   </tr>";
		}
		$data = array("vehicle_data"=>$vehicle_details,"open_trips"=>$open_trips);
		echo json_encode($data);
	}
	else if($post->is_reserved == "no"){
		$vehicle_details = $vehicle->getVehicleDetails($post->vehicle_id);
		echo json_encode($vehicle_details);
	}
	
