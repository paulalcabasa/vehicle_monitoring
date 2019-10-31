<?php

class Barcode {

	public function addBarcode($value,$label){
		$conn = Connection::getInstance();
		$sql = "INSERT INTO temp_barcode_generator(barcode_value,barcode_label) VALUES(:barcode_value,:barcode_label)";
		$result = $conn->query($sql,array(":barcode_value"=>$value,":barcode_label"=>$label));
	}

	public function emptyTable(){
		$conn = Connection::getInstance();
		$conn->query("TRUNCATE temp_barcode_generator");
	}

	public function getBarcodes(){
		$conn = Connection::getInstance();
		return $conn->query("SELECT * FROM temp_barcode_generator");
	}
}