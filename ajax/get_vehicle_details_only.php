<?php

	require_once("../initialize.php");
	
	// convert $_POST global variable to object
	$post = (object)$_POST;
	$vehicle = new Vehicle();
	$trip_ticket = new TripTicket();
	$data = array();

	$vehicle_details = $vehicle->getVehicleDetails($post->vehicle_id);
	$checklist_details = $vehicle->getRecentCheckList($post->vehicle_id,1);


	$trip_ticket_details = $trip_ticket->getCompleteTripTicketDetails($post->trip_ticket_no);

	$orig_vehicle = $trip_ticket_details->vehicle_id;


	if($post->vehicle_id == $orig_vehicle){
		if(!empty($checklist_details)){
			$checklist_details = $vehicle->getChecklistDetails($trip_ticket_details->checklist_id);
			$attachment_list = "";
			$checklist_attachments = $vehicle->getChecklistAttachments($trip_ticket_details->checklist_id);
			foreach($checklist_attachments as $attachment){
				$attachment = (object)$attachment;
				$attachment_list .= "<a href='attachments/$attachment->attachment' target='_blank'><i class='fa fa-paperclip'></i> ". $attachment->attachment . "</a><br/>";
			}
			
			$data = array(
				"cl_id" => $checklist_details->id,
				"cl_fid" => Format::formatChecklistId($checklist_details->id),
				"attachments" => $attachment_list,
				"v_cond" => $checklist_details->description,
				"date_created" => Format::format_date($checklist_details->date_created)
			);
		}
		else {
			$data = array(
					"cl_id" => null,
					"cl_fid" => null,
					"attachments" => null,
					"v_cond" => null,
					"date_created" => null
				);
		}
	}
	else {
		if(!empty($checklist_details)){
			foreach($checklist_details as $checklist){
				$checklist = (object)$checklist;
				$attachment_list = "";
				$checklist_attachments = $vehicle->getChecklistAttachments($checklist->id);
				foreach($checklist_attachments as $attachment){
					$attachment = (object)$attachment;
					$attachment_list .= "<a href='attachments/$attachment->attachment' target='_blank'><i class='fa fa-paperclip'></i> ". $attachment->attachment . "</a><br/>";
				}
				
				$attachment_list = substr($attachment_list,0,strlen($attachment_list)-1);

				$data = array(
					"cl_id" => $checklist->id,
					"cl_fid" => Format::formatChecklistId($checklist->id),
					"attachments" => $attachment_list,
					"v_cond" => $checklist->v_cond,
					"date_created" => Format::format_date($checklist->date_created)
				);
			}
		}
		else {
			$data = array(
					"cl_id" => null,
					"cl_fid" => null,
					"attachments" => null,
					"v_cond" => null,
					"date_created" => null
				);
		}
	}
	echo json_encode(array("vehicle_details"=>$vehicle_details,"checklist"=>$data));
	
	
