<?php


require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");
$post = (object)$_POST;
$start_date = "";
$end_date = "";
$start_date_display = "";
$end_date_display = "";
$driver = new Driver();
$fleet_card = new FleetCard();
$session = (object)$_SESSION['user_data'];
$emp_details = $driver->getEmployeeDetailsById($session->employee_id);
$table_body = "";
if(isset($post->txt_report_date)){
	//$report_date =  explode('-',trim($post->txt_report_date));
	$start_date = $post->start_date;
	$end_date = $post->end_date;
	$start_date_display = Format::format_readable_date_only($start_date);
	$end_date_display = Format::format_readable_date_only($end_date);
	$fleet_card_monitoring_report = $fleet_card->get_fleet_card_report($start_date,$end_date);
	foreach($fleet_card_monitoring_report as $report) {
		$report = (object)$report;
		$table_body .= '<tr>
							<td>'.$report->fleet_card_no.'</td>
							<td>'.$report->plate_no.'</td>
							<td>'.$report->cs_no.'</td>
							<td>'.$report->driver_name.'</td>
							<td>'.$report->vehicle_class.'</td>
							<td>'.$report->km_reading.'</td>
							<td>'.$report->fuel_status.'</td>
							<td>'.$report->log_date.'</td>
							<td>'.$report->event_type.'</td>
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
		$html = "<span style='font-weight:normal;'>IPC Centralized Web Portal</span>";
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
$pdf->SetTitle('Fleet Card Monitoring');
$pdf->SetSubject('Fleet Card Cards Monitoring');
$pdf->SetKeywords('fc , isuzu');

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
$page_header = '<h1 style="text-align:center;">Fleet Card Monitoring</h1>';
$page_sub_header = '<p style="font-size:11px;font-style:italic;">Fleet Card Usage as of <strong>'.$start_date_display.'</strong> to <strong>'.$end_date_display.'</strong></p>';

$table_data = '<table border="1" style="font-size:10px;" cellpadding="5">
				 	<thead >
				 		<tr>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Fleet Card No.</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Plate No.</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">CS No.</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Driver</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Vehicle Class</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">KM Reading</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Fuel Status</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Date & Time</th>
				 			<th  align="center" style="background-color:#DADFE1;color:#000;">Event Type</th>
				 		</tr>
				 		
				 	</thead>
				 		
				 	<tbody>'.$table_body.'</tbody>
				</table>';

$html = '';
$html .= $page_header;
$html .= $page_sub_header;
$html .= $table_data;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('Fleet-Card-Monitoring-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

