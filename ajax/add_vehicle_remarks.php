<?php

include("../initialize.php");
$vehicle = new Vehicle();
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$vehicle->addRemarks($post->vehicle_id,$post->remarks,$user_data->employee_id);