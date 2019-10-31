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

$file = "../reports/template/cpvu_template.xlsx";
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

$baseRow = 11;
$rowIndex = $baseRow;
$vehicle = new Vehicle();

$vehicle_usage_report = $vehicle->getVehicleUsageReportCarplan($post->start_date,$post->end_date);
$objPHPExcel->getActiveSheet()->setCellValue('B6', "Vehicle Usage as of " . Format::format_readable_date_only($post->start_date) . " to " . Format::format_readable_date_only($post->end_date) );
foreach($vehicle_usage_report as $usage) {
	$usage = (object)$usage;
	
	$time_in = ($usage->time_in != "" ? Format::format_date_slash($usage->time_in) : 'N/A');
	$time_out = ($usage->time_out != "" ? Format::format_date_slash($usage->time_out) : 'N/A');
	
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $usage->cs_no)
	                              ->setCellValue('C'.$rowIndex, $usage->plate_no)
	                              ->setCellValue('D'.$rowIndex, $usage->driver)
	                              ->setCellValue('E'.$rowIndex, Format::formatBlank($usage->passengers_in))
	                              ->setCellValue('F'.$rowIndex, Format::formatBlank($usage->passengers_out))
	                              ->setCellValue('G'.$rowIndex, $time_in)
	                              ->setCellValue('H'.$rowIndex, $time_out)
	                              ->setCellValue('I'.$rowIndex, Format::formatBlank($usage->km_in))
	                              ->setCellValue('J'.$rowIndex, Format::formatBlank($usage->km_out))
	                              ->setCellValue('K'.$rowIndex, Format::formatBlank($usage->fuel_in))
	                              ->setCellValue('L'.$rowIndex, Format::formatBlank($usage->fuel_out))
	                              ->setCellValue('M'.$rowIndex, Format::formatBlank($usage->remarks_in))
	                              ->setCellValue('N'.$rowIndex, Format::formatBlank($usage->remarks_out));
	                             
    $rowIndex++;
}

// set report properties
$objPHPExcel->getActiveSheet()->setCellValue('B' .($rowIndex + 2 ), "Prepared by: " . Format::makeUpperCase($emp_details->first_name . " " . substr($emp_details->middle_name,0,1) . ". " . $emp_details->last_name) );
$objPHPExcel->getActiveSheet()->setCellValue('B'.($rowIndex + 3), "Date Generated: " . Format::format_date(date('Y-m-d H:i:s')));


$objPHPExcel->getActiveSheet()->getStyle('B'.$baseRow.":".'N'. ($rowIndex-1))->applyFromArray($report_border);
$objPHPExcel->getActiveSheet()->getStyle('B'.$baseRow.":".'N'. ($rowIndex-1))->getAlignment()->setWrapText(true); 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $inputFileType);

$file_path = "../reports/excel_exports/";
$filename = "Vehicle-Usage-" . date('YmdHis') . '.xlsx';
$objWriter->save(str_replace(__FILE__, $file_path . $filename, __FILE__));
echo $filename;
