<?php
	include("../initialize.php");
	$vehicle = new Vehicle();
	$post = (object)$_POST;
	$managers_attendance = $vehicle->getManagersAttendanceByDate($post->start_date,$post->end_date,$post->employee_id);
	$ctr = 1;
	foreach($managers_attendance as $attendance){
		$attendance = (object)$attendance;
		$time_in = "";
		$time_out = "";
		if($attendance->time_in != ""){
			$time_in = Format::format_12h_time($attendance->time_in);
		}
		if($attendance->time_out != ""){
			$time_out = Format::format_12h_time($attendance->time_out);
		}

		$remarks = "";
		
		if($attendance->remarks!=""){
			$remarks = " - " . $attendance->remarks;
		}

		echo "<tr>";
			echo "<td>" . $ctr . "</td>";
			echo "<td>" . Format::format_date_with_day($attendance->log_date) . $remarks . "</td>";
			echo "<td>" . $time_in . "</td>";
			echo "<td>" . $time_out . "</td>";
		echo "</tr>";

		$ctr++;
	}