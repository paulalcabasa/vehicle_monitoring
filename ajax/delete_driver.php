<?php
require_once("../initialize.php");
$post = (object)$_POST;
$driver = new Driver();
$user_data = (object)$_SESSION['user_data'];
echo $driver->deleteDriver($post->driver_id,$user_data->employee_id);

