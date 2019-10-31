<?php
/*
 *
 * Title : Driver Class
 * Author : John Paul M. Alcabasa
 * Company : Isuzu Philppines Corporation
 * Date Created :  December 4, 2015
 * System : Vehicle Monitoring
 * Version : 1.0
 *
 */

class Driver {

	/*
	 *
	 *  Method for fetching all the drivers
	 *  
	 *
	 */
	public function getDriversList(){
		$conn = Connection::getInstance();
		$sql = "SELECT FormatName(a.first_name,a.middle_name,a.last_name) emp_name, 
					b.employee_no driver_id
					FROM ipc_central.personal_information_tab a INNER JOIN ipc_central.employee_masterfile_tab b ON a.employee_id = b.id 
				UNION
				SELECT CONCAT(last_name,', ',first_name,' ',LEFT(middle_name,1),'.') emp_name, driver_no driver_id
					FROM sys_vehicle_monitoring.company_drivers_master	
				ORDER BY emp_name ASC";
		$results = $conn->query($sql,false);
		return $results;
	}

	/* 
	 *
	 * Method for fetching details of the driver from company_drivers_master
	 * @params @driver_no = number of driver
	 */
	public function getDriverDetails($driver_no){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
					   a.driver_no,
					   a.first_name,
					   a.middle_name,
					   a.last_name,
		               FormatName(a.first_name,a.middle_name,a.last_name) driver_name,
		               a.company,
		               a.contact_no,
		               a.picture,
		               CASE WHEN b.plate_no IS NULL THEN b.cs_no ELSE b.plate_no END AS vehicle,
		               a.assigned_vehicle_id 
				FROM company_drivers_master a 
				     LEFT JOIN sys_insurance_and_registration.iar_company_car_units b 
				     ON a.assigned_vehicle_id = b.id
				WHERE a.id = :driver_no AND a.active = :active LIMIT 1";
		$results = $conn->query($sql,array(":driver_no" => $driver_no,":active" => 1));
		if(!empty($results)){
			return (object)$results[0];
		}
		else {
			return (object)$results;
		}
	}

	public function getEmployeeDetailsById($employee_id){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id, b.first_name,b.middle_name,b.last_name, a.position, c.section, d.department, a.employee_no, e.email, 
					   a.section_id,
					   FormatName(b.first_name,b.middle_name,b.last_name) emp_name 
					FROM ipc_central.employee_masterfile_tab a 
					INNER JOIN ipc_central.personal_information_tab b ON a.id = b.employee_id
					LEFT JOIN ipc_central.section_tab c ON a.section_id = c.id
					LEFT JOIN ipc_central.department_tab d ON a.department_id = d.id
					LEFT JOIN ipc_central.email_tab e ON a.id = e.employee_id 
				WHERE a.id = :employee_id LIMIT 1";
		$results = $conn->query($sql,array(":employee_id" => $employee_id));
		return (object)$results[0];
	}

	public function getEmployeeDetailsByNo($employee_no){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id, b.first_name,b.middle_name,b.last_name, a.position, c.section, d.department 
					FROM ipc_central.employee_masterfile_tab a 
					INNER JOIN ipc_central.personal_information_tab b ON a.id = b.employee_id
					LEFT JOIN ipc_central.section_tab c ON a.section_id = c.id
					LEFT JOIN ipc_central.department_tab d ON d.id = a.department_id 
				WHERE a.employee_no = :employee_no LIMIT 1";
		$results = $conn->query($sql,array(":employee_no" => $employee_no));
		return (!empty($results) ? (object)$results[0] : $results);
	}

	public function getDriverName($driver_no){
		$name = "";
		if(strpos($driver_no,"DRV") === 0) {
			$driver_no = Format::reformatDriverId($driver_no);
			$driver_details = $this->getDriverDetails($driver_no);
			$name = Format::makeUpperCase($driver_details->first_name . " " . substr($driver_details->middle_name,0,1) . ". " . $driver_details->last_name);
		}
		// the driver is an employee
		else if(strlen($driver_no) == 6){ 
			$driver_details = $this->getEmployeeDetailsByNo($driver_no);
			$name = Format::makeUpperCase($driver_details->first_name . " " . substr($driver_details->middle_name,0,1) . ". " . $driver_details->last_name);
		}
		return $name;
	}

