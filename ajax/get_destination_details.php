<?php

require_once("../initialize.php");

// convert $_POST global variable to object
$post = (object)$_POST;
$destination = new Destination();
$destination_details = $destination->getDestinationDetails($post->id);
echo json_encode($destination_details);
