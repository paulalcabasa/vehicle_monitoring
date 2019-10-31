<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$vehicle = new Vehicle();
$vehicle->updateVehicleStatus(Format::reformatVehicleId($post->vehicle_id),$post->status);