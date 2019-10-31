<?php
class Expats {

	public function addTimeLog($log_classification_id = 4,$log_type_id,$driver_no,$driver_name,$vehicle_id,$create_user,$passenger_list){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO time_log(log_type_id,log_classification_id,driver_no,driver_name,vehicle_id,log_time,create_user)
				VALUES(:log_type_id,:log_classification_id,:driver_no,:driver_name,:vehicle_id,NOW(),:create_user)";
		$time_log_id = $conn->query($sql,array(
										":log_type_id" 		  	 => $log_type_id,
										":log_classification_id" => $log_classification_id,
									//	":employee_id"		     => $employee_id,
										":driver_no" 	  		 => $driver_no,
										":driver_name" 	  		 => $driver_name,
										":vehicle_id" 	  		 => $vehicle_id,
									//	":km_reading" 	  		 => $km_reading,
									//	":fuel_status_id" 		 => $fuel_status_id,
									//	":remarks" 	  	 		 => $remarks,
										":create_user" 	  		 => $create_user
									)
				  );
	
		if(!empty($passenger_list)){
			$this->addLogTimePassengers($time_log_id,$passenger_list);
		}
	} 

	public function addLogTimePassengers($time_log_id,$passenger_list){
		$conn = Connection::getInstance();
		foreach($passenger_list as $passenger){
			$sql = "INSERT INTO time_log_passenger(time_log_id,passenger_name)
					VALUES(:time_log_id,:passenger_name)";
			$result = $conn->query($sql,array(
										":time_log_id" 		=> $time_log_id,
										":passenger_name"	=> $passenger,
									)
				  	  );
		}
	}

	public function updateTimeLog($log_id,$log_classification_id = 4,$log_type_id,$driver_no,$driver_name,$vehicle_id,$update_user){
		$conn = Connection::getInstance();
		$sql = "UPDATE time_log 
				SET log_type_id = :log_type_id,
					driver_no = :driver_no,
					driver_name = :driver_name,
					vehicle_id = :vehicle_id,
					update_user =:update_user,
					date_updated = NOW()
				WHERE id = :log_id";
		$time_log_id = $conn->query($sql,array(
										":log_type_id" 		 => $log_type_id,
										":driver_no" 	  	 => $driver_no,
										":driver_name" 	  	 => $driver_name,
										":vehicle_id" 	  	 => $vehicle_id,
										":update_user" 	  	 => $update_user,
										":log_id" 	  		 => $log_id
									)
				  );
	}

	public function addPassenger($time_log_id,$passenger_name){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO time_log_passenger(time_log_id,passenger_name)
				VALUES(:time_log_id,:passenger_name)";
		$result = $conn->query($sql,array(
									":time_log_id" 		=> $time_log_id,
									":passenger_name"	=> $passenger_name,
								)
			  	  );		
	}

	public function removePassenger($passenger_id){
		$conn = Connection::getInstance();
		$sql = "DELETE FROM time_log_passenger WHERE id = :id";
		$conn->query($sql,array(":id"=>$passenger_id));		
	}
}