<?php
	require_once("../initialize.php");
	$fleet_card = new FleetCard();
	$post = (object)$_POST;
	$fleetcard_monitoring_report = $fleet_card->getFleetCardMonitoringReport($post->start_date,$post->end_date);
	foreach($fleetcard_monitoring_report as $report) {
		$report = (object)$report;
?>
	<tr>
		<td><?php echo $report->fleet_card_id;?></td>
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