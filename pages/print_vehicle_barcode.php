<?php
require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");
/*if($vehicle_details->classification == "Carplan"){
	$emp_details = $driver->getEmployeeDetailsById($vehicle_details->employee_id);
	$assignee = Format::makeUpperCase($emp_details->emp_name);
	$assignee = str_replace('Iv','IV',$assignee);
}
else {
	$assignee = $vehicle_details->assignee;
}*/
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
		/*$image_file = K_PATH_IMAGES.'/ss.jpg';
		$this->Image($image_file, 10, 13, 33.3, 5, 'JPG', '', 'T', false, 0, 'R', false, false, 0, false, false, false);
		$style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, 22, 200, 22, $style);
		$html = "<span style='font-weight:normal;'>Vehicle Monitoring System</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>IPC Centralized Database Portal</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 13, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = '<span style="font-weight:normal;font-size:10px;">Your Responsible Partner</span>';
		$this->writeHTMLCell($w = 0, $h = 0, $x = 167, $y = 18, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);*/

    }

    // Page footer
    public function Footer() {
		//  // Position at 15 mm from bottom
	/*	$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', '', 6);
		// Page number
		$date_today = date('Y-m-d H:i:s');
		$date_create = date_create($date_today);
		$date_today = date_format($date_create,"l, F j, Y g:i A");
		$style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, $this->GetPageHeight()-12, 200, $this->getPageHeight()-12, $style);
		$this->Cell(0, 10, "        ".$date_today, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');*/
    }
    
}


// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Paul');
$pdf->SetTitle('Vehicles Barcode');
$pdf->SetSubject('Vehicles Barcode');
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
$pdf->SetFont('dejavusans', '', 9, '', true);

// Add a page
// tdis metdod has several options, check tde source code documentation for more information.


// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'deptd_w'=>0.2, 'deptd_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


// define barcode style
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

$barcode_x = 25;
$barcode_y = 15;
$x = $pdf->GetX();
$y = $pdf->GetY();	
$pdf->AddPage();

$vehicle = new Vehicle();
$driver = new Driver();
$encryption = new Encryption();
$get = (object)$_GET;

$vehicle_id = $encryption->decrypt($get->d);
$vehicle_details = $vehicle->getVehicleDetails($vehicle_id);

$plate_no = ($vehicle_details->plate_no != "" ? $vehicle_details->plate_no . " - " : "");
$cs_no = $vehicle_details->cs_no;
$label = $plate_no . $cs_no;
$left_additional = 0;
if(empty($plate_no)){
	$left_additional = 25;
}
else {
	$left_additional = 15;
}
$assignee = "";

if($vehicle_details->classification == "Carplan"){
	$assignee = Format::makeUpperCase($vehicle_details->assignee_name);
	$assignee = str_replace('Iv','IV',$assignee);
}
else {
	$assignee = $vehicle_details->classification;
}

$assignee_length = strlen($assignee);
$add_space = 0;
if($assignee_length > 10){
	$add_space = 5;
}
$space_left = (30 - $assignee_length + $add_space);

/*	
$assignee_length = strlen($assignee);
$add_space = 0;
if($assignee_length > 10){
	$add_space = 5;
}
$space_left = (30 - $assignee_length + $add_space);
$pdf->writeHTMLCell(0, 0, $barcode_x + $space_left, $barcode_y - 5, '<span>' . $assignee . '</span>', 0, 0, false, true, 'B',false);
$pdf->writeHTMLCell(100, 50, $barcode_x + $left_additional, $barcode_y + 20, '<span>' . $label . '</span>', 0, 0, false, true, '',true);
$pdf->write1DBarcode(Format::formatVehicleId($unit->id), 'C39', $barcode_x, ($barcode_y), '', 18, 0.3, $style, 'M');*/

$x = $pdf->GetX();
$y = $pdf->GetY();
//$pdf->writeHTMLCell(0, 0, $barcode_x + $space_left, $barcode_y -5, '<span>' . $assignee  . '</span>', 0, 0, false, true, 'B',false);
$pdf->writeHTMLCell(100, 50, $barcode_x + $left_additional, $barcode_y + 18, '<span>' . $label . '</span>', 0, 0, false, true, '',true);	
$pdf->write1DBarcode(Format::formatVehicleId($vehicle_id), 'C39', $barcode_x, $barcode_y, '', 18, 0.3, $style, 'M');
$pdf->SetXY($x,$y);
$pdf->Cell(94.5, 35, '', 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');

	





// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('Vehicle_Barcode-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

