<?php

require_once("../initialize.php");

// convert $_POST global variable to object
$post = (object)$_POST;
$driver = new Driver();
$driver_details = $driver->getDriverInfo($post->driver_id);
echo json_encode($driver_details);
