<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$driver = new Driver();

$driver_info = (object)$driver->getDriverInfo($post->driver_id);
$attachment = $driver_info->attachment;
$pic = $driver_info->picture;

if ($_FILES['u_txt_pic']['size'] != 0 && $_FILES['u_txt_pic']['error'] == 0) { 
    unlink("../images/driver_pics/".$driver_info->picture);
    $driver_pic = new upload($_FILES['u_txt_pic']);
	if($driver_pic->uploaded){
		$driver_pic->file_new_name_body = 'drv_' . date("YmdHis");
		$driver_pic->image_resize 		 = true;
		$driver_pic->image_y 			 = 512;
		$driver_pic->image_x 		     = 512;
		$driver_pic->process("../images/driver_pics/");
		if($driver_pic->processed){
			$pic = $driver_pic->file_dst_name;
			$driver_pic->clean();
		} else {
			echo "Error: " . $driver_pic->error;
		}
	}  
}	

if ($_FILES['u_txt_attachment']['size'] != 0 && $_FILES['u_txt_attachment']['error'] == 0) { 
    $file_name = $_FILES['u_txt_attachment']['name'];
    $file_size =$_FILES['u_txt_attachment']['size'];
    $file_tmp =$_FILES['u_txt_attachment']['tmp_name'];
    $file_type=$_FILES['u_txt_attachment']['type'];
    $file_ext=explode('.',$_FILES['u_txt_attachment']['name']);
	$file_ext=end($file_ext);     
	$new_file_name = "att-".date("YmdHis").'.'.$file_ext;
	$attachment  = $new_file_name;
	unlink("../attachments/".$driver_info->attachment);
	move_uploaded_file($file_tmp,"../attachments/".$new_file_name);
}

$driver->updateDriverInfo(
		$post->driver_id,
		$post->u_txt_first_name,
		$post->u_txt_middle_name,
		$post->u_txt_last_name,
		$post->u_txt_contact_no,
		$pic,
		$attachment,
		$post->u_txt_company,
		$post->u_sel_car_units,
		$user_data->employee_id
);
echo "Successfully updated driver!";