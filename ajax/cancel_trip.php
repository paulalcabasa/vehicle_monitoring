<?php
require_once("../initialize.php");
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$tripticket = new TripTicket();

$tripticket->cancelTrip(
				$post->trip_ticket_no,
				$user_data->employee_id
			);
