<?php

class Destination {

	public function getDestination(){
		$conn = Connection::getInstance();
		$sql = "SELECT id,
					   destination,
					   zip_code,
					   area_code,
					   toll_fee,
					   distance
				FROM destination_master
				ORDER BY destination ASC";
		$result = $conn->query($sql,false);
		return $result;
	}

	public function addDestination($destination,$zip_code,$area_code,$toll_fee,$distance,$create_user){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO destination_master(destination,zip_code,area_code,toll_fee,distance,date_created,create_user)
				VALUES(:destination,:zip_code,:area_code,:toll_fee,:distance,NOW(),:create_user)";
		$result = $conn->query($sql,array(
										":destination" => $destination,
										":zip_code"    => $zip_code,
										":area_code"   => $area_code,
										":toll_fee"    => $toll_fee,
										":distance"    => $distance,
										":create_user" => $create_user
									)
							  );
	}

	public function getDestinationDetails($id){
		$conn = Connection::getInstance();
		$sql = "SELECT id,destination,zip_code,area_code,toll_fee,distance FROM destination_master WHERE id = :id LIMIT 1";
		$result = $conn->query($sql,array(":id"=>$id));
		return (!empty($result) ? (object)$result[0] : $result);
	}

	public function getDestinationDetailsByDest($destination){
		$conn = Connection::getInstance();
		$sql = "SELECT id,destination,zip_code,area_code,toll_fee,distance 
				FROM destination_master 
				WHERE destination = :destination";
		$result = $conn->query($sql,array(":destination"=>$destination));
		return (!empty($result) ? (object)$result[0] : $result);
	}

	public function updateDestination($id,$destination,$zip_code,$area_code,$toll_fee,$distance,$update_user){
		$conn = Connection::getInstance();
		$sql = "UPDATE destination_master 
				SET destination = :destination,
				    zip_code = :zip_code,
				    area_code = :area_code,
				    toll_fee = :toll_fee,
				    distance = :distance,
				    update_user = :update_user
				WHERE id = :id";
		$result = $conn->query($sql,array(
								   	 ":destination" => $destination,
								   	 ":zip_code"    => $zip_code,
								   	 ":area_code"   => $area_code,
								   	 ":toll_fee"    => $toll_fee,
								   	 ":area_code"   => $area_code,
								   	 ":distance"    => $distance,
								   	 ":update_user" => $update_user,
								   	 ":id" 			=> $id
								   )
	 					     );
	}

}