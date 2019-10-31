<?php
	require_once("../initialize.php");
	$post = (object)$_POST;
	$user_data = (object)$_SESSION['user_data'];
	$driver = new Driver();
	$manager = new Manager();

	$employee_details = $driver->getEmployeeDetailsByNo($post->employee_no);
	if(!empty($employee_details)){
		$manager->addAttendance(
			$employee_details->id, // employee id
			$post->employee_no, // employee no
			$user_data->employee_id // employee id of user
		);

		if(file_exists("../../../emp_pics/" . $post->employee_no . ".jpg")){
    		$pic = $post->employee_no. ".jpg";
	    }
	    else {
	    	$pic = "anonymous.png";
	    }

		echo json_encode(array("emp_details"=>$employee_details,"emp_pic"=>$pic));
	}
	else {
		echo "error";
	}

	
