<?php

include("../initialize.php");
$post = (object)$_POST;
$tripticket = new TripTicket();

$tripticket->deletePassenger($post->id,$post->trip_ticket_no);
echo "deleted";