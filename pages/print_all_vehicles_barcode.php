<?php


require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");


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
    'bgcolor' => false, //array(255,255,255), falsearray(0,0,0)
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

/*
$style4 = array('L' => 0,
                'T' => array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '20,10', 'phase' => 10, 'color' => array(100, 100, 255)),
                'R' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(50, 50, 127)),
                'B' => array('width' => 0.75, 'cap' => 'square', 'join' => 'miter', 'dash' => '30,10,5,10'));// Rect*/

$style4 = array('L' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
                'T' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
                'R' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
                'B' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))
                );// Rect

//$pdf->Text(100, 4, 'Rectangle examples');
$vehicle = new Vehicle();

//$vehicle_list = $vehicle->getAllVehicles();
$vehicle_list = $_SESSION['vehicle_list'];


$index = 1;

$barcode_x = 25;
$barcode_y = 15;
$barcodes_per_page = 14;
$ctr = 0;
$pdf->AddPage();
$vehicle_count = count($vehicle_list);
$page = 1;

$style6 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'color' => array(0, 0, 0));

foreach($vehicle_list as $unit){
	$vehicle_id = $unit[0];
	$cs_no = $unit[1];
	$plate_no = $unit[2];
	$assignee_name = $unit[3];
	$classification = $unit[4];
	$model = $unit[5];

	$plate_no = ($plate_no != "" ? $plate_no . " - " : "");

	$label = $plate_no . $cs_no;
	$left_additional = 0;
	if(empty($plate_no)){
		$left_additional = 25;
	}
	else {
		$left_additional = 15;
	}
	$assignee = "";
	if($classification == "Carplan"){
		$assignee = Format::makeUpperCase($assignee_name);
		$assignee = str_replace('Iv','IV',$assignee);
	}
	else {
		$assignee = $classification;
	}

	$assignee_length = strlen($assignee);
	$add_space = 0;
	if($assignee_length > 10){
		$add_space = $assignee_length/4;
	}
	$space_left = (30 - $assignee_length + $add_space);

	$x = $pdf->GetX();
    $y = $pdf->GetY();

	if($index <= 2){
		//$pdf->writeHTMLCell(0, 0, $barcode_x + $space_left, $barcode_y -5, '<span>' . $assignee  . '</span>', 0, 0, false, true, 'B',false);
		//$pdf->writeHTMLCell(0, 0, $barcode_x, $barcode_y -5, '<span>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</span>', 0, 0, false, true, 'B',false);

	//	$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
	//	$pdf->RoundedRect(50, 255, 40, 30, 6.50, '1000');
	//	$pdf->RoundedRect(95, 255, 40, 30, 10.0, '1111', null, $style6);
	//	$pdf->RoundedRect(140, 255, 40, 30, 8.0, '0101', 'DF', $style6, array(200, 200, 200));
		$pdf->RoundedRect($x + 9.5, $y - 13, 75, 26, 3.50, '1111', 'DF',$style6,array(255,255,255),array(255,255,255));
		$pdf->writeHTMLCell(0, 0, $barcode_x + $left_additional, $barcode_y + 18, '<span>' . $label . '</span>', 0, 0, false, true, 'B',false);

		$pdf->write1DBarcode($vehicle_id, 'C39', $barcode_x, $barcode_y, '', 18, 0.3, $style, 'M');
	 	$pdf->SetXY($x,$y);
	 	//$pdf->setCellPaddings(0,10,50,0);
	 	$pdf->Cell(94.5, 35, '', 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
	 	
	 	$barcode_x+= 95;	
	}
	else {
		$barcode_x = 25;
		$barcode_y += 35;
		//$pdf->writeHTMLCell(0, 0, $barcode_x + $space_left, $barcode_y - 5, '<span>' . $assignee . '</span>', 0, 0, false, true, 'B',false);
		$pdf->RoundedRect($barcode_x - 6, $barcode_y - 3, 75, 26, 3.50, '1111', 'DF',$style6,array(255,255,255),array(255,255,255));
		$pdf->writeHTMLCell(100, 50, $barcode_x + $left_additional, $barcode_y + 18, '<span>' . $label . '</span>', 0, 0, false, true, '',true);
		$pdf->write1DBarcode($vehicle_id, 'C39', $barcode_x, ($barcode_y), '', 18, 0.3, $style, 'M');	 	
		$pdf->Ln();
		$pdf->SetY($barcode_y+10);
		//$pdf->setCellPaddings(0,10,50,0);
		$pdf->Cell(94.5, 35, '', 1, 0, 'C', FALSE, '', 0, FALSE, 'C', 'B');
	
		$barcode_x += 95;
		//$barcode_y += 2.5;
		$index = 1;
	}

	$ctr++;
	$index++;
	if($ctr == $barcodes_per_page){
		$barcode_x = 25;
		$barcode_y = 15;
		if($page < ($vehicle_count/$barcodes_per_page) ){
			$pdf->AddPage();
		}
		$ctr = 0;
		$index = 1;
		$page++;
	}
	

}






// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('Vehicle_Barcode-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

