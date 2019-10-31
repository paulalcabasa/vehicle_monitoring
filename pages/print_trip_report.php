<?php
require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");
$get = (object)$_GET;
$trip_ticket = new TripTicket();
$encryption = new Encryption();
$driver = new Driver();
$vehicle = new Vehicle();
$approver = new Approver();
$trip_ticket_no = $encryption->decrypt($get->d);
$trip_details = $trip_ticket->getCompleteTripTicketDetails($trip_ticket_no);
$driver_name = $driver->getDriverName($trip_details->driver_no);
$vehicle_details = $vehicle->getVehicleDetails($trip_details->vehicle_id);
$employee_details = $driver->getEmployeeDetailsById($trip_details->create_user);
$time_logs = $trip_ticket->getTimeLogsByTripTicketNo($trip_ticket_no);
$trip_ticket_passengers = "* * * NONE * * *";
$passenger_list = $trip_ticket->getTripTicketPassengers($trip_ticket_no);

if(!empty($passenger_list)){
    $trip_ticket_passengers = "";
    foreach($passenger_list as $passenger){
        $passenger = (object)$passenger;
        $trip_ticket_passengers .= "<li>" . $passenger->passenger_name . "</li>";
    }
}


$ob_date = "";
if($trip_details->ob_date_from != "" && $trip_details->ob_date_to != ""){
    $ob_date = Format::format_date($trip_details->ob_date_from) . " to " . Format::format_date($trip_details->ob_date_to);
}

$assignee = "";

if($vehicle_details->vehicle_class_id == 2){
	$assignee = $vehicle_details->assignee_name;
}
else {
	$assignee = $vehicle_details->assignee;
}

/*$checklist_no ="";
$checklist_condition="";
$checklist_attachments_list="";
$checklist_date_created="";

if($trip_details->checklist_id != 0){
    $checklist_details = $vehicle->getChecklistDetails($trip_details->checklist_id);
    $checklist_attachments = $vehicle->getChecklistAttachments($trip_details->checklist_id);
    $checklist_no = Format::formatChecklistId($checklist_details->id);
    $checklist_condition = $checklist_details->description;
    $checklist_attachments_list = "";
    foreach($checklist_attachments as $attachment){
        $attachment = (object)$attachment;
        $checklist_attachments_list .= "<a href='attachments/$attachment->attachment' target='_blank'><i class='fa fa-paperclip'></i> ".$attachment->attachment."</a><br/>";
    }
    $checklist_date_created = Format::format_date($checklist_details->date_created);
}*/


 $approval_list = $approver->getApproverByTripTicketNo($trip_ticket_no);
$approvers_list = "";
foreach($approval_list as $approval){
    $approval = (object)$approval;
    $display = "";                          
    if($approval->is_approved == 1){
        $display .=  $approval->approver_name . " <span class='label label-success'>Approved</span> at " . Format::format_date($approval->date_approved);
    }
    else {
        $display .= $approval->approver_name . " <span class='label label-warning'>Pending Approval</span>";
    }
    $approvers_list .= "<li>" . $display . "</li>";
}





// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
		$image_file = K_PATH_IMAGES.'/ss.jpg';
		$this->Image($image_file, 10, 10, 0, '', 'JPG', '', 'T', false, 0, 'R', false, false, 0, false, false, false);
		$style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, 22, 200, 22, $style);
		$html = "<span style='font-weight:normal;'>Vehicle Monitoring System</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>IPC Centralized Web Portal</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 13, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>Your Responsible Partner</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 163.5, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);

    }

    // Page footer
    public function Footer() {
		//  // Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', '', 6);
		// Page number
		$date_today = date('Y-m-d H:i:s');
		$date_create = date_create($date_today);
		$date_today = date_format($date_create,"l, F j, Y g:i:s A");
		$style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, $this->GetPageHeight()-12, 200, $this->getPageHeight()-12, $style);
		$this->Cell(0, 10, "        ".$date_today, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
    
}


// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Paul');
$pdf->SetTitle('Trip Report');
$pdf->SetSubject('Trip Report');
$pdf->SetKeywords('gp , isuzu');

// set default header data
$pdf->SetheaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setFooterData(array(0,0,0), array(0,0,0));

// set header and footer fonts
$pdf->setheaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetheaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
//$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'deptd_w'=>0.2, 'deptd_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


$trip_ticket_data_header = '<div style="width:100%;background-color:#DADFE1;font-weight:bold;font-size:12px;text-align:center;">Trip Ticket Details</div>';

$trip_ticket_data = '<table style="font-size:10px;padding:0;margin:0;">
					  	<tr>
					  		<td>
					  			<table>
					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Trip Ticket No. : </td>
					  					<td>'.$trip_details->id.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Job Order No. : </td>
					  					<td>'.$trip_details->jo_no.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Nature of Trip : </td>
					  					<td>'.$trip_details->trip_type.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">OB Date : </td>
					  					<td>'.$ob_date.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Purpose : </td>
					  					<td>'.$trip_details->purpose.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Destination : </td>
					  					<td>'.$trip_details->destination.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Requested by : </td>
					  					<td>'.$trip_details->requestor.'</td>
					  				</tr>

					  			</table>
					  		</td>

					  		<td>
					  			<table>
					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Date Requested : </td>
					  					<td>'.Format::format_date($trip_details->date_requested).'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Prepared by : </td>
					  					<td>'.Format::makeUpperCase($employee_details->first_name . " " . substr($employee_details->middle_name,0,1) . ". " . $employee_details->last_name).'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:80px;">Passengers : </td>
					  					<td style="width:100%;"><ol style="line-height:1.5;">'.$trip_ticket_passengers.'</ol></td>
					  				</tr>
					  			</table>
					  		</td>
					  	</tr>						  		
					  </table>';


$vehicle_header = '<div style="width:100%;background-color:#DADFE1;font-weight:bold;font-size:12px;text-align:center;">Vehicle and Driver Details</div>';

$vehicle_data = '<table style="font-size:10px;padding:0;margin:0;">
					  	<tr>
					  		<td>
					  			<table>
					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Vehicle ID : </td>
					  					<td>'.(!empty($vehicle_details) ? Format::formatVehicleId($trip_details->vehicle_id) : "*** TO FOLLOW ***").'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">CS No. : </td>
					  					<td>'.$vehicle_details->cs_no.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Plate No. : </td>
					  					<td>'.$vehicle_details->plate_no.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Classification : </td>
					  					<td>'.(!empty($vehicle_details) ? $vehicle_details->classification : "*** TO FOLLOW ***").'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Model : </td>
					  					<td>'.(!empty($vehicle_details) ? $vehicle_details->model : "*** TO FOLLOW ***").'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;">Body Color : </td>
					  					<td style="width:100%;">'.(!empty($vehicle_details) ? $vehicle_details->body_color : "*** TO FOLLOW ***").'</td>
					  				</tr>

					  			</table>
					  		</td>

					  		<td>
					  			<table>
					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Assignee : </td>
					  					<td>'.$assignee.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Department : </td>
					  					<td>'.$vehicle_details->department.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Section : </td>
					  					<td style="width:100%;">'.$vehicle_details->section.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Driver No : </td>
					  					<td style="width:100%;">'.$trip_details->driver_no.'</td>
					  				</tr><tr style="line-height:2;">

					  					<td style="font-weight:bold;width:100px;">Driver Name : </td>
					  					<td style="width:100%;">'.$driver_name.'</td>
					  				</tr>
					  			</table>
					  		</td>
					  	</tr>						  		
					  </table>';

$approval_header = '<div style="width:100%;background-color:#DADFE1;font-weight:bold;font-size:12px;text-align:center;">Approval and Checklist Details</div>';

