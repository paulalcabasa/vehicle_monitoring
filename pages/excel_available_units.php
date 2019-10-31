<?php 
require_once('../../../libs/phpexcel/Classes/PHPExcel.php');
require_once("../initialize.php");
    ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300000);

$params_id = !empty($_GET['id'])? $_GET['id'] : null;
$vehicle = new Vehicle();
$values = "";
$vehicle_list = $vehicle->getAllAvailableUnits($params_id);
$index = 1;

class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
}


//$overview_details = $this->report_model->cbua_debit_report($date);
        //$this->load->library('excel');

        $styleArray = array(
              'borders' => array(
                  'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                  )
              ),

              'font'  => array(
                    'bold'  => false,
                    'size'  => 8,
                    'name'  => 'Calibri'
                ),

              'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )

          );

        $row = count($vehicle_list)+1;
        $objPHPExcel = PHPExcel_IOFactory::load("../reports/template/available_units.xlsx");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->fromArray($vehicle_list,null, 'A2');
        $objPHPExcel->getActiveSheet()->getStyle(
            'A2:' . 
            'F' . 
            $row
        )->applyFromArray($styleArray);

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('../reports/template/tempfile.xls');

        $filename='available_units.xls'; //save our workbook as this file name
 
        header('Content-Type: application/vnd.ms-excel'); //mime type
 
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
 
        header('Cache-Control: max-age=0'); //no cache
 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 
        $objWriter->save('php://output');

?>