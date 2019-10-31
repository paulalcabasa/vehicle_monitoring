<?php
class Epass {

	public function getEpassList(){
		$conn = Connection::getInstance();
		$sql = "SELECT * FROM e_pass_master";
		$result = $conn->query($sql,false);
		return $result;
	}

	
	
	public function getEpassMonitoringReport($start_date,$end_date){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
				       a.trip_ticket_no,
				       a.e_pass_id,
				       a.vehicle_id,
				       b.plate_no,
				       b.cs_no,
				       GetDriverName(a.driver_no) driver_name,
				       a.log_time,
				       GetLogType(a.log_type_id) log_type,
				       c.classification      
				FROM time_log a
				INNER JOIN sys_insurance_and_registration.iar_company_car_units b 
				ON a.vehicle_id = b.id
				INNER JOIN log_classification c ON c.id = a.log_classification_id
				WHERE a.e_pass_id IS NOT NULL AND (log_time BETWEEN :start_date AND :end_date)";
		$result = $conn->query($sql,array(":start_date"=>$start_date,":end_date"=>$end_date));
		return $result;
	}

}