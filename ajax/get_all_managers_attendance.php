<?php
	include("../initialize.php");
	$manager = new Manager();
	$post = (object)$_POST;
	$managers_attendance = $manager->getAllManagersAttendance($post->start_date,$post->end_date);
	foreach($managers_attendance as $attendance){
		$attendance = (object)$attendance;
		$time_in = "";
		$time_out = "";
		$remarks = "";
		
		if($attendance->remarks!=""){
			$remarks = " - " . $attendance->remarks;
		}

		if($attendance->time_in != ""){
			$time_in = Format::format_12h_time($attendance->time_in);
		}
		if($attendance->time_out != ""){
			$time_out = Format::format_12h_time($attendance->time_out);
		}
		echo "<tr>";
			echo "<td>" . $attendance->employee_no . "</td>";
			echo "<td>" . Format::makeUpperCase($attendance->employee_name) . "</td>";
			echo "<td>" . Format::format_date_with_day($attendance->log_date) . $remarks . "</td>";
			echo "<td>" . $time_in . "</td>";
			echo "<td>" . $time_out . "</td>";
		echo "</tr>";
	}