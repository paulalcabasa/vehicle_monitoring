<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$destination = new Destination();
$destination->updateDestination(
	$post->destination_id,
	$post->destination,
	$post->zip_code,
	$post->area_code,
	$post->toll_fee,
	$post->distance,
	$user_data->employee_id);
echo "Successfully added <strong>{$post->destination}</strong> in the list of destinations!";