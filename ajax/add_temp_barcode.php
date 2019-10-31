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

$post = (object)$_POST;
$barcode = new Barcode();
$barcode->emptyTable();

$barcode_list_file = "";
if ($_FILES['file_barcode_list']['size'] != 0 && $_FILES['file_barcode_list']['error'] == 0) { 
    $file_name = $_FILES['file_barcode_list']['name'];
    $file_size =$_FILES['file_barcode_list']['size'];
    $file_tmp =$_FILES['file_barcode_list']['tmp_name'];
    $file_type=$_FILES['file_barcode_list']['type'];
    $file_ext=explode('.',$_FILES['file_barcode_list']['name']);
	$file_ext=end($file_ext);     
	$barcode_list_file = "barcode-list.".$file_ext;
	move_uploaded_file($file_tmp,"../reports/barcode_list/".$barcode_list_file);
}

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify("../reports/barcode_list/" .$barcode_list_file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load("../reports/barcode_list/" . $barcode_list_file);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($barcode_list_file,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
for ($row = 2; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
    $data = $rowData[0];
    $barcode->addBarcode($data[0],$data[1]);
    //  Insert row data array into your database of choice here
}

