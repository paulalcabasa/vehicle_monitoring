<?php
class Errand {

	public function addTimeLog($log_classification_id = 3,$log_type_id,$driver_no,$driver_name,$vehicle_id,$km_reading,$fuel_status_id,$create_user,$passenger_list,$remarks){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO time_log(log_type_id,log_classification_id,driver_no,driver_name,vehicle_id,km_reading,fuel_status_id,log_time,remarks,create_user)
				VALUES(:log_type_id,:log_classification_id,:driver_no,:driver_name,:vehicle_id,:km_reading,:fuel_status_id,NOW(),:remarks,:create_user)";
		$time_log_id = $conn->query($sql,array(
										":log_type_id" 		  	 => $log_type_id,
										":log_classification_id" => $log_classification_id,
									//	":employee_id"		     => $employee_id,
										":driver_no" 	  		 => $driver_no,
										":driver_name" 	  		 => $driver_name,
										":vehicle_id" 	  		 => $vehicle_id,
										":km_reading" 	  		 => $km_reading,
										":fuel_status_id" 		 => $fuel_status_id,
										":remarks" 	  	 		 => $remarks,
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

	public function getTimeLogDetails($log_id){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
				       a.log_type_id,
				       a.log_classification_id,
				       a.employee_id,
				       a.driver_no,
				       a.vehicle_id,
				       a.km_reading,
				       a.fuel_status_id,
				       a.log_time,
				       a.remarks,
				       a.driver_name,
				       b.cs_no,
				       b.plate_no
				FROM time_log a LEFT JOIN sys_insurance_and_registration.iar_company_car_units b
						ON a.vehicle_id = b.id
				WHERE a.id = :id
				LIMIT 1";
		$result = $conn->query($sql,array(":id"=>$log_id));
		return (object)$result[0];
	}

	public function getPassengersByTimeLogId($time_log_id){
		$conn = Connection::getInstance();
		$sql = "SELECT id,passenger_name FROM time_log_passenger WHERE time_log_id = :time_log_id";
		$result = $conn->query($sql,array(":time_log_id"=>$time_log_id));
		return $result;
	}

	public function updateTimeLog($log_id,$log_classification_id = 3,$log_type_id,$driver_no,$driver_name,$vehicle_id,$km_reading,$fuel_status_id,$update_user,$remarks){
		$conn = Connection::getInstance();
		$sql = "UPDATE time_log 
				SET log_type_id = :log_type_id,
					driver_no = :driver_no,
					driver_name = :driver_name,
					vehicle_id = :vehicle_id,
					km_reading = :km_reading,
					fuel_status_id = :fuel_status_id,
					remarks = :remarks,
					update_user =:update_user,
					date_updated = NOW()
				WHERE id = :log_id";
		$time_log_id = $conn->query($sql,array(
										":log_type_id" 		 => $log_type_id,
										":driver_no" 	  	 => $driver_no,
										":driver_name" 	  	 => $driver_name,
										":vehicle_id" 	  	 => $vehicle_id,
										":km_reading" 	  	 => $km_reading,
										":fuel_status_id" 	 => $fuel_status_id,
										":remarks" 	  	 	 => $remarks,
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