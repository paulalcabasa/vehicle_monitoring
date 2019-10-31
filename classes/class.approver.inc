<?php
class Approver {

	public function addTripTicketApprover($trip_ticket_no,$list_of_approvers){
		$conn = Connection::getInstance();
		foreach($list_of_approvers as $approver){
			$approver = (object)$approver;
			$sql = "INSERT INTO trip_ticket_approval(trip_ticket_no,approver_id,sequence)
				 	VALUES(:trip_ticket_no,:approver_id,:sequence)";
			$result = $conn->query($sql,array(
											":trip_ticket_no" => $trip_ticket_no,
											":approver_id"	  => $approver->employee_id,
											":sequence"		  => $approver->sequence
										)
			);
		}
	}

	public function addApprovedTripTicket($trip_ticket_no,$list_of_approvers){
		$conn = Connection::getInstance();
		foreach($list_of_approvers as $approver){
			$approver = (object)$approver;
			$sql = "INSERT INTO trip_ticket_approval(trip_ticket_no,approver_id,sequence,approved_by,is_approved,date_approved)
				 	VALUES(:trip_ticket_no,:approver_id,:sequence,:approver_id,1,NOW())";
			$result = $conn->query($sql,array(
											":trip_ticket_no" => $trip_ticket_no,
											":approver_id"	  => $approver->employee_id,
											":sequence"		  => $approver->sequence
										)
			);
		}
	}

	public function approveTripTicket($trip_ticket_no,$approver_id){
		$conn = Connection::getInstance();
		$sql = "UPDATE trip_ticket_approval SET is_approved = 1,
												approved_by = :approver_id,
												date_approved = NOW()
				WHERE trip_ticket_no = :trip_ticket_no AND 
					  approver_id = :approver_id";

		$result = $conn->query($sql,array(
										":trip_ticket_no"=>$trip_ticket_no,
										":approver_id"=>$approver_id
									)	
							  );
	}

	public function isApproved($trip_ticket_no,$approver_id){
		$conn = Connection::getInstance();
		$sql = "SELECT is_approved 
				FROM trip_ticket_approval 
				WHERE trip_ticket_no = :trip_ticket_no AND approver_id = :approver_id";
		$result = $conn->query($sql,array(
										":trip_ticket_no"=>$trip_ticket_no,
										":approver_id"=>$approver_id
									)	
							  );
		$details = (object)$result[0];
		if($details->is_approved == 1){
			return true;
		}
		else {
			return false;
		}
	}

	public function getApproverSequence($trip_ticket_no,$approver_id){
		$conn = Connection::getInstance();
		$sql = "SELECT sequence 
				FROM trip_ticket_approval 
				WHERE trip_ticket_no = :trip_ticket_no AND approver_id = :approver_id";
		$result = $conn->query($sql,array(
										":trip_ticket_no"=>$trip_ticket_no,
										":approver_id"=>$approver_id
									)	
							  );
		$details = (object)$result[0];
		return $details->sequence;
	}

	public function countApprovers($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT COUNT(id) approver_count FROM trip_ticket_approval WHERE trip_ticket_no = :trip_ticket_no";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		$details = (object)$result[0];
		return $details->approver_count;
	}

	public function getApproverBySequence($trip_ticket_no,$sequence){
		$conn = Connection::getInstance();
		$sql = "SELECT approver_id FROM trip_ticket_approval WHERE trip_ticket_no = :trip_ticket_no AND sequence = :sequence";
		$result = $conn->query($sql,array(
										":trip_ticket_no"=>$trip_ticket_no,
										":sequence"=>$sequence
									)	
							  );
		$details = (object)$result[0];
		return $details->approver_id;
	}

	public function getApproverByTripTicketNo($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
					   a.approver_id,
					   FormatName(b.first_name,b.middle_name,b.last_name) approver_name,
					   a.is_approved,
					   a.date_approved
				FROM trip_ticket_approval a LEFT JOIN ipc_central.personal_information_tab b 
						ON a.approver_id = b.employee_id
				WHERE trip_ticket_no = :trip_ticket_no";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		return $result;
	}

	public function checkApproval($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT id,approver_id,is_approved FROM trip_ticket_approval WHERE trip_ticket_no = :trip_ticket_no";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		$approver_count = 0;
		$approved_count = 0;
		foreach($result as $row){
			$row = (object)$row;
			if($row->is_approved == 1){
				$approved_count++;
			}
			$approver_count++;
		}
		if($approver_count == $approved_count){
			return "approved";
		}
		else {
			return "not approved";
		}
	}

	public function approveOnBehalfOf($trip_ticket_no,$approver_id,$approved_by){
		$conn = Connection::getInstance();
		$sql = "UPDATE trip_ticket_approval SET is_approved = 1,
												approved_by = :approved_by,
												date_approved = NOW()
				WHERE trip_ticket_no = :trip_ticket_no AND 
					  approver_id = :approver_id";

		$result = $conn->query($sql,array(
										":trip_ticket_no" => $trip_ticket_no,
										":approver_id" => $approver_id,
										":approved_by" => $approved_by
									)	
							  );
	}
	
	public function getApproverByTripTicketNoPending($trip_ticket_no){
		$conn = Connection::getInstance();
		$sql = "SELECT a.id,
					   a.approver_id,
					   FormatName(b.first_name,b.middle_name,b.last_name) approver_name,
					   a.is_approved,
					   a.date_approved
				FROM trip_ticket_approval a LEFT JOIN ipc_central.personal_information_tab b 
						ON a.approver_id = b.employee_id
				WHERE trip_ticket_no = :trip_ticket_no AND a.is_approved IS NULL";
		$result = $conn->query($sql,array(":trip_ticket_no"=>$trip_ticket_no));
		return $result;
	}
}