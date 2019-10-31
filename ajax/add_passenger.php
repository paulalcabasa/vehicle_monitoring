<?php

include("../initialize.php");

$post = (object)$_POST;
$tripticket = new TripTicket();

echo $tripticket->addPassenger($post->trip_ticket_no,$post->passenger_name);