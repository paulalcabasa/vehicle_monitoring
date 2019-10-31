<?php

class Manager {

	public function addAttendance($employee_id,$employee_no,$create_user) {
		$conn = Connection::getInstance();
		$sql = "INSERT INTO managers_attendance(employee_id,employee_no,log_time,create_user)
				VALUES(:employee_id,:employee_no,NOW(),:create_user)";
		$result = $conn->query($sql,array(
										":employee_id"	=>	$employee_id,
										":employee_no"  =>  $employee_no,
										":create_user"  =>  $create_user
									)
								);
	}

	public function getAllManagersAttendance($start_date,$end_date){
		$conn = Connection::getInstance();
		$pos1 = "DEPARTMENT HEAD";
		$pos2 = "ASST. DEPT. HEAD";
		$pos3 = "ASST. SECTION HEAD";
		$pos4 = "SECTION HEAD";
		$pos5 = "ASST. DIVISION HEAD";
		$pos6 = "DIVISION HEAD";
		$pos7 = "ASST. VICE PRESIDENT-ADMIN";
		$pos8 = "EXECUTIVE VICE PRESIDENT";
		$pos9 = "VICE PRESIDENT";
		$pos10 = "VP-ADMIN";
		$pos11 = "VP-MARKETING & SALES";
		$pos12 = "SENIOR VICE PRESIDENT";

		$sql = "SELECT a.employee_id,
				       a.employee_no,
				       FormatName(b.first_name,b.middle_name,b.last_name) employee_name,
				       a.remarks,
				       DATE(a.log_time) log_date,
				       MIN(a.log_time) time_in,
				       CASE WHEN COUNT(a.id) <= 1 THEN NULL ELSE MAX(a.log_time) END AS time_out 
				FROM managers_attendance a 
					 LEFT JOIN ipc_central.personal_information_tab b
					 	ON a.employee_id = b.employee_id
					 LEFT JOIN ipc_central.employee_masterfile_tab c 
					 	ON c.id = a.employee_id
				WHERE DATE(a.log_time) BETWEEN :start_date AND :end_date
				AND (c.position = :pos1 OR c.position = :pos2 OR c.position = :pos3 OR c.position = :pos4
					 OR c.position = :pos5 OR c.position = :pos6 OR c.position = :pos7 OR c.position = :pos8
					 OR c.position = :pos9 OR c.position = :pos10 OR c.position = :pos11 OR c.position = :pos12)
				GROUP BY DATE(a.log_time),
					 a.employee_id
				ORDER BY DATE(a.log_time),b.last_name, b.first_name";
		$result = $conn->query($sql,array(
										":start_date"=>$start_date,
										":end_date"=>$end_date,
										":pos1"=>$pos1,
										":pos2"=>$pos2,
										":pos3"=>$pos3,
										":pos4"=>$pos4,
										":pos5"=>$pos5,
										":pos6"=>$pos6,
										":pos7"=>$pos7,
										":pos8"=>$pos8,
										":pos9"=>$pos9,
										":pos10"=>$pos10,
										":pos11"=>$pos11,
										":pos12"=>$pos12
									)
							  );
		return $result;
	}

	public function addObAttendance($employee_id,$employee_no,$input_start_date,$input_end_date,$remarks,$create_user,$trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO managers_attendance(employee_id,employee_no,log_time,trip_ticket_no,remarks,create_user)
				VALUES(:employee_id,:employee_no,:log_time,:trip_ticket_no,:remarks,:create_user)";

		$start_date = Format::format_date_only($input_start_date);
		// Start date
		$date = Format::format_date_only($input_start_date);
		// End date
		$end_date = Format::format_date_only($input_end_date);
		// time start
		$time_start = Format::format_time_only($input_start_date);
		// time end
		$time_end = Format::format_time_only($input_end_date);
		// regular time start
		$regular_time_start = "07:30:00";
		// regular time end
		$regular_time_end = "17:45:00";

		$log_time = "";
		while (strtotime($date) <= strtotime($end_date)) {
			$current_date = $date;
			if($current_date == $start_date){
				$log_time = $date . " " . $time_start;
				$this->queryObAttendance($conn,$sql,$employee_id,$employee_no,$log_time,$remarks,$create_user,$trip_ticket_no);
			}
			else {
				$log_time = $date . " " . $regular_time_start;
				$this->queryObAttendance($conn,$sql,$employee_id,$employee_no,$log_time,$remarks,$create_user,$trip_ticket_no);
			}
			if($current_date == $end_date){
				$log_time = $date . " " . $time_end;
				$this->queryObAttendance($conn,$sql,$employee_id,$employee_no,$log_time,$remarks,$create_user,$trip_ticket_no);
			}
			else {
				$log_time = $date . " " . $regular_time_end;
				$this->queryObAttendance($conn,$sql,$employee_id,$employee_no,$log_time,$remarks,$create_user,$trip_ticket_no);
			}
			// echo date("l", strtotime($date)); used to check for Days if needed
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
	}
	
	public function queryObAttendance($conn,$sql,$employee_id,$employee_no,$log_time,$remarks,$create_user,$trip_ticket_no){
		$result = $conn->query($sql,array(
					":employee_id" 		=> $employee_id,
					":employee_no" 		=> $employee_no,
					":log_time"    		=> $log_time,
					":trip_ticket_no"   => $trip_ticket_no,
					":remarks"     		=> $remarks,
					":create_user" 		=> $create_user
				)
			);
	}

	public function removeObAttendance($trip_ticket_no,$employee_id,$employee_no){
		$sql = "DELETE 
				FROM managers_attendance 
				WHERE employee_id = :employee_id
					  AND trip_ticket_no = :trip_ticket_no
					  AND employee_no = :employee_no";
			$result = $conn->query($sql,array(
										":employee_id"=>$employee_id,
										":trip_ticket_no"=>$trip_ticket_no,
										":employee_no"=>$employee_no
									)
							  );
		return $result;
	}

	public function getManagersList(){
		$conn = Connection::getInstance();
		$sql = "SELECT emt.rank,
       				   CONCAT(pit.last_name,', ',pit.first_name,' ',(CASE WHEN (pit.middle_name IS NOT NULL) THEN CONCAT(LEFT(pit.middle_name,1),'.') ELSE '' END)) AS employee_name,
				       emt.employee_no,
				       emt.position,
				       st.status
				FROM ipc_central.employee_masterfile_tab emt LEFT JOIN ipc_central.personal_information_tab pit
						ON emt.id = pit.employee_id
				     LEFT JOIN ipc_central.status_tab st
						ON st.id = emt.status_id
					 LEFT JOIN ipc_central.section_tab sct
						ON sct.id = emt.section_id
				WHERE 1 = 1
				      AND emt.rank >= 8
				      AND emt.status_id in (1,2,3,4)
				      AND UPPER(emt.position) NOT LIKE '%SPECIALIST%'
				      AND emt.section_id <> 38
				ORDER BY pit.last_name,
						 pit.first_name,
						 pit.middle_name";
		$result = $conn->query($sql,false);
		return $result;
	}

}