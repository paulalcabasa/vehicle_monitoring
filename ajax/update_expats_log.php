<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$expats = new Expats();
$vehicle_id = Format::reformatVehicleId($post->vehicle_id);
$expats->updateTimeLog(
	$post->log_id,
	$post->log_classification_id,
	$post->log_type,
	$post->driver_no,
	$post->driver_name,
	$vehicle_id,
	$user_data->employee_id
);
