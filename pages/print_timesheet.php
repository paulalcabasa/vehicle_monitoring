<?php
require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");
$start_date = "";
$end_date = "";
$employee_id = "";
$employee_no = "";
$department = "";
$name = "";
$section = "";
$timesheet_summary = "";
$vehicle = new Vehicle();
$driver = new Driver();
if(isset($_POST['start_date']) && isset($_POST['end_date'])){
	$post = (object)$_POST;	
	$start_date = Format::format_readable_date_only($post->start_date);
	$end_date = Format::format_readable_date_only($post->end_date);
	$employee_id = $post->sel_employee_id;
	$employee_details = $driver->getEmployeeDetailsById($employee_id); 
	$employee_no = $employee_details->employee_no;
	$name = Format::makeUpperCase($employee_details->first_name . " " . substr($employee_details->middle_name,0,1) . ". " . $employee_details->last_name);
	$department = $employee_details->department;
	$section = $employee_details->section;

	$managers_attendance = $vehicle->getManagersAttendanceByDate($post->start_date,$post->end_date,$post->sel_employee_id);
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

		$timesheet_summary .= "<tr>";
			$timesheet_summary .= "<td>" . $ctr . "</td>";
			$timesheet_summary .= "<td>" . Format::format_date_with_day($attendance->log_date) . $remarks  . "</td>";
			$timesheet_summary .= "<td>" . $time_in . "</td>";
			$timesheet_summary .= "<td>" . $time_out . "</td>";
		$timesheet_summary .= "</tr>";
		$ctr++;
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
$pdf->SetTitle('Managers Attendance');
$pdf->SetSubject('Managers Attendance');
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

$title = '<div style="width:100%;background-color:#dfe2ea;color:#000;text-align:center;font-size:13px;font-weight:bold;">
		  	<span style="padding:1.5em;">
		  		DTR for '.$start_date.' to '.$end_date.'
		  	</span>
		  </div>';
$employee_details = '
					<table style="font-size:10px;width:50%;">
		 				<tr>
		 					<td width="100"><strong>Employee No. :</strong></td>
		 					<td>'.$employee_no.'</td>
		 				</tr>
		 				<tr>
		 					<td><strong>Name :</strong></td>
		 					<td>'.$name.'</td>
		 				</tr>
		 				<tr>
		 					<td><strong>Department :</strong></td>
		 					<td>'.$department.'</td>
		 				</tr>
		 				<tr>
		 					<td><strong>Section :</strong></td>
		 					<td>'.$section.'</td>
		 				</tr>
					 </table>
					 ';
$summary_title = '<div style="width:100%;background-color:#dfe2ea;color:#000;text-align:center;font-size:11px;font-family:Arial, Sans, Tahoma;">
				  	<span style="padding:1.5em;">
				  		 Summary
				  	</span>
				  </div>';

$summary_content = '<table style="font-size:9px;" cellpadding="4" border="1">

						<tr style="background-color:#dfe2ea;">	
							<th style="font-weight:bold;width:10%;">No.</th>
							<th style="font-weight:bold;width:35%;">Date</th>
							<th style="font-weight:bold;width:27.5%;">In</th>
							<th style="font-weight:bold;width:27.5%;">Out</th>
						</tr>

						'.$timesheet_summary.'
	
					</table>';

$summary_wrapper = '<br/><table>
<tr>
<td style="width:1%"></td>
<td style="width:98%">'.$summary_content.'</td>
<td style="width:1%"></td>
</tr>
</table>';


$html = "";
$html .= $title;
$html .= $employee_details;
$html .= $summary_title;
$html .= $summary_wrapper;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('DTR-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

