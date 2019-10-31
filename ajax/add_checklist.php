<?php
include("../initialize.php");
$vehicle = new Vehicle();
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
/*if ($_FILES['file_checklist_attachment']['size'] != 0 && $_FILES['file_checklist_attachment']['error'] == 0) { 
    $file_name = $_FILES['file_checklist_attachment']['name'];
    $file_size =$_FILES['file_checklist_attachment']['size'];
    $file_tmp =$_FILES['file_checklist_attachment']['tmp_name'];
    $file_type=$_FILES['file_checklist_attachment']['type'];
    $file_ext=explode('.',$_FILES['file_checklist_attachment']['name']);
	$file_ext=end($file_ext);     
	$new_file_name = "checklist-".date("YmdHis").'.'.$file_ext;
	$attachment  = $new_file_name;
	move_uploaded_file($file_tmp,"../attachments/".$new_file_name);
}*/
$checklist_id = $vehicle->addVehicleCheckList($post->txt_vehicle_id,$post->vehicle_condition,$user_data->employee_id);
 if(isset($_FILES['file_checklist_attachment'])){
		// check for errors in the file

 		echo "<ul>";
		foreach($_FILES['file_checklist_attachment']['tmp_name'] as $key => $tmp_name ){
			$file_name = $_FILES['file_checklist_attachment']['name'][$key];
			$file_size =$_FILES['file_checklist_attachment']['size'][$key];
			$file_tmp =$_FILES['file_checklist_attachment']['tmp_name'][$key];
			$file_type=$_FILES['file_checklist_attachment']['type'][$key];  

			$file_ext = explode(".",$file_name);
			$file_ext = end($file_ext);
			$new_file_name = "checklist-" .date('YmdHis'). ".". $file_ext;

			if(move_uploaded_file($file_tmp,"../attachments/".$new_file_name)){
				//$gatepass->saveAttachments($gatepass_id,$file_name,$new_file_name);
				$vehicle->addVehicleChecklistAttachment($checklist_id,$new_file_name);
				echo "<li>".$file_name . " has been uploaded.</li>";
			} 
			else {
				echo  "<li>Failed uploading".$file_name . ".</li>";
			}		
		}
		echo "</ul>";
}

