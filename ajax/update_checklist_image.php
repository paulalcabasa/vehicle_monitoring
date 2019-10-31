<?php

include("../initialize.php");
$vehicle = new Vehicle();
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
// code for uploading image
$image = imagecreatefrompng($_POST['image']);
$checklist_id = $post->checklist_id;
//$id = uniqid();
imagealphablending($image, false);
imagesavealpha($image, true);
$image_name = 'clist_defect-' . date('YmdHis') . '.png';
imagepng($image, '../images/vehicle_pics/'.$image_name);

$vehicle->update_checklist_image($checklist_id,$image_name,$user_data->employee_id);