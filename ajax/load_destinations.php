<?php
require_once("../initialize.php");
$trip_ticket = new TripTicket();
echo json_encode(array_column($trip_ticket->getDestinations(),"destination"));