$approval_data = '<table style="font-size:10px;padding:0;margin:0;">
					  	<tr>
					  		<td>
					  			<table>
					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:80px;">Approval</td>
					  					<td style="width:70%;"><ol>'.$approvers_list.'</ol></td>
					  				</tr>

					  			</table>
					  		</td>

					  		<td>
					  			<table>
					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Checklist No. : </td>
					  					<td>'.$trip_details->checklist_id.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Condition : </td>
					  					<td>'.$trip_details->vc_condition.'</td>
					  				</tr>

					  				<tr style="line-height:2;">
					  					<td style="font-weight:bold;width:100px;">Date Created : </td>
					  					<td style="width:100%;">'.$trip_details->checklist_date_created.'</td>
					  				</tr>
					  			</table>
					  		</td>
					  	</tr>						  		
					  </table>';


$time_logs_header = '<div style="width:100%;background-color:#DADFE1;font-weight:bold;font-size:12px;text-align:center;">Time Logs</div>';

$time_logs_data = '<br/><table>';
foreach($time_logs as $log){
	$log = (object)$log;

	$create_user_details = $driver->getEmployeeDetailsById($log->create_user);
	$passenger_list = $trip_ticket->getPassengersByTimeLogId($log->id);
	$list_of_passenger = "";
 	$time_logs_data .= '<tr nobr="true"><td>';
    if(!empty($passenger_list)){    

	    $list_of_passenger .= '<tr>
	    							<td style="width:63px;font-weight:bold;">Passengers : </td>
	    						<td style="width:100%;">
	    						<ol>'; 

        foreach($passenger_list as $passenger){
            $passenger = (object)$passenger;
            $list_of_passenger .= '<li>'.$passenger->passenger_name.'</li>';
        } 

        $list_of_passenger .= '</ol></td></tr>';
   		
    }

	$time_logs_data .= '<br/><table style="font-size:10px;padding:0;margin:0;border:1px solid #000;" cellpadding="5">
						<tr>
							<td colspan="2" style="font-size:11px;border-bottom:1px solid #ccc;"><span style="font-weight:bold;">'.$log->log_type.'</span> - '.Format::format_date($log->log_time).'</td>
						</tr>

						<tr>
							<td>
								<table>
									<tr>
										<td style="width:80px;font-weight:bold;">Vehicle ID : </td>
										<td>'.Format::formatVehicleId($log->vehicle_id).'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">CS No. : </td>
										<td>'.$log->cs_no.'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Plate No. : </td>
										<td>'.$log->plate_no.'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Model : </td>
										<td>'.$log->model.'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Driver ID : </td>
										<td>'.$log->driver_no.'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Driver Name : </td>
										<td>'.$driver->getDriverName($log->driver_no).'</td>
									</tr>
								
								</table>
							</td>

							<td>
								<table>
									<tr>
										<td style="width:80px;font-weight:bold;">KM Reading : </td>
										<td>'.$log->km_reading.'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Fuel Status : </td>
										<td>'.$log->status.'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Logged by : </td>
										<td>'.Format::makeUpperCase($create_user_details->first_name . " " . substr($create_user_details->middle_name,0,1) . ". " . $create_user_details->last_name).'</td>
									</tr>
									<tr>
										<td style="width:80px;font-weight:bold;">Remarks : </td>
										<td>'.$log->remarks.'</td>
									</tr>
									'.$list_of_passenger.'
								</table>

							</td>

						</tr>

						
				   </table><br/>';
				   	$time_logs_data .= '</td></tr>';
 }

$time_logs_data .= '</table>';

$html = "";

$html .= $trip_ticket_data_header;
$html .= $trip_ticket_data;
$html .= $vehicle_header;
$html .= $vehicle_data;
$html .= $approval_header;
$html .= $approval_data;
$html .= $time_logs_header;
$html .= $time_logs_data;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('Trip Report-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

