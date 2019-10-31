<?php

include("../initialize.php");
$vehicle = new Vehicle();
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
// code for uploading image
$image = imagecreatefrompng($_POST['body_defects_image']);
//$id = uniqid();
imagealphablending($image, false);
imagesavealpha($image, true);
$image_name = 'clist_defect-' . date('YmdHis') . '.png';
imagepng($image, '../images/vehicle_pics/'.$image_name);


$header_params = array(
	":vehicle_id" => $post->vehicle_id,
    ":km_reading_out" => $post->km_reading_out,
    ":km_reading_in" => $post->km_reading_in,
    ":body_defects_image" => $image_name,
    ":remarks" => $post->overall_remarks,
    ":vehicle_condition_id" => $post->overall_vehicle_condition,
    ":create_user" => $user_data->employee_id
);
$vehicle_checklist_id = $vehicle->insert_vehicle_checklist_header($header_params);

foreach($post->checklist_items as $row){
	$row = (object)$row;
	$line_params = array(
		":vehicle_checklist_header_id" => $vehicle_checklist_id,
		":category_id" => $row->category_id,
		":item_id" => $row->item_id,
		":item_status_id" => $row->status,
		":remarks" => $row->remarks,
		":date_repaired" => $row->date_repaired,
		":create_user" => $user_data->employee_id
	);
	$vehicle->insert_vehicle_checklist_lines($line_params);
}

$_SESSION['flash_message'] = "Successfully created checklist! <br/>
	  Checklist number is <strong>" . $vehicle_checklist_id . "</strong>.";