<?php

include("../initialize.php");

$post = (object)$_POST;
$vehicle = new Vehicle();
// $user_data = (object)$_SESSION['user_data'];

$vehicle->delete_vehicle_use_rfid(
	$post->rfid
	// $user_data->employee_id
);

// echo "Successfully added!";
// echo "Successfully added <strong>{$post->destination}</strong> in the list of destinations!";