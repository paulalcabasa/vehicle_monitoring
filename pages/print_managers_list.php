<?php
require_once('../../../libs/tcpdf/tcpdf_include.php');
require_once("../initialize.php");
$manager = new Manager();
// getManagersList

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
	/*	$image_file = K_PATH_IMAGES.'/ss.jpg';
		$this->Image($image_file, 10, 10, 0, '', 'JPG', '', 'T', false, 0, 'R', false, false, 0, false, false, false);
		$style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, 22, 200, 22, $style);
		$html = "<span style='font-weight:normal;'>Vehicle Monitoring System</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>IPC Centralized Web Portal</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 13, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		$html = "<span style='font-weight:normal;'>Your Responsible Partner</span>";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 163.5, $y = 17, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);*/

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
		$date_today = date_format($date_create,"l, F j, Y g:i:s A");
		$style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(10, $this->GetPageHeight()-12, 200, $this->getPageHeight()-12, $style);
		$this->Cell(0, 10, "        ".$date_today, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');*/
    }
    
}


// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 15, PDF_MARGIN_RIGHT);
$pdf->SetheaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 15);

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

$style4 = array('L' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
                'T' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
                'R' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
                'B' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0))
                );// Rect


/*$index = 1;

$barcode_x = 25;
$barcode_y = 15;
$barcodes_per_page = 14;
$ctr = 0;
$pdf->AddPage();
$barcode_count = count($barcode_list);
$page = 1;*/
$managers_list = $manager->getManagersList();
$html = '<table cellspacing="0"  border="1">';
$tr_ctr = 1;

$row_ctr = 1;

$total_rows = count($managers_list);

foreach($managers_list as $manager_data){
	$manager_data = (object)$manager_data;
    $employee_name = $manager_data->employee_name;
    $employee_no = $manager_data->employee_no;
  //  var_dump($manager_data);
    $params = $pdf->serializeTCPDFtagParameters(array(
											  $employee_no, 
											  'C39',
											  '',
											  '',
											  0,
											  0,
											  0.2,
											  array(
											  	'position'=>'S',
											  	'border'=>false,
											  	'padding'=>4,
											  	'fgcolor'=>array(0,0,0),
											  	'bgcolor'=>array(255,255,255),
											  	'text'=>false,
											  	'font'=>'helvetica',
											  	'fontsize'=>8,
											  	'stretchtext'=>2
											  ),
											  'N'
											)
										);    
           
 
	if($row_ctr < 45){
		if($tr_ctr == 1){
			$html .= '<tr nobr="true">'; 

	    	//echo $total_rows . " - " . $row_ctr . "<br/>";
			/*if($total_rows == $row_ctr){ // if last row
				//echo "last row" . $row_ctr;
				if($total_rows % 2 == 1){
				//echo $row_ctr  . "test";
						$html .= '<td width="200px"  align="center"></td>
			      				  <td align="center" width="290px"></td>';
				}
			}*/
		}
		
		$html .= '<td width="200px"  align="center"><br/><br/><br/>'.$employee_name.'</td>
			      <td align="center" width="290px">
			    	<tcpdf method="write1DBarcode" params="'.$params.'" />
			      </td>';
	    
	    
	    $row_ctr++;



		if($tr_ctr == 2){
			
			$html .= '</tr>';
			$tr_ctr = 0;
		}


	
	
		$tr_ctr++;
	}
	
}


$html .= '</table>';


//echo $html;
$pdf->writeHTML($html,true, false,false,false,'left');


// ---------------------------------------------------------
// Close and output PDF document
// tdis metdod has several options, check tde source code documentation for more information.
$pdf->Output('Managers-List-' . time() .'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

