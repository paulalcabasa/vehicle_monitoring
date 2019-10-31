<?php

include("../initialize.php");

$post = (object)$_POST;
$ticket = new TripTicket();
$user_data = (object)$_SESSION['user_data'];

$ticket->update_fuelrate(
	$post->ticketno,
	$post->fuelrate,
	$user_data->employee_id);


echo $tripticket->get_fuel_rate($post->trip_ticket_no);
// echo "Successfully added!";
// echo "Successfully added <strong>{$post->destination}</strong> in the list of destinations!";