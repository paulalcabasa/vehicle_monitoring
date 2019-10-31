<?php
include("initialize.php");
$trip_ticket = new TripTicket();


$overdue_trips = $trip_ticket->get_overdue_trip_tickets();

foreach($overdue_trips as $row){
	$email = new Email();
	$email->send_overdue_mail_notif($row);
	sleep(10);
}
