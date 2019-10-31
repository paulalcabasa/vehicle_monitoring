<?php

require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");

$post = (object)$_POST;

$trip_ticket = new TripTicket();
$driver = new Driver();
$session = (object)$_SESSION['user_data'];
$emp_details = $driver->getEmployeeDetailsById($session->employee_id);
$start_date = "";
$end_date = "";
$table_body = "";
$report_date_header = "";
$vehicle_id = null;
if(isset($post->sel_vehicle)){
	$vehicle_id = $post->sel_vehicle;
}
if(isset($post->txt_report_date)){ 
	$report_date =  explode('-',trim($post->txt_report_date));
	$start_date = $report_date[0];
	$end_date = $report_date[1];
	$report_date_header = '<p style="font-size:11px;font-style:italic;">Trip Report from <strong>' . Format::format_readable_date_only($start_date) . "</strong> to <strong>" . Format::format_readable_date_only($end_date) . "</strong></p>";
	$trip_ticket_report = $trip_ticket->getTripTicketReport(
		Format::format_date_only($start_date) . " 00:00:00",
		Format::format_date_only($end_date). " 23:59:59",
		$post->sel_report_type,
		$vehicle_id
	);
	foreach($trip_ticket_report as $row) {
		$row = (object)$row;
		$etr = ($row->expected_time_of_return != "" ? Format::format_date_slash($row->expected_time_of_return) : 'N/A');
		$time_in = ($row->time_in != "" ? Format::format_date_slash($row->time_in) : 'N/A');
		$time_out = ($row->time_out != "" ? Format::format_date_slash($row->time_out) : 'N/A');
		$date_requested = ($row->date_requested != "" ? Format::format_date_slash($row->date_requested) : 'N/A');
		$table_body .= '<tr nobr="true">
							<td>' .  $row->trip_ticket_no . '</td>
							<td>' .  $row->jo_no . '</td>
							<td>' .  $row->plate_no. '</td>
							<td>' .  $row->purpose. '</td>
							<td>' .  $row->destination. '</td>
							<td>' .  $row->trip_type. '</td>
							<td>' .  Format::makeUppercase($row->driver_name). '</td>
							<td>' .  $date_requested. '</td>
							<td>' .  $etr. '</td>
							<td>' .  $row->trip_status. '</td>
							<td>' .  $time_out. '</td>
							<td>' .  $time_in. '</td>
						</tr>';		                             
	}
}
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

  //Page header
    public function Header() {
        // Logo
		$image_file = K_PATH_IMAGES.'/ss.jpg';
		$this->Image($image_file, 10, 10, 0, '', 'JPG', '', 'T', false, 0, 'R', false, false, 0, false, false, false);
		$style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, 22, 287, 22, $style);
		$html = "<span style='font-weight:normal;'>Vehicle Monitoring System</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>IPC Centralized Database Portal</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 13, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>Your Responsible Partner</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 250.5, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);

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
		$this->Line(10, $this->GetPageHeight()-12, 287, $this->getPageHeight()-12, $style);
		$this->Cell(0, 10, "        ".$date_today, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
    
}


// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Paul');
$pdf->SetTitle('Trip Ticket Report');
$pdf->SetSubject('Trip Ticket Report');
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


$html = '';

$page_header = '<h1 style="font-family:Arial, Sans, Tahoma, Verdana;font-size:13pt;text-align:center;">Trip Ticket Report</h1> <br/>';

$table_data = '<table border="1" style="font-size:10px;" cellpadding="5">
				 	<thead >
				 		<tr>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Trip Ticket No.</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">JO No.</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Plate No</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Purpose</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Destination</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Nature of Trip</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Driver Name</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Date Requested</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Expected Time of Return</th>
				 			<th rowspan="2" align="center" style="background-color:#DADFE1;color:#000;">Status</th>
				 			<th colspan="2" align="center" style="background-color:#DADFE1;color:#000;">Time</th>
				 		</tr>
				 		<tr>
				 			<th align="center" style="background-color:#DADFE1;color:#000;">Out</th>
				 			<th align="center" style="background-color:#DADFE1;color:#000;">In</th>
				 		</tr>
				 	</thead>
				 	<tbody>'.$table_body.'</tbody>
				 </table>';

$html .= $page_header;
$html .= $report_date_header;
$html .= $table_data;
//$html .= '<br/><br/><span style="font-size:10px;">Prepared by: ' . Format::makeUpperCase($emp_details->first_name . ' ' . substr($emp_details->middle_name,0,1) . '. ' . $emp_details->last_name);
//$html .= '<br/><span style="font-size:10px;">Date Generated : '.Format::format_date(date('Y-m-d H:i:s')).'</span>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('Trip-Ticket-Report-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

