<?php
	require_once("../initialize.php");
	$vehicle = new Vehicle();
	$post = (object)$_POST;
	$vehicle_usage_report = $vehicle->getVehicleUsageReport($post->start_date,$post->end_date,$post->vehicle_id,$post->driver_no);
	foreach($vehicle_usage_report as $usage) {
		$usage = (object)$usage;
?>
	<tr>
		<td><?php echo $usage->cs_no;?></td>
		<td><?php echo $usage->plate_no;?></td>
		<td><?php echo $usage->driver;?></td>
		<td>
			<a href="#" class="btn_view_passengers_in" data-passengers="<?php echo $usage->passengers_in; ?>">
				<?php echo $usage->passengers_in_count;?>
			</a>
		</td>
		<td>
			<a href="#" class="btn_view_passengers_out" data-passengers="<?php echo $usage->passengers_out; ?>">
				<?php echo $usage->passengers_out_count;?>
			</a>
		</td>
		<td><?php echo Format::format_date_slash($usage->expected_time_of_return);?></td>
		<td><?php echo $usage->trip_status;?></td>
		<td><?php echo ($usage->time_in!="" ? Format::format_date_slash($usage->time_in) : "N/A" );?></td>
		<td><?php echo ($usage->time_out!="" ? Format::format_date_slash($usage->time_out) : "N/A");?></td>
		<td><?php echo ($usage->km_in!="" ? $usage->km_in : "N/A");?></td>
		<td><?php echo ($usage->km_out!="" ? $usage->km_out : "N/A");?></td>
		<td><?php echo ($usage->fuel_in!="" ? $usage->fuel_in : "N/A");?></td>
		<td><?php echo ($usage->fuel_out!="" ? $usage->fuel_out : "N/A");?></td>
	</tr>
<?php
	}
?>