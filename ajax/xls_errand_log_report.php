<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Asia/Manila');

/** PHPExcel_IOFactory */
require_once('../../../libs/phpexcel/Classes/PHPExcel/IOFactory.php');
require_once('../initialize.php');
$driver = new Driver();
$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$emp_details = $driver->getEmployeeDetailsById($user_data->employee_id);

$file = "../reports/template/errand_log_template.xlsx";
$inputFileType = PHPExcel_IOFactory::identify($file);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($file);

$report_border = array(
    'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
    ),
     'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
     )
);

$vehicle_id = null;
if(isset($post->sel_vehicle)){
	$vehicle_id = $post->sel_vehicle;
}

$baseRow = 11;
$rowIndex = $baseRow;
$trip_ticket = new TripTicket();

$trip_ticket_report = $trip_ticket->get_errand_logs(
	$post->start_date,
	$post->end_date,
	$vehicle_id
);




//SET REPORT DATE
$objPHPExcel->getActiveSheet()->setCellValue('B6', "Trip Report from " . Format::format_readable_date_only($post->start_date) . " to " . Format::format_readable_date_only($post->end_date) );

foreach($trip_ticket_report as $row) {
	$row = (object)$row;
/* 	$etr = ($row->expected_time_of_return != "" ? Format::format_date_slash($row->expected_time_of_return) : 'N/A');
	//$time_in = ($row->time_in != "" ? Format::format_date_slash($row->time_in) : 'N/A');
//	$time_out = ($row->time_out != "" ? Format::format_date_slash($row->time_out) : 'N/A');
	$date_requested = ($row->date_requested != "" ? Format::format_date_slash($row->date_requested) : 'N/A');
 */	
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $row->vehicle_id)
	                              ->setCellValue('C'.$rowIndex, $row->cs_no)
	                              ->setCellValue('D'.$rowIndex, $row->plate_no)
	                              ->setCellValue('E'.$rowIndex, $row->remarks)
	                              ->setCellValue('F'.$rowIndex, $row->fuel_status)
	                              ->setCellValue('G'.$rowIndex, $row->driver_name)
	                              ->setCellValue('H'.$rowIndex, $row->km_reading)
	                              ->setCellValue('I'.$rowIndex, $row->passengers)
	                              ->setCellValue('J'.$rowIndex, $row->log_time)
	                              ->setCellValue('K'.$rowIndex, $row->log_type);
	                             
    $rowIndex++;
}

// set report properties
$objPHPExcel->getActiveSheet()->setCellValue('B' .($rowIndex + 2 ), "Prepared by: " . Format::makeUpperCase($emp_details->first_name . " " . substr($emp_details->middle_name,0,1) . ". " . $emp_details->last_name) );
$objPHPExcel->getActiveSheet()->setCellValue('B'.($rowIndex + 3), "Date Generated: " . Format::format_date(date('Y-m-d H:i:s')));


$objPHPExcel->getActiveSheet()->getStyle('B'.$baseRow.":".'K'. ($rowIndex-1))->applyFromArray($report_border);
$objPHPExcel->getActiveSheet()->getStyle('B'.$baseRow.":".'K'. ($rowIndex-1))->getAlignment()->setWrapText(true); 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $inputFileType);

//$file_path = "../reports/excel_exports/";
//$filename = "Trip-Ticket-Report-" . date('YmdHis') . '.xlsx';
/*
$objWriter->save(str_replace(__FILE__, $file_path . $filename, __FILE__));
echo $filename;
*/

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('TripReport');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="trip_report.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');