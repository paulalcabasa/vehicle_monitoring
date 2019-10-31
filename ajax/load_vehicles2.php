<?php
include("../initialize.php");

$vehicle = new Vehicle();
$encryption = new Encryption();
$post = (object)$_POST;
$etr = $post->etr;
   
$vehicle_list = $vehicle->getVehicles2($etr,$post->is_reserved);
$vehicle_opts = "<option value=''>Select Vehicle</option>"; 

foreach($vehicle_list as $v){
	$v = (object)$v;
	

	$label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
	$label = (trim($label) == "") ? "Plate No/CS No Unavailable" : $label;
	if($v->classification == 2 || $v->classification == 4 || $v->classification == 5){
		/*
			if vehicle class is
			2 = carplan
			4 = executive unit
			5 = executive family unit
			then exempt from pre-requisites
		*/
		if($v->ccu_remarks != ""){
			$remarks = $v->ccu_remarks;
			$vehicle_opts .= "<option value='".$v->vehicle_id."' disabled='disabled'>".$label.'-'.$remarks."</option>";
		}
		else {
			$vehicle_opts .= "<option value=".$v->vehicle_id.">".$label." - " .$v->classification_name."</option>";
		}
	}
	else {
		
		if($v->trip_count <= 0 && $v->checklist_id != ""){
			$enc_id = $encryption->encrypt($v->checklist_id );
			$vehicle_opts .= "<option value=".$v->vehicle_id ." 
								data-checklist_id=".$v->checklist_id."
								data-remarks=".$v->remarks."
								data-enc_id=".$enc_id."
								data-vehicle_condition=".$v->vehicle_condition."
								data-date_created=".$v->date_created."
							  >".$label."</option>";			
		}
		else {
			
			$remarks = "";
		
			if($v->checklist_id == ""){
				$remarks .= " No checklist";
			}
			if($v->trip_count > 0){
				$remarks .= " Has ongoing trip";
			}
			if($remarks != ""){
				$remarks = " - " . $remarks;
			}
			if($v->ccu_remarks != ""){
				$remarks = $v->ccu_remarks;
			}

			
			$vehicle_opts .= "<option value='".$v->id."' disabled='disabled'>".$label.$remarks."</option>";
		}
	}
}

echo $vehicle_opts;
  

                            