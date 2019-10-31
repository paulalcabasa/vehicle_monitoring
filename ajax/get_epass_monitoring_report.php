<?php
	require_once("../initialize.php");
	$epass = new Epass();
	$post = (object)$_POST;
	$epass_monitoring_report = $epass->getEpassMonitoringReport($post->start_date,$post->end_date);
	foreach($epass_monitoring_report as $report) {
		$report = (object)$report;
?>
	<tr>
		<td><?php echo $report->e_pass_id;?></td>
		<td><?php echo $report->plate_no;?></td>
		<td><?php echo $report->cs_no;?></td>
		<td><?php echo $report->classification;?></td>
		<td><?php echo $report->driver_name;?></td>
		<td><?php echo Format::format_date($report->log_time);?></td>
		<td><?php echo $report->log_type;?></td>
	</tr>
<?php
	}

?>