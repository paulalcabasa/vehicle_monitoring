<?php

include("../initialize.php");
$vehicle = new Vehicle();
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
/*// code for uploading image
$image = imagecreatefrompng($_POST['body_defects_image']);
//$id = uniqid();
imagealphablending($image, false);
imagesavealpha($image, true);
$image_name = 'clist_defect-' . date('YmdHis') . '.png';
imagepng($image, '../images/vehicle_pics/'.$image_name);
*/
$checklist_id = $post->checklist_id;



$vehicle->update_checklist_header(
	$post->km_reading_out,
	$post->km_reading_in,
	$post->overall_remarks,
	$post->overall_vehicle_condition,
	$user_data->employee_id,
	$checklist_id
);


foreach($post->checklist_items as $row){
	$row = (object)$row;

	$vehicle->update_checklist_lines(
		$row->line_id,
		$row->status,
		$row->remarks,
		$row->date_repaired,
		$user_data->employee_id
	);
}

$_SESSION['flash_message'] = "Checklist was successfully updated!.";
sleep(2);