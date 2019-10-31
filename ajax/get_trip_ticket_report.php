<?php
include("../initialize.php");
$post = (object)$_POST;
$trip_ticket = new TripTicket();
$report_result = $trip_ticket->getTripTicketReport(
	$post->start_date,
	$post->end_date,
	$post->search_by,
	$post->vehicle_id
);
if(!empty($report_result)) {
	foreach($report_result as $row){
		$row = (object)$row;
		$time_in = ($row->time_in != "" ? Format::format_date_slash($row->time_in) : "");
		$time_out = ($row->time_out != "" ? Format::format_date_slash($row->time_out) : "");
		echo "<tr>";
			echo "<td>" . $row->trip_ticket_no . "</td>";
			echo "<td>" . $row->jo_no . "</td>";
			echo "<td>" . $row->plate_no . "</td>";
			echo "<td>" . $row->purpose . "</td>";
			echo "<td>" . $row->destination . "</td>";
			echo "<td>" . $row->trip_type . "</td>";
			echo "<td>" . Format::makeUppercase($row->driver_name) . "</td>";
			echo "<td>" . Format::format_date($row->date_requested) . "</td>";
			echo "<td>" . Format::format_date($row->expected_time_of_return) . "</td>";
			echo "<td>" . $row->trip_status . "</td>";
			echo "<td>" . $time_out . "</td>";
			echo "<td>" . $time_in . "</td>";
		echo "</tr>";
	}
}
else {
	echo "<tr>";
	echo "<td colspan='10' align='center'>No records found.</td>";
	echo "</tr>";
}