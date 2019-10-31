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
											<span>Emergency Trip Ticket</span>
										</h1>
										<p></p>
										<b style="text-align: left;font-size: 13px;">Trip Ticket No</b>
										<p style="font-size: 16px;font-weight:bold;color:red;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><u><?php echo $trip_ticket_no; ?></u></p>
										<b style="text-align: left;font-size: 13px;">Jo No.</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $jo_no; ?></p>
										<b style="text-align: left;font-size: 13px;">Plate No</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $plate_no; ?></p>
										<b style="text-align: left;font-size: 13px;">CS No.</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $cs_no; ?></p>
										<b style="text-align: left;font-size: 13px;">Driver</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $driver_name; ?></p>
										<b style="text-align: left;font-size: 13px;">Passengers</b>
										<p style="font-size: 12px;margin: 0;padding: 0;">
											<ol style="font-size:12px;">
												<?php echo $passengers;?>
											</ol>
										</p>
										<b style="text-align: left;font-size: 13px;">Nature of Trip</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $nature_of_trip; ?></p>
										<b style="text-align: left;font-size: 13px;">Purpose</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $purpose; ?></p>
										<b style="text-align: left;font-size: 13px;">Destination</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $destination; ?></p>
										<b style="text-align: left;font-size: 13px;">Expected Time of Return</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $etr; ?></p>
										<b style="text-align: left;font-size: 13px;">Requested By</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $requestor_name; ?></p>
										<b style="text-align: left;font-size: 13px;">Prepared By</b>
										<p style="font-size: 12px;line-height: 22px;margin: 0;padding: 0;margin-bottom: 24px;"><?php echo $prepared_by; ?></p>
									</td>
								</tr>
								<!-- <tr>
									<td style="background-color: #ffffff;text-align: center;padding: 40px;">
										<p style="text-align: center;margin-bottom: 24px;font-size: 14px;">Just simply click or <br /> copy the link to the browser below to approve the trip ticket. 
										</p>
										<a href="<?php echo $approval_link . $enc_trip_ticket_no . '&a=' . $approver_id; ?>" style="color:#333333;font-size: 13px;">
											<span style="font-size: 13px;color:#333333">
												<?php echo $approval_link . $enc_trip_ticket_no . '&a=' . $approver_id; ?>
											</span>
										</a>
										</div>
									</td>
								</tr> -->
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
									<td style="font-size: 12px;text-align: center;">&copy; <?php echo date('Y');?> Management Information System. All Rights Reserved.<br>
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
