<?php
class FleetCard {

	public function getFleetCardList(){
		$conn = Connection::getInstance();
		$sql = "SELECT id,fleet_card_no FROM fleet_card_master";
		$result = $conn->query($sql,false);
		return $result;
	}

	public function getFleetCardMonitoringReport($start_date,$end_date){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
				       a.trip_ticket_no,
				       a.fleet_card_id,
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
				WHERE a.fleet_card_id IS NOT NULL AND (log_time BETWEEN :start_date AND :end_date)";
		$result = $conn->query($sql,array(":start_date"=>$start_date,":end_date"=>$end_date));
		return $result;
	}
	
	public function update_vehicle_fleet_card($cs_no,$fleet_card_no){
		$conn = Connection::getInstance();
		$sql = "UPDATE sys_insurance_and_registration.iar_company_car_units
				SET sys_insurance_and_registration.iar_company_car_units.fleet_card_no = :fleet_card_no
				WHERE sys_insurance_and_registration.iar_company_car_units.cs_no = :cs_no";
		$result = $conn->query($sql,array(":fleet_card_no"=>$fleet_card_no,":cs_no"=>$cs_no));
		return $result;
	}

	public function get_fleet_card_report($start_date,$end_date){
		$conn = Connection::getInstance();
		$sql = "SELECT tl.id,
				       units.fleet_card_no,
				       units.plate_no,
				       units.cs_no,
				       lc.classification vehicle_class,
				       tl.driver_name,
				       DATE_FORMAT(tl.log_time,'%m/%d/%Y %h:%i %p') log_date,
				       lt.type event_type,
				       tl.km_reading,
				       fs.status fuel_status
				FROM sys_vehicle_monitoring.time_log tl LEFT JOIN sys_insurance_and_registration.iar_company_car_units units
					ON tl. vehicle_id = units.id
				     LEFT JOIN sys_vehicle_monitoring.log_classification lc
					ON lc.id = tl.log_classification_id
				     LEFT JOIN sys_vehicle_monitoring.log_type lt
					ON lt.id = tl.log_type_id
					LEFT JOIN sys_vehicle_monitoring.fuel_status fs
						ON fs.id = tl.fuel_status_id
				WHERE date(tl.log_time) BETWEEN :start_date AND :end_date
					  AND units.fleet_card_no IS NOT NULL";
		$result = $conn->query($sql,array(":start_date"=>$start_date,":end_date"=>$end_date));
		return $result;
	}

}