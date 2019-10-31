<?php

include("../initialize.php");

$post = (object)$_POST;
$vehicle = new Vehicle();
$user_data = (object)$_SESSION['user_data'];

$vehicle->add_vehicle_use_rfid(
	$post->mdl_plate_no,
	$post->txt_tag_no,
	$post->txt_transaction_date,
	$post->txt_entry_plaza,
	$post->txt_exit_plaza,
	$post->txt_amount,
	$post->trip_ticket,
	$user_data->employee_id);

// echo "Successfully added!";
// echo "Successfully added <strong>{$post->destination}</strong> in the list of destinations!";