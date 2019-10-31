<?php
include("../initialize.php");

$vehicle = new Vehicle();
$post = (object)$_POST;
$etr = $post->etr;
$vehicle_list = $vehicle->getVehicles();


if($post->is_reserved == "yes"){ // if reservation is checked, check on ETR
	$vehicle_opts = "<option value=''>Select Vehicle</option>"; 
	foreach($vehicle_list as $v){
		$v = (object)$v;

		if($v->classification == 2 || $v->classification == 3 || $v->classification == 5){
			/*
				if vehicle class is
				2 = carplan
				3 = executive unit
				5 = executive family unit
				then exempt from pre-requisites
			*/
			$label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
			$vehicle_opts .= "<option value=".$v->id ." data-fid='' data-attachments='' data-id='' data-cond='' data-date_created=''>".$label."</option>";
		}
		else {

			$trip_count = $vehicle->countOnGoingTripBasedOnEtr($v->id,$etr);
			$label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
			$checklist = $vehicle->getRecentCheckList($v->id,1);
			$checklist_ctr = count($checklist);
			if($trip_count == 0 && $checklist_ctr!=0){ // vehicle should be available na
				$checklist_data =  (object)$checklist[0];
				$ckid = Format::formatChecklistId($checklist_data->id);
				$attachment_list = "";
				$checklist_attachments = $vehicle->getChecklistAttachments($checklist_data->id);
				foreach($checklist_attachments as $attachment){
					$attachment = (object)$attachment;
					$attachment_list .= $attachment->attachment . ";";
				}
				$attachment_list = substr($attachment_list,0,strlen($attachment_list)-1);
				$vehicle_opts .= "<option value=".$v->id ." data-fid='".$ckid."' data-attachments='".$attachment_list."' data-id='".$checklist_data->id."' data-cond='".$checklist_data->v_cond."' data-date_created='".Format::format_date($checklist_data->date_created)."'>".$label."</option>";
			}
			else {
				$dis_opt = "";
				if($checklist_ctr == 0){
					$dis_opt = "<option value=" .$v->id . " disabled='disabled'>".$label." - No checklist</option>";
				}
				if($trip_count != 0){
					$dis_opt = "<option value=" .$v->id . " disabled='disabled'>".$label." - Has ongoing trip</option>";
				}
				if($trip_count != 0 && $checklist_ctr == 0){
					$dis_opt = "<option value=" .$v->id . " disabled='disabled'>".$label." - No checklist and has ongoing trip</option>";
				}
				$vehicle_opts .= $dis_opt;
			}
		}
	}
	echo $vehicle_opts;
}    
else if($post->is_reserved == "no"){ // if not reserved, check on Open trips
	$vehicle_opts = "<option value=''>Select Vehicle</option>"; 
	foreach($vehicle_list as $v){
		$v = (object)$v;
		if($v->classification == 2 || $v->classification == 3 || $v->classification == 5){
			/*
				if vehicle class is
				2 = carplan
				3 = executive unit
				5 = executive family unit
				then exempt from pre-requisites
			*/
			$label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
			$vehicle_opts .= "<option value=".$v->id ." data-fid='' data-attachments='' data-id='' data-cond='' data-date_created=''>".$label."</option>";
		}
		else {
			$label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
			$open_trip_count = $vehicle->countOpenTrips($v->id);
		//	$checklist = $vehicle->getRecentCheckList($v->id,1);
		//	$checklist_ctr = count($checklist);
			//if($open_trip_count == 0 && $checklist_ctr!=0){ // vehicle should be available na
			if($open_trip_count == 0 ){
				$checklist_data =  (object)$checklist[0];
				$ckid = Format::formatChecklistId($checklist_data->id);
				$attachment_list = "";
				$checklist_attachments = $vehicle->getChecklistAttachments($checklist_data->id);
				foreach($checklist_attachments as $attachment){
					$attachment = (object)$attachment;
					$attachment_list .= $attachment->attachment . ";";
				}
				$attachment_list = substr($attachment_list,0,strlen($attachment_list)-1);
				$vehicle_opts .= "<option value=".$v->id ." data-fid='".$ckid."' data-attachments='".$attachment_list."' data-id='".$checklist_data->id."' data-cond='".$checklist_data->v_cond."' data-date_created='".Format::format_date($checklist_data->date_created)."'>".$label."</option>";			
			}
			else {
				$dis_opt = "";
				/*if($checklist_ctr == 0){
					$dis_opt = "<option value=" .$v->id . " disabled='disabled'>".$label." - No checklist</option>";
				}*/
				if($open_trip_count != 0){
					$dis_opt = "<option value=" .$v->id . " disabled='disabled'>".$label." - Has ongoing trip</option>";
				}
			/*	if($open_trip_count != 0 && $checklist_ctr == 0){
					$dis_opt = "<option value=" .$v->id . " disabled='disabled'>".$label." - No checklist and has ongoing trip</option>";
				}*/
				$vehicle_opts .= $dis_opt;
			}
		}
	}
	echo $vehicle_opts;
}  

                            