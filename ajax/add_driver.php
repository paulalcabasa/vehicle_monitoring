
<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$driver = new Driver();
$pic = NULL;
$attachment = NULL;
if ($_FILES['txt_pic']['size'] != 0 && $_FILES['txt_pic']['error'] == 0) { 
    $driver_pic = new upload($_FILES['txt_pic']);
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

if ($_FILES['txt_attachment']['size'] != 0 && $_FILES['txt_attachment']['error'] == 0) { 
    $file_name = $_FILES['txt_attachment']['name'];
    $file_size =$_FILES['txt_attachment']['size'];
    $file_tmp =$_FILES['txt_attachment']['tmp_name'];
    $file_type=$_FILES['txt_attachment']['type'];
    $file_ext=explode('.',$_FILES['txt_attachment']['name']);
	$file_ext=end($file_ext);     
	$new_file_name = "att-".date("YmdHis").'.'.$file_ext;
	$attachment  = $new_file_name;
	move_uploaded_file($file_tmp,"../attachments/".$new_file_name);
}


$driver->addDriver($post->txt_first_name,$post->txt_middle_name,$post->txt_last_name,$post->txt_contact_no,$pic,$attachment,$post->txt_company,$post->sel_car_units,$user_data->employee_id);
echo "Successfully added driver!";