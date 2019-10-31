<?php

require_once("../initialize.php");

// convert $_POST global variable to object
$post = (object)$_POST;
$destination = new Destination();
$destination_details = $destination->getDestinationDetailsByDest($post->destination);
if(!empty($destination_details)){
	//$destination_details['toll_fee']  = ($destination_details['toll_fee'] != "" ? "PHP " . $destination_details['toll_fee'] : "");
	echo json_encode($destination_details);
}
else {
	echo "none";
}
