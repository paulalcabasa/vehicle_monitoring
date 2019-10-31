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

$file = "../reports/template/trip_ticket_report_template.xlsx";
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

$trip_ticket_report = $trip_ticket->getTripTicketReport(
	$post->start_date,
	$post->end_date,
	$post->search_by,
	$vehicle_id
);

//SET REPORT DATE
$objPHPExcel->getActiveSheet()->setCellValue('B6', "Trip Report from " . Format::format_readable_date_only($post->start_date) . " to " . Format::format_readable_date_only($post->end_date) );

foreach($trip_ticket_report as $row) {
	$row = (object)$row;
	$etr = ($row->expected_time_of_return != "" ? Format::format_date_slash($row->expected_time_of_return) : 'N/A');
	$time_in = ($row->time_in != "" ? Format::format_date_slash($row->time_in) : 'N/A');
	$time_out = ($row->time_out != "" ? Format::format_date_slash($row->time_out) : 'N/A');
	$date_requested = ($row->date_requested != "" ? Format::format_date_slash($row->date_requested) : 'N/A');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $row->trip_ticket_no)
	                              ->setCellValue('C'.$rowIndex, $row->jo_no)
	                              ->setCellValue('D'.$rowIndex, $row->plate_no)
	                              ->setCellValue('E'.$rowIndex, $row->purpose)
	                              ->setCellValue('F'.$rowIndex, $row->destination)
	                              ->setCellValue('G'.$rowIndex, $row->trip_type)
	                              ->setCellValue('H'.$rowIndex, $date_requested)
	                              ->setCellValue('I'.$rowIndex, $row->driver_name)
	                              ->setCellValue('J'.$rowIndex, $etr)
	                              ->setCellValue('K'.$rowIndex, $row->trip_status)
	                              ->setCellValue('L'.$rowIndex, $time_out)
	                              ->setCellValue('M'.$rowIndex, $time_in);
	                             
    $rowIndex++;
}

// set report properties
$objPHPExcel->getActiveSheet()->setCellValue('B' .($rowIndex + 2 ), "Prepared by: " . Format::makeUpperCase($emp_details->first_name . " " . substr($emp_details->middle_name,0,1) . ". " . $emp_details->last_name) );
$objPHPExcel->getActiveSheet()->setCellValue('B'.($rowIndex + 3), "Date Generated: " . Format::format_date(date('Y-m-d H:i:s')));


$objPHPExcel->getActiveSheet()->getStyle('B'.$baseRow.":".'M'. ($rowIndex-1))->applyFromArray($report_border);
$objPHPExcel->getActiveSheet()->getStyle('B'.$baseRow.":".'M'. ($rowIndex-1))->getAlignment()->setWrapText(true); 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $inputFileType);

$file_path = "../reports/excel_exports/";
$filename = "Trip-Ticket-Report-" . date('YmdHis') . '.xlsx';
$objWriter->save(str_replace(__FILE__, $file_path . $filename, __FILE__));
echo $filename;