	public function deleteDriver($id,$user){
		$conn = Connection::getInstance();
		$sql = "UPDATE company_drivers_master SET active = 0, update_user = :update_user, date_updated = NOW() WHERE id = :id";
		$result = $conn->query($sql,array(":id"=>$id,":update_user"=>$user));
		return $result;
	}

	public function addDriver($first_name,$middle_name,$last_name,$contact_no,$pic,$attachment,$company,$vehicle_id,$user){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO company_drivers_master(first_name,middle_name,last_name,contact_no,picture,attachment,company,assigned_vehicle_id,create_user,date_created)
				VALUES(:first_name,:middle_name,:last_name,:contact_no,:pic,:attachment,:company,:vehicle_id,:create_user,NOW())";
		$driver_id = $conn->query($sql,array(
										":first_name"	=>	$first_name,
										":middle_name"	=>	$middle_name,
										":last_name"	=>	$last_name,
										":contact_no"	=>	$contact_no,
										":pic"			=>	$pic,
										":attachment"	=>	$attachment,
										":company"		=>	$company,
										":vehicle_id"	=>	$vehicle_id,
										":create_user"	=>	$user
										)
		);
		$driver_no = Format::formatDriverId($driver_id);
		$sql = "UPDATE company_drivers_master SET driver_no = :driver_no WHERE id = :id";
		$result = $conn->query($sql,array(":driver_no"=>$driver_no,":id"=>$driver_id));
	}

	public function getDriverInfo($id){
		$conn = Connection::getInstance();
		$sql = "SELECT id,first_name,middle_name,last_name,contact_no,picture,attachment,company,assigned_vehicle_id FROM company_drivers_master WHERE id = :id";
		$result = $conn->query($sql,array(":id"=>$id));
		return $result[0];
	}

	public function updateDriverInfo($id,$fname,$mname,$lname,$contact_no,$picture,$attachment,$company,$assigned_vehicle_id,$user){
		$conn = Connection::getInstance();
		$sql = "UPDATE company_drivers_master SET first_name = :first_name, 
												  middle_name = :middle_name,
												  last_name = :last_name,
												  contact_no = :contact_no,
												  picture = :picture,
												  attachment = :attachment,
												  company = :company,
												  assigned_vehicle_id = :assigned_vehicle_id,
												  update_user = :user,
												  date_updated = NOW() 
				WHERE id = :id";
		$result = $conn->query($sql,array(
										":first_name"=>$fname,
										":middle_name"=>$mname,
										":last_name"=>$lname,
										":contact_no"=>$contact_no,
										":picture"=>$picture,
										":attachment"=>$attachment,
										":company"=>$company,
										":assigned_vehicle_id"=>$assigned_vehicle_id,
										":user"=>$user,
										":id"=>$id
									)
							   );
	}

	public function getEmployeesList(){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
				       FormatName(b.first_name,b.middle_name,b.last_name) emp_name  
				FROM
				  ipc_central.employee_masterfile_tab a 
				  INNER JOIN ipc_central.personal_information_tab b 
				    ON a.id = b.employee_id
				  ORDER BY b.last_name,b.first_name";
		$result = $conn->query($sql,false);
		return $result;
	}

	public function getSignatory($section_id){
		$conn = Connection::getInstance();
		$sql = "SELECT CASE WHEN ash <> 0 THEN ash
					        WHEN sh <> 0 THEN sh
					        WHEN adh <> 0 THEN adh
					        WHEN dh <> 0 THEN dh
					        WHEN dvh <> 0 THEN dvh
					        WHEN advh <> 0 THEN advh 
					        WHEN avp <> 0 THEN avp
					   END AS signatory
				FROM ipc_central.signatories_matrix_tab 
				WHERE section_id = :section LIMIT 1";
		$result = $conn->query($sql,array(":section"=>$section_id));
		return (!empty($result) ? (object)$result[0] : $result);
	}

	public function getCompanyDriversList(){
		$conn = Connection::getInstance();
		$sql = "SELECT id,
		               driver_no,
		               driver_name,
		               date_created,
		               picture,
		               company
		        FROM v_company_drivers_list";
		 $result = $conn->query($sql,false);
		 return $result;
	}

	
}