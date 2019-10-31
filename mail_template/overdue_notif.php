<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
	</head>
	<body style=" margin: 0;padding: 0;">
		<table  cellspacing="0" cellpadding="0" border="1" style="width:100%;background-color: #ebebeb;font-family: arial,sans-serif;color: #a1a2a5;border-collapse: collapse;">
			<tbody>
				<tr>
					<td align="center" valign="top" style="padding:30px 0;">
						<table style="font-family: arial,sans-serif;margin-left: auto;margin-right: auto;width: 544px;" border="0" cellpadding="0" cellspacing="0" width="544">
							<tbody>
								<tr>
									<td style="font-family: arial,sans-serif;background-color: #d73925; padding: 20px;padding-bottom:5px;padding-left:10px;color: #ffffff;font-weight: bold;font-size: 16px;">
										Vehicle Monitoring System
									</td>
								</tr>
								<tr>
									<td style="background-color: #f6f6f7;padding: 15px;">
										<h1 style="text-align: center;;font-size: 24px;">
											<span>Trip ticket expiration notice</span>
										</h1>
										<p>Vehicle with plate no. <strong><?php echo $plate_no == "" ? $cs_no : $plate_no; ?></strong> under trip ticket number <strong><?php echo $trip_ticket_no; ?></strong> must be returned to IPC on <strong><?php echo $expected_time_of_return; ?></strong>.</p>
										<p>Please give explanation to CSS within the day regarding the delay of return for the said unit.</p>
								<!-- 		<b style="text-align: left;font-size: 13px;">Trip Ticket No</b>
										<p style="font-size: 16px;font-weight:bold;color:red;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><u><?php echo $trip_ticket_no; ?></u></p>
								 -->		
								 		<b style="text-align: left;font-size: 13px;">Jo No.</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $jo_no; ?></p>
										<!-- <b style="text-align: left;font-size: 13px;">Plate No</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $plate_no; ?></p>
										<b style="text-align: left;font-size: 13px;">CS No.</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $cs_no; ?></p> -->
										<b style="text-align: left;font-size: 13px;">Driver</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $driver_name; ?></p>
										<!-- <b style="text-align: left;font-size: 13px;">Passengers</b>
										<p style="font-size: 12px;margin: 0;padding: 0;">
											<ol style="font-size:12px;">
												<?php echo $passengers;?>
											</ol>
										</p> -->
									<!-- 	<b style="text-align: left;font-size: 13px;">Nature of Trip</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $nature_of_trip; ?></p>
										 -->
										 <b style="text-align: left;font-size: 13px;">Purpose</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $purpose; ?></p>
										<b style="text-align: left;font-size: 13px;">Destination</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $destination; ?></p>
										<!-- <b style="text-align: left;font-size: 13px;">Expected Time of Return</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $expected_time_of_return; ?></p>
										 --><b style="text-align: left;font-size: 13px;">Requested By</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $requestor_name; ?></p>
										<!-- <b style="text-align: left;font-size: 13px;">Prepared By</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $prepared_by; ?></p> -->
									</td>
								</tr>
								<tr>
									<td class="bottomCorners">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tbody>
												<tr>
													<td>&nbsp;</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="font-size: 12px;text-align: center;">&copy; 2015 Management Information System. All Rights Reserved.<br>
										<p>IPC Centralized Database Portal</p>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
