<?php

include("../initialize.php");

$post = (object)$_POST;
$tripticket = new TripTicket();

echo $tripticket->get_fuel_rate($post->trip_ticket_no);