<?php

/*
 *
 * Title : TripTricket Class
 * Author : John Paul M. Alcabasa
 * Company : Isuzu Philppines Corporation
 * Date Created :  December 4, 2015
 * System : Vehicle Monitoring
 * Version : 1.0
 *
 */

class TripTicket {

	/*
	 *  Gets list of nature of trip from "nature_of_trip" table
	 *  @params None
	 *  @returns list of nature of trips
	 */

	public function getNatureOfTrip(){
		$conn = Connection::getInstance();
		$sql = "SELECT * FROM nature_of_trip";
		$result = $conn->query($sql,false);
		return $result;
	}

 	/*
	 * Method for trip_ticket creation - Adds trip ticket in the trip_ticket table
	 * @params $ta_no = travel authorization number, 
	 * @params $driver_no = driver_no from company_drivers_master E.g DRV00001 or employee_no from employee_masterfile_tab
	 * @params $vehicle_no = vehicle id from Insurance and Registration Database E.g IPCCU00001 but the saved id is "1" 
	 * @params $nature_of_trip_id = nature of trip id that is from nature of trip table 
	 * @params $purpose = text based input from user,
	 * @params $expected_time_return = date time input from user
	 * $params $user = employee_id of the user, usually in session. $_SESSSION['user_data']['employee_id']
	 * @returns trip_tricket_no = autoincrement
	 */
	public function addTripTicket($jo_no,$driver_no,$vehicle_id,$nature_of_trip_id,$purpose,$destination,$expected_time_return,$create_user,$requestor,$vehicle_checklist_id,$ob_date_from,$ob_date_to) {
		$conn = Connection::getInstance();
		$sql = "INSERT INTO trip_ticket(
					jo_no,
					driver_no,
					vehicle_id,
					nature_of_trip_id,
					purpose,
					destination,
					expected_time_of_return,
					requestor,
					vehicle_checklist_id,
					ob_date_from,
					ob_date_to,
					create_user,
					date_requested
				) 
				VALUES(
					:jo_no,
					:driver_no,
					:vehicle_id,
					:nature_of_trip_id,
					:purpose,
					:destination,
					:expected_time_of_return,
					:requestor,
					:vehicle_checklist_id,
					:ob_date_from,
					:ob_date_to,
					:create_user,
					NOW()
				)";
		$trip_ticket_no = $conn->query($sql,
							   array(
							   		":jo_no" 					=> $jo_no,
							   		":driver_no" 				=> $driver_no,
							   		":vehicle_id" 				=> $vehicle_id,
							   		":nature_of_trip_id" 		=> $nature_of_trip_id,
							   		":purpose"					=> $purpose,
							   		":destination"				=> $destination,
							   		":expected_time_of_return" 	=> $expected_time_return,
							   		":requestor" 				=> $requestor,
							   		":vehicle_checklist_id" 	=> $vehicle_checklist_id,
							   		":ob_date_from" 			=> $ob_date_from,
							   		":ob_date_to" 				=> $ob_date_to,
							   		":create_user"				=> $create_user,
							   )
				  );
		$this->update_checklist_trip_ticket($vehicle_checklist_id,$trip_ticket_no,$create_user);
		//$this->updateCheckListStatus($checklist_id,2); // set checklist to End of Validity since it is used already
		return $trip_ticket_no;
	}

	public function update_checklist_trip_ticket($checklist_id,$trip_ticket_no,$update_user){
		$conn = Connection::getInstance();
		$sql = "UPDATE sys_vehicle_monitoring.vehicle_checklist_header
				SET trip_ticket_no = :trip_ticket_no,
					update_user = :update_user,
					date_updated = NOW()
				WHERE id = :checklist_id";
		$conn->query($sql,array(
							":trip_ticket_no" => $trip_ticket_no,
							":update_user" => $update_user,
							":checklist_id" => $checklist_id
						  )
					);
	}
	/* 
     * Method for fetching trip ticket data
     * 
     *
     *
	 */
	public function getTripTicketDetails($trip_tricket_id){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
					   a.jo_no,
					   a.driver_no,
					   a.vehicle_id,
					   a.nature_of_trip_id,
					   a.purpose,
					   a.destination,
					   a.expected_time_of_return,
					   a.create_user,
					   a.date_requested,b.trip_type,
					   FormatName(c.first_name,c.middle_name,c.last_name) requestor_name,
					   a.requestor,
					   d.status,
					   a.checklist_id,
					   (SELECT COUNT(id) FROM time_log WHERE trip_ticket_no = a.id) log_count,
					   a.ob_date_from,
					   a.ob_date_to
				FROM trip_ticket a INNER JOIN nature_of_trip b 
					 	ON a.nature_of_trip_id = b.id
					 LEFT JOIN ipc_central.personal_information_tab c 
					 	ON c.employee_id = a.requestor
					 LEFT JOIN trip_status d 
					    ON d.id = a.trip_status_id
				WHERE a.id = :id AND a.delete_user IS NULL
				LIMIT 1";
		$result = $conn->query($sql,array(":id"=>$trip_tricket_id));
		return (!empty($result) ? (object)$result[0] : $result);
	}

	/*
 	 *
 	 * Method for adding logs in the trip ticket
 	 *
	 */

	public function addCompanyCarLog($log_classification_id,$trip_ticket_no,$log_type_id,$driver_no,$driver_name,$vehicle_id,$km_reading,$fuel_status_id,$create_user,$passenger_list,$e_pass_no,$fleet_card_no,$remarks){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO time_log(trip_ticket_no,log_type_id,log_classification_id,driver_no,driver_name,vehicle_id,km_reading,fuel_status_id,log_time,e_pass_id,fleet_card_id,remarks,create_user)
				VALUES(:trip_ticket_no,:log_type_id,:log_classification_id,:driver_no,:driver_name,:vehicle_id,:km_reading,:fuel_status_id,NOW(),:e_pass_id,:fleet_card_id,:remarks,:create_user)";
		$time_log_id = $conn->query($sql,array(
										":trip_ticket_no" 		 => $trip_ticket_no,
										":log_type_id" 		  	 => $log_type_id,
										":log_classification_id" => $log_classification_id,
										":driver_no" 	  		 => $driver_no,
										":driver_name" 	  		 => $driver_name,
										":vehicle_id" 	  		 => $vehicle_id,
										":km_reading" 	  		 => $km_reading,
										":fuel_status_id" 		 => $fuel_status_id,
										":e_pass_id" 	  		 => $e_pass_no,
										":fleet_card_id"		 => $fleet_card_no,
										":remarks"		 		 => $remarks,
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

	public function getTimeLogsByTripTicketNo($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
					   a.trip_ticket_no,
					   a.log_type_id,
					   a.driver_no,
					   a.vehicle_id,
					   a.km_reading,
					   a.fuel_status_id,
					   a.log_time,
					   a.create_user,
					   b.status,
					   c.type log_type,
					   a.remarks,
					   d.cs_no,
					   d.plate_no,
					   e.model,
					   a.driver_name
				FROM time_log a INNER JOIN fuel_status b ON a.fuel_status_id = b.id
				INNER JOIN log_type c ON c.id = a.log_type_id
				LEFT JOIN sys_insurance_and_registration.iar_company_car_units d ON d.id = a.vehicle_id
				LEFT JOIN sys_insurance_and_registration.iar_vehicle_master e ON e.cs_no = d.cs_no 
				WHERE a.trip_ticket_no = :trip_ticket_no
				ORDER BY a.log_time ASC";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		return $result;
	}

	public function getPassengersByTimeLogId($time_log_id){
		$conn = Connection::getInstance();
		$sql = "SELECT id,passenger_name FROM time_log_passenger WHERE time_log_id = :time_log_id";
		$result = $conn->query($sql,array(":time_log_id"=>$time_log_id));
		return $result;
	}

	public function getLogType(){
		$conn = Connection::getInstance();
		$sql = "SELECT * FROM log_type";
		$result = $conn->query($sql,false);
		return $result;
	}
	
	public function addCarPlanLog($log_classification_id,$log_type_id,$employee_id,$driver_no,$driver_name,$vehicle_id,$km_reading,$fuel_status_id,$create_user,$passenger_list,$e_pass_no,$fleet_card_no,$remarks){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO time_log(log_type_id,log_classification_id,employee_id,driver_no,driver_name,vehicle_id,km_reading,fuel_status_id,log_time,e_pass_id,fleet_card_id,remarks,create_user)
				VALUES(:log_type_id,:log_classification_id,:employee_id,:driver_no,:driver_name,:vehicle_id,:km_reading,:fuel_status_id,NOW(),:e_pass_no,:fleet_card_no,:remarks,:create_user)";
		$time_log_id = $conn->query($sql,array(
										":log_type_id" 		  	 => $log_type_id,
										":log_classification_id" => $log_classification_id,
										":employee_id"		     => $employee_id,
										":driver_no" 	  		 => $driver_no,
										":driver_name" 	  		 => $driver_name,
										":vehicle_id" 	  		 => $vehicle_id,
										":km_reading" 	  		 => $km_reading,
										":fuel_status_id" 		 => $fuel_status_id,
										":e_pass_no" 	  		 => $e_pass_no,
										":fleet_card_no" 	  	 => $fleet_card_no,
										":remarks" 	  	 		 => $remarks,
										":create_user" 	  		 => $create_user
									)
				  );
	
		if(!empty($passenger_list)){
			$this->addLogTimePassengers($time_log_id,$passenger_list);
		}
	}

	public function getTripTicketReport($start_date,$end_date,$search_by,$vehicle_id){
		$conn = Connection::getInstance();
	/*	$sql = "SELECT a.id trip_ticket_no,
				       a.jo_no,
				       a.purpose,
				       a.destination,
				       c.trip_type,
				       a.date_requested,
				       FormatName(d.first_name,d.middle_name,d.last_name) requested_by,
				       a.expected_time_of_return,
				       CONCAT(
				       		e.status,
				       		CheckOverdue(
				       			MIN(CASE b.log_type_id WHEN 2 THEN b.log_time ELSE NULL END),
				       			MAX(CASE b.log_type_id WHEN 1 THEN b.log_time ELSE NULL END),
				       			a.expected_time_of_return
				       		)
				       ) trip_status,
				       MAX(CASE b.log_type_id WHEN 1 THEN b.log_time ELSE NULL END) time_in,
				       MAX(CASE b.log_type_id WHEN 2 THEN b.log_time ELSE NULL END) time_out
				FROM trip_ticket a
				INNER JOIN time_log b ON a.id = b.trip_ticket_no
				INNER JOIN nature_of_trip c ON c.id = a.nature_of_trip_id
				INNER JOIN ipc_central.personal_information_tab d ON d.employee_id = a.requestor
				INNER JOIN trip_status e ON e.id = a.trip_status_id
				WHERE $search_by BETWEEN :start_date AND :end_date GROUP BY a.id";  
		update as of April 5, 2017 10:13 AM by Paul Pogi */
		$vehicle_where = "";
		if($vehicle_id != ""){
			$vehicle_where =  "AND b.vehicle_id = " . $vehicle_id;
		}
		$sql = "SELECT a.id trip_ticket_no,
				       a.jo_no,
				       CASE WHEN (units.plate_no != '') THEN units.plate_no ELSE units.cs_no END plate_no,
				       vms.body_color,
				       vms.model,
				       a.purpose,
				       a.destination,
				       c.trip_type,
				       a.date_requested,
				       sys_vehicle_monitoring.GetDriverName(a.driver_no) driver_name,
				       a.expected_time_of_return,
				       CONCAT(
				       		e.status,
				       		sys_vehicle_monitoring.CheckOverdue(
				       			MIN(CASE b.log_type_id WHEN 2 THEN b.log_time ELSE NULL END),
				       			MAX(CASE b.log_type_id WHEN 1 THEN b.log_time ELSE NULL END),
				       			a.expected_time_of_return
				       		)
				       ) trip_status,
				       MAX(CASE b.log_type_id WHEN 1 THEN b.log_time ELSE NULL END) time_in,
				       MAX(CASE b.log_type_id WHEN 2 THEN b.log_time ELSE NULL END) time_out
				FROM sys_vehicle_monitoring.trip_ticket a
					LEFT JOIN sys_vehicle_monitoring.time_log b 
						ON a.id = b.trip_ticket_no
					LEFT JOIN sys_vehicle_monitoring.nature_of_trip c 
						ON c.id = a.nature_of_trip_id
					LEFT JOIN ipc_central.personal_information_tab d 
						ON d.employee_id = a.requestor
					LEFT JOIN sys_vehicle_monitoring.trip_status e 
						ON e.id = a.trip_status_id
					LEFT JOIN sys_insurance_and_registration.iar_company_car_units units 
						ON a.vehicle_id = units.id 
					LEFT JOIN sys_insurance_and_registration.iar_vehicle_master vms
						ON vms.cs_no = units.cs_no
				WHERE $search_by BETWEEN :start_date AND :end_date 
					  $vehicle_where
				GROUP BY a.id";
		
		$result = $conn->query($sql,array(":start_date"=>$start_date,":end_date"=>$end_date));
		return $result;
	}

	public function getTripLogReport($start_date,$end_date,$search_by,$vehicle_id){
		$conn = Connection::getInstance();
	
		$vehicle_where = "";
		if($vehicle_id != ""){
			$vehicle_where =  "AND b.vehicle_id = " . $vehicle_id;
		}
		$sql = "SELECT a.id trip_ticket_no,
				       a.jo_no,
				       CASE WHEN (units.plate_no != '') THEN units.plate_no ELSE units.cs_no END plate_no,
				       vms.body_color,
				       vms.model,
				       a.purpose,
				       a.destination,
				       c.trip_type,
				       a.date_requested,
				       sys_vehicle_monitoring.GetDriverName(a.driver_no) driver_name,
				       a.expected_time_of_return,
				       e.status,
				       CASE WHEN b.log_type_id = 1 THEN 'In' ELSE 'Out' END log_type,
				       b.log_time
				FROM sys_vehicle_monitoring.trip_ticket a
					LEFT JOIN sys_vehicle_monitoring.time_log b 
						ON a.id = b.trip_ticket_no
					LEFT JOIN sys_vehicle_monitoring.nature_of_trip c 
						ON c.id = a.nature_of_trip_id
					LEFT JOIN ipc_central.personal_information_tab d 
						ON d.employee_id = a.requestor
					LEFT JOIN sys_vehicle_monitoring.trip_status e 
						ON e.id = a.trip_status_id
					LEFT JOIN sys_insurance_and_registration.iar_company_car_units units 
						ON a.vehicle_id = units.id 
					LEFT JOIN sys_insurance_and_registration.iar_vehicle_master vms
						ON vms.cs_no = units.cs_no
				WHERE $search_by BETWEEN :start_date AND :end_date 
					  $vehicle_where
				";
		
		$result = $conn->query($sql,array(":start_date"=>$start_date,":end_date"=>$end_date));
		return $result;
	}

	public function getDestinations(){
		$conn = Connection::getInstance();
		$sql = "SELECT destination FROM destination_master ORDER BY destination ASC";
		$result = $conn->query($sql,false);
		return $result;
	}	
	
	public function updateTripTicket($trip_ticket_no,$jo_no,$driver_no,$vehicle_id,$nature_of_trip_id,$purpose,$destination,$expected_time_of_return,$checklist_id,$update_user,$requestor,$ob_date_from,$ob_date_to){
		$conn = Connection::getInstance();
		$sql = "UPDATE trip_ticket SET jo_no = :jo_no,
									   driver_no = :driver_no,
									   vehicle_id = :vehicle_id,
									   nature_of_trip_id = :nature_of_trip_id,
									   purpose = :purpose,
									   destination = :destination,
									   requestor = :requestor,
									   expected_time_of_return = :expected_time_of_return,
									   checklist_id = :checklist_id,
									   update_user = :update_user,
									   ob_date_from = :ob_date_from,
									   ob_date_to = :ob_date_to,
									   date_updated = NOW()
				WHERE id = :id";
		$result = $conn->query($sql, array(
									 	":jo_no" => $jo_no,
									 	":driver_no" => $driver_no,
									 	":vehicle_id" => $vehicle_id,
									 	":nature_of_trip_id" => $nature_of_trip_id,
									 	":purpose" => $purpose,
									 	":destination" => $destination,
									 	":requestor" => $requestor,
									 	":ob_date_from" => $ob_date_from,
									 	":ob_date_to" => $ob_date_to,
									 	":expected_time_of_return" => $expected_time_of_return,
									 	":checklist_id" => $checklist_id,
									 	":update_user" => $update_user,
									 	":id" => $trip_ticket_no
									 )
		);
	}

	public function getTimeLogDetails($log_id){
		$conn = Connection::getInstance();
		$sql = "SELECT id,
				       trip_ticket_no,
				       log_type_id,
				       log_classification_id,
				       employee_id,
				       driver_no,
				       vehicle_id,
				       km_reading,
				       fuel_status_id,
				       log_time,
				       e_pass_id,
				       fleet_card_id,
				       remarks
				FROM time_log
				WHERE id = :id
				LIMIT 1";
		$result = $conn->query($sql,array(":id"=>$log_id));
		return (object)$result[0];
	}

	public function updateCompanyCarLog($time_log_id,$log_classification_id,$trip_ticket_no,$log_type_id,$driver_no,$vehicle_id,$km_reading,$fuel_status_id,$update_user,$passenger_list,$e_pass_no,$fleet_card_no,$remarks,$time_log){
		$conn = Connection::getInstance();
		$sql = "UPDATE time_log SET trip_ticket_no = :trip_ticket_no,
									log_type_id = :log_type_id,
									log_classification_id = :log_classification_id,
									driver_no = :driver_no,
									vehicle_id = :vehicle_id,
									km_reading = :km_reading,
									fuel_status_id = :fuel_status_id,
									e_pass_id = :e_pass_id,
									fleet_card_id = :fleet_card_id,
									remarks = :remarks,
									update_user = :update_user,
									log_time = :time_log,
									date_updated = NOW()
				WHERE id = :id";
		$update_query_result = $conn->query($sql,array(
										":trip_ticket_no" 		 => $trip_ticket_no,
										":log_type_id" 		  	 => $log_type_id,
										":log_classification_id" => $log_classification_id,
										":driver_no" 	  		 => $driver_no,
										":vehicle_id" 	  		 => $vehicle_id,
										":km_reading" 	  		 => $km_reading,
										":fuel_status_id" 		 => $fuel_status_id,
										":e_pass_id" 	  		 => $e_pass_no,
										":fleet_card_id"		 => $fleet_card_no,
										":remarks"		 		 => $remarks,
										":update_user" 	  		 => $update_user,
										":id"					 => $time_log_id,
										":time_log"				 => $time_log
									)
				  );
	
		if(!empty($passenger_list)){
			// delete first old passengers
			$del_pass = $conn->query("DELETE FROM time_log_passenger WHERE time_log_id = :id",array(":id"=>$time_log_id));
			$this->addLogTimePassengers($time_log_id,$passenger_list);
		}
	}

	public function updateCarPlanLog($time_log_id,$log_classification_id,$log_type_id,$employee_id,$driver_no,$vehicle_id,$km_reading,$fuel_status_id,$update_user,$passenger_list,$e_pass_no,$fleet_card_no,$remarks){
		$conn = Connection::getInstance();
		$sql = "UPDATE time_log SET log_type_id = :log_type_id,
									log_classification_id = :log_classification_id,
									employee_id = :employee_id,
									driver_no = :driver_no,
									vehicle_id = :vehicle_id,
									km_reading = :km_reading,
									fuel_status_id = :fuel_status_id,
									e_pass_id = :e_pass_id,
									fleet_card_id = :fleet_card_id,
									remarks = :remarks,
									update_user = :update_user,
									date_updated = NOW()
				WHERE id = :id";
		$update_result = $conn->query($sql,array(
										":log_type_id" 		  	 => $log_type_id,
										":log_classification_id" => $log_classification_id,
										":employee_id"		     => $employee_id,
										":driver_no" 	  		 => $driver_no,
										":vehicle_id" 	  		 => $vehicle_id,
										":km_reading" 	  		 => $km_reading,
										":fuel_status_id" 		 => $fuel_status_id,
										":e_pass_id" 	  		 => $e_pass_no,
										":fleet_card_id" 	  	 => $fleet_card_no,
										":remarks" 	  	 		 => $remarks,
										":update_user" 	  		 => $update_user,
										":id"					 => $time_log_id
									)
				  );
	
		if(!empty($passenger_list)){
			$del_pass = $conn->query("DELETE FROM time_log_passenger WHERE time_log_id = :id",array(":id"=>$time_log_id));
			$this->addLogTimePassengers($time_log_id,$passenger_list);
		}
	}

		/* 
     * Method for fetching trip ticket data
     * 
     *
     *
	 */
	public function getCompleteTripTicketDetails($trip_tricket_id){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
					   a.jo_no,
					   GetDriverName(a.driver_no) driver_name,
					   a.vehicle_id,
					   a.nature_of_trip_id,
					   a.purpose,
					   a.destination,
					   a.expected_time_of_return,
					   a.create_user,
					   a.date_requested,
					   b.trip_type,
					   c.plate_no,
					   c.cs_no,
					   a.driver_no,
					   CONCAT(d.last_name,', ',d.first_name,' ',LEFT(d.middle_name,1),'.') prepared_by,
					   a.requestor,
					   vch.id checklist_id,
					   vc.description vc_condition,
					   DATE_FORMAT(vch.date_created,'%m/%d/%Y %h:%i %p') checklist_date_created,
					   a.ob_date_from,
					   a.ob_date_to,
					   a.fuel_rate as fuelrate
				FROM trip_ticket a LEFT JOIN nature_of_trip b 
				     	ON a.nature_of_trip_id = b.id
				     LEFT JOIN sys_insurance_and_registration.iar_company_car_units c
				        ON c.id = a.vehicle_id
				     LEFT JOIN ipc_central.personal_information_tab d 
				        ON d.employee_id = a.create_user
				     LEFT JOIN vehicle_checklist_header vch
				        ON vch.trip_ticket_no = a.id
				     LEFT JOIN vehicle_condition vc
				     	ON vc.id = vch.vehicle_condition_id
				WHERE a.id = :id AND a.delete_user IS NULL
				LIMIT 1";
		$result = $conn->query($sql,array(":id"=>$trip_tricket_id));
		return (!empty($result) ? (object)$result[0] : $result);
	}

	public function cancelTrip($trip_ticket_no,$update_user){
		$conn = Connection::getInstance();
		$sql = "UPDATE trip_ticket SET is_cancelled = 1,
		  							   update_user = :update_user,
									   date_updated = NOW()
				WHERE id = :id";
		$result = $conn->query($sql,array(
										":id"		   =>	$trip_ticket_no,
										":update_user" =>	$update_user
									)
							  );
	}

	public function getTripTicketFirstLogout($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT id,
					   log_type_id,
					   employee_id,
					   driver_no,
					   vehicle_id,
					   log_time,
					   km_reading
				FROM time_log
				WHERE trip_ticket_no = :id
				ORDER BY log_time DESC
				LIMIT 1";
		$result = $conn->query($sql,array(":id"=>$trip_ticket_no));
		 if(!empty($result)) {
		 	$result[0]['vehicle_id'] = Format::formatVehicleId($result[0]['vehicle_id']);
		 	return (object)$result[0];
		 }
		 else {
		 	return $result;
		 }
	}

	public function addPassengerToTripTicket($trip_ticket_no,$passenger_list){
		$conn = Connection::getInstance();
		foreach($passenger_list as $passenger){
			$sql = "INSERT INTO trip_ticket_passenger(trip_ticket_no,passenger_name)
					VALUES(:trip_ticket_no,:passenger_name)";
			$result = $conn->query($sql,array(
										":trip_ticket_no" 	=> $trip_ticket_no,
										":passenger_name"	=> $passenger,
									)
				  	  );
		}

	}

	public function getTripTicketPassengers($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT id,passenger_name FROM trip_ticket_passenger WHERE trip_ticket_no = :trip_ticket_no";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		return $result;
	}
	
	public function updateTripTicketStatus($trip_ticket_no,$trip_status_id){
		$conn = Connection::getInstance();
		$sql = "UPDATE trip_ticket SET trip_status_id = :trip_status_id, date_updated = NOW() WHERE id = :trip_ticket_no";
		$result = $conn->query($sql,array(":trip_status_id"=>$trip_status_id,":trip_ticket_no"=>$trip_ticket_no));
	}

	public function getLastTripLog($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT id,log_type_id
				FROM time_log
				WHERE trip_ticket_no = :trip_ticket_no
				ORDER BY log_time DESC
				LIMIT 1";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		return $result;
	}

	public function deletePassenger($id,$trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "DELETE FROM trip_ticket_passenger WHERE id = :id AND trip_ticket_no = :trip_ticket_no";
		$result = $conn->query($sql,array(":id"=>$id,":trip_ticket_no"=>$trip_ticket_no));
		return $result;
	}

	public function addPassenger($trip_ticket_no,$passenger_name){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO trip_ticket_passenger(trip_ticket_no,passenger_name) VALUES(:trip_ticket_no,:passenger_name)";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no,":passenger_name"=>$passenger_name));
		return $result;
	}

	public function updateCheckListStatus($checklist_id,$status_id){
		$conn = Connection::getInstance();
		$sql = "UPDATE vehicle_checklist SET vehicle_checklist_status_id = :status_id WHERE id = :checklist_id";
		$result = $conn->query($sql,array(":status_id"=>$status_id,":checklist_id"=>$checklist_id));
	}

	public function get_active_trip_tickets($from_date,$to_date,$tt_no){
		$conn = Connection::getInstance();
		if($tt_no == ""){
			$sql = "SELECT
						  a.id                      AS id,
						  a.jo_no                   AS jo_no,
						  (CASE WHEN f.plate_no = '' THEN f.cs_no ELSE f.plate_no END) AS vehicle,
						  b.trip_type               AS trip_type,
						  a.purpose                 AS purpose,
						  a.destination             AS destination,
						  a.date_requested          AS date_requested,
						  a.expected_time_of_return AS expected_time_of_return,
						  FormatName(c.first_name,c.middle_name,c.last_name)  AS requestor_name,
						 CONCAT(e.status,'',CheckOverdue(MIN((CASE d.log_type_id WHEN 2 THEN d.log_time ELSE NULL END)),MAX((CASE d.log_type_id WHEN 1 THEN d.log_time ELSE NULL END)),a.expected_time_of_return)) AS trip_status,
						  CONCAT(
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id),';',
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id
							AND sys_vehicle_monitoring.trip_ticket_approval.is_approved = 1)
						)  approval_status_count
					FROM sys_vehicle_monitoring.trip_ticket a
						INNER JOIN sys_vehicle_monitoring.nature_of_trip b
							ON a.nature_of_trip_id = b.id
						INNER JOIN ipc_central.personal_information_tab c
							ON c.employee_id = a.requestor
						LEFT JOIN sys_vehicle_monitoring.time_log d
							ON d.trip_ticket_no = a.id
						 LEFT JOIN sys_vehicle_monitoring.trip_status e
							ON e.id = a.trip_status_id
						LEFT JOIN sys_insurance_and_registration.iar_company_car_units f
							ON a.vehicle_id = f.id
					WHERE (a.trip_status_id = 1
					      OR a.trip_status_id IS NULL)
					      AND DATE(a.date_requested) BETWEEN :from_date AND :to_date 
					GROUP BY a.id";
			$result = $conn->query($sql,array(
											":from_date"=>$from_date,
											":to_date"=>$to_date
									)
							);
		}
		else {
			$sql = "SELECT
						  a.id                      AS id,
						  a.jo_no                   AS jo_no,
						  (CASE WHEN f.plate_no = '' THEN f.cs_no ELSE f.plate_no END) AS vehicle,
						  b.trip_type               AS trip_type,
						  a.purpose                 AS purpose,
						  a.destination             AS destination,
						  a.date_requested          AS date_requested,
						  a.expected_time_of_return AS expected_time_of_return,
						  FormatName(c.first_name,c.middle_name,c.last_name)  AS requestor_name,
						 CONCAT(e.status,'',CheckOverdue(MIN((CASE d.log_type_id WHEN 2 THEN d.log_time ELSE NULL END)),MAX((CASE d.log_type_id WHEN 1 THEN d.log_time ELSE NULL END)),a.expected_time_of_return)) AS trip_status,
						  CONCAT(
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id),';',
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id
							AND sys_vehicle_monitoring.trip_ticket_approval.is_approved = 1)
						)  approval_status_count
					FROM sys_vehicle_monitoring.trip_ticket a
						INNER JOIN sys_vehicle_monitoring.nature_of_trip b
							ON a.nature_of_trip_id = b.id
						INNER JOIN ipc_central.personal_information_tab c
							ON c.employee_id = a.requestor
						LEFT JOIN sys_vehicle_monitoring.time_log d
							ON d.trip_ticket_no = a.id
						 LEFT JOIN sys_vehicle_monitoring.trip_status e
							ON e.id = a.trip_status_id
						LEFT JOIN sys_insurance_and_registration.iar_company_car_units f
							ON a.vehicle_id = f.id
					WHERE (a.trip_status_id = 1
					      OR a.trip_status_id IS NULL)
					      AND a.id = :tt_no
					GROUP BY a.id";
			$result = $conn->query($sql,array(
											":tt_no"=>$tt_no
									)
							);
		}
		return $result;
	}

	public function get_closed_trip_tickets($from_date,$to_date,$tt_no){
	
		$conn = Connection::getInstance();
		if($tt_no == ""){
			$sql = "SELECT
						  a.id                      AS id,
						  a.jo_no                   AS jo_no,
						  (CASE WHEN f.plate_no = '' THEN f.cs_no ELSE f.plate_no END) AS vehicle,
						  b.trip_type               AS trip_type,
						  a.purpose                 AS purpose,
						  a.destination             AS destination,
						  a.date_requested          AS date_requested,
						  a.expected_time_of_return AS expected_time_of_return,
						  FormatName(c.first_name,c.middle_name,c.last_name)  AS requestor_name,
						 CONCAT(e.status,'',CheckOverdue(MIN((CASE d.log_type_id WHEN 2 THEN d.log_time ELSE NULL END)),MAX((CASE d.log_type_id WHEN 1 THEN d.log_time ELSE NULL END)),a.expected_time_of_return)) AS trip_status,
						  CONCAT(
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id),';',
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id
							AND sys_vehicle_monitoring.trip_ticket_approval.is_approved = 1)
						)  approval_status_count
					FROM sys_vehicle_monitoring.trip_ticket a
						INNER JOIN sys_vehicle_monitoring.nature_of_trip b
							ON a.nature_of_trip_id = b.id
						INNER JOIN ipc_central.personal_information_tab c
							ON c.employee_id = a.requestor
						LEFT JOIN sys_vehicle_monitoring.time_log d
							ON d.trip_ticket_no = a.id
						 LEFT JOIN sys_vehicle_monitoring.trip_status e
							ON e.id = a.trip_status_id
						LEFT JOIN sys_insurance_and_registration.iar_company_car_units f
							ON a.vehicle_id = f.id
					WHERE a.trip_status_id IN(2,3)
					      AND DATE(a.date_requested) BETWEEN :from_date AND :to_date 
					     
					GROUP BY a.id";
			$result = $conn->query($sql,array(
											":from_date"=>$from_date,
											":to_date"=>$to_date
									)
							);
		}
		else {
			$sql = "SELECT
						  a.id                      AS id,
						  a.jo_no                   AS jo_no,
						  (CASE WHEN f.plate_no = '' THEN f.cs_no ELSE f.plate_no END) AS vehicle,
						  b.trip_type               AS trip_type,
						  a.purpose                 AS purpose,
						  a.destination             AS destination,
						  a.date_requested          AS date_requested,
						  a.expected_time_of_return AS expected_time_of_return,
						  FormatName(c.first_name,c.middle_name,c.last_name)  AS requestor_name,
						 CONCAT(e.status,'',CheckOverdue(MIN((CASE d.log_type_id WHEN 2 THEN d.log_time ELSE NULL END)),MAX((CASE d.log_type_id WHEN 1 THEN d.log_time ELSE NULL END)),a.expected_time_of_return)) AS trip_status,
						  CONCAT(
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id),';',
							(SELECT COUNT(sys_vehicle_monitoring.trip_ticket_approval.id) 
							FROM sys_vehicle_monitoring.trip_ticket_approval 
							WHERE sys_vehicle_monitoring.trip_ticket_approval.trip_ticket_no = a.id
							AND sys_vehicle_monitoring.trip_ticket_approval.is_approved = 1)
						)  approval_status_count
					FROM sys_vehicle_monitoring.trip_ticket a
						INNER JOIN sys_vehicle_monitoring.nature_of_trip b
							ON a.nature_of_trip_id = b.id
						INNER JOIN ipc_central.personal_information_tab c
							ON c.employee_id = a.requestor
						LEFT JOIN sys_vehicle_monitoring.time_log d
							ON d.trip_ticket_no = a.id
					    LEFT JOIN sys_vehicle_monitoring.trip_status e
							ON e.id = a.trip_status_id
						LEFT JOIN sys_insurance_and_registration.iar_company_car_units f
							ON a.vehicle_id = f.id
					WHERE a.trip_status_id = 2
					      AND a.id = :tt_no
					GROUP BY a.id";
			$result = $conn->query($sql,array(
											":tt_no"=>$tt_no
									)
							);
		}
	
		return $result;
	}

	public function get_overdue_trip_tickets(){
		$conn = Connection::getInstance();
		
/*		$sql = "SELECT *
				FROM (
					SELECT
						a.id                      AS trip_ticket_no,
						a.jo_no                   AS jo_no,
						f.plate_no,
						f.cs_no,
						GetDriverName(a.driver_no) driver_name,
						(CASE WHEN (f.plate_no = '') THEN f.cs_no ELSE f.plate_no END) AS vehicle,
						b.trip_type               AS nature_of_trip,
						a.purpose                 AS purpose,
						a.destination             AS destination,
						a.date_requested          AS date_requested,
						DATE_FORMAT(a.expected_time_of_return,'%M %d,%Y %h:%i %p') expected_time_of_return,
						FormatName(c.first_name,c.middle_name,c.last_name)  AS requestor_name,
						FormatName(prep_by.first_name,prep_by.middle_name,prep_by.last_name)  AS prepared_by,
						CONCAT(e.status,'',CheckOverdue(
												MIN(
													(
													CASE d.log_type_id 
														WHEN 2 THEN d.log_time 
														ELSE NULL 
													END
													)
												),
												MAX(
													(
													CASE d.log_type_id 
														WHEN 1 THEN d.log_time 
														ELSE NULL END
													)
												),a.expected_time_of_return
											)
						) AS trip_status,
						et.email
					FROM sys_vehicle_monitoring.trip_ticket a
						LEFT JOIN sys_vehicle_monitoring.nature_of_trip b
							ON a.nature_of_trip_id = b.id
						LEFT JOIN ipc_central.personal_information_tab c
							ON c.employee_id = a.requestor
						LEFT JOIN sys_vehicle_monitoring.time_log d
							ON d.trip_ticket_no = a.id
						LEFT JOIN sys_vehicle_monitoring.trip_status e
							ON e.id = a.trip_status_id
						LEFT JOIN sys_insurance_and_registration.iar_company_car_units f
							ON a.vehicle_id = f.id
						LEFT JOIN ipc_central.personal_information_tab prep_by
							ON prep_by.employee_id = a.create_user
						LEFT JOIN ipc_central.email_tab et 
							ON et.employee_id = c.employee_id
					WHERE (a.trip_status_id = 1)
					GROUP BY a.id
				) a
				WHERE a.trip_status = 'Open Trip and Overdue'
				      AND DATE(a.date_requested) >= '2017-12-01'";*/
		$sql = "SELECT a.*,
				       manager_email.email manager_mail_address
				FROM (
					SELECT
						a.id                      AS trip_ticket_no,
						a.jo_no                   AS jo_no,
						f.plate_no,
						f.cs_no,
						sys_vehicle_monitoring.GetDriverName(a.driver_no) driver_name,
						(CASE WHEN (f.plate_no = '') THEN f.cs_no ELSE f.plate_no END) AS vehicle,
						b.trip_type               AS nature_of_trip,
						a.purpose                 AS purpose,
						a.destination             AS destination,
						a.date_requested          AS date_requested,
						DATE_FORMAT(a.expected_time_of_return,'%M %d,%Y %h:%i %p') expected_time_of_return,
						sys_vehicle_monitoring.FormatName(c.first_name,c.middle_name,c.last_name)  AS requestor_name,
						sys_vehicle_monitoring.FormatName(prep_by.first_name,prep_by.middle_name,prep_by.last_name)  AS prepared_by,
						CONCAT(e.status,'',sys_vehicle_monitoring.CheckOverdue(
									MIN(
										(
										CASE d.log_type_id 
											WHEN 2 THEN d.log_time 
											ELSE NULL 
										END
										)
									),
									MAX(
										(
										CASE d.log_type_id 
											WHEN 1 THEN d.log_time 
											ELSE NULL END
										)
									),a.expected_time_of_return
								)
						) AS trip_status,
						req_et.email requestor_email,
						CASE WHEN smt.ash != 0 THEN smt.ash
						     WHEN smt.sh != 0 THEN smt.sh
						     WHEN smt.adh != 0 THEN smt.adh
						     WHEN smt.dh != 0 THEN smt.dh
						     WHEN smt.advh != 0 THEN smt.advh
						     WHEN smt.dvh != 0 THEN smt.dvh
						     ELSE 521
						END manager_cc,
						req_emt.section_id
					FROM sys_vehicle_monitoring.trip_ticket a
						LEFT JOIN sys_vehicle_monitoring.nature_of_trip b
							ON a.nature_of_trip_id = b.id
						LEFT JOIN ipc_central.personal_information_tab c
							ON c.employee_id = a.requestor
						LEFT JOIN sys_vehicle_monitoring.time_log d
							ON d.trip_ticket_no = a.id
						LEFT JOIN sys_vehicle_monitoring.trip_status e
							ON e.id = a.trip_status_id
						LEFT JOIN sys_insurance_and_registration.iar_company_car_units f
							ON a.vehicle_id = f.id
						LEFT JOIN ipc_central.personal_information_tab prep_by
							ON prep_by.employee_id = a.create_user
						LEFT JOIN ipc_central.email_tab req_et 
							ON req_et.employee_id = c.employee_id
						LEFT JOIN ipc_central.employee_masterfile_tab req_emt 
							ON req_emt.id = c.employee_id
						LEFT JOIN ipc_central.signatories_matrix_tab smt
							ON smt.section_id = req_emt.section_id
					WHERE (a.trip_status_id = 1)
					GROUP BY a.id
				) a LEFT JOIN ipc_central.email_tab manager_email
					ON manager_email.employee_id = a.manager_cc
				WHERE a.trip_status = 'Open Trip and Overdue'
				      AND DATE(a.date_requested) >= '2017-12-01'";
		$result = $conn->query($sql);
		return $result;
	}

	public function update_fuelrate ($ticketno, $fuelrate, $user) {
		$conn = Connection::getInstance();
		$sql = "UPDATE trip_ticket SET fuel_rate = :fuelrate, update_user = :user, date_updated = NOW() WHERE id = :ticketno";
		$result = $conn->query($sql,array(":fuelrate"=>$fuelrate,":user"=>$user,":ticketno"=>$ticketno));
	}

	public function get_fuel_rate($ticketno) {

		$conn = Connection::getInstance();
		$sql = "SELECT * FROM trip_ticket WHERE id = :ticketno";
		$result = $conn->query($sql,array(":ticketno"=>$ticketno));
		return $result;
	}
	
	public function get_errand_logs($start_date,$end_date,$vehicle_id){
		$conn = Connection::getInstance();

		$vehicle_where = "";
		if($vehicle_id != ""){
			$vehicle_where =  "AND tl.vehicle_id = " . $vehicle_id;
		}

		$sql = "SELECT units.id vehicle_id,
						units.cs_no,
						units.plate_no,
						tl.remarks,
						fs.status fuel_status,
						tl.driver_name,
						GROUP_CONCAT(tlp.passenger_name) passengers,
						DATE_FORMAT(tl.log_time,'%m/%d/%Y %l:%i %p') log_time,
						lt.type log_type,
						tl.km_reading
				FROM sys_vehicle_monitoring.time_log tl
					LEFT JOIN sys_insurance_and_registration.iar_company_car_units units
						ON tl.vehicle_id = units.id
					LEFT JOIN sys_vehicle_monitoring.fuel_status fs
						ON fs.id = tl.fuel_status_id
					LEFT JOIN sys_vehicle_monitoring.time_log_passenger tlp
						ON tlp.time_log_id = tl.id
					LEFT JOIN sys_vehicle_monitoring.log_type lt
						ON lt.id = tl.log_type_id
				WHERE 1 = 1
					AND tl.log_classification_id = 3
						AND DATE(tl.log_time) BETWEEN :start_date AND :end_date 
						$vehicle_where	
				GROUP BY 
					units.id,
					units.cs_no,
					units.plate_no,
					tl.remarks,
					fs.status,
					tl.driver_name,
					tl.km_reading,
					tl.log_time";
		$result = $conn->query($sql,[
			':start_date' => $start_date,
			':end_date'   => $end_date
		]);
		return $result;
	}


} // end of class