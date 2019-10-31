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
$fleet_card = new FleetCard();

$fleet_card_list_file = "";
if ($_FILES['file_fleet_card']['size'] != 0 && $_FILES['file_fleet_card']['error'] == 0) { 
    $file_name = $_FILES['file_fleet_card']['name'];
    $file_size =$_FILES['file_fleet_card']['size'];
    $file_tmp =$_FILES['file_fleet_card']['tmp_name'];
    $file_type=$_FILES['file_fleet_card']['type'];
    $file_ext=explode('.',$_FILES['file_fleet_card']['name']);
	$file_ext=end($file_ext);     
	$fleet_card_list_file = "fleet_card-list.".$file_ext;
	move_uploaded_file($file_tmp,"../reports/fleet_card_list/".$fleet_card_list_file);
}

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify("../reports/fleet_card_list/" .$fleet_card_list_file);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load("../reports/fleet_card_list/" . $fleet_card_list_file);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($barcode_list_file,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
$ctr = 1;
echo "<table border='1'>";
    echo "<thead>
                <tr>
                    <th>No.</th>
                    <th>CS No</th>
                    <th>Plate No</th>
                    <th>Fleet Card No</th>
                    <th>Remarks</th>
                </tr>
           </thead>
           <tbody>";
//  Loop through each row of the worksheet in turn
for ($row = 2; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
  
    $cs_no = $rowData[0][0];
    $plate_no = $rowData[0][1];
    $fleet_card_no = $rowData[0][2];
    $fleet_card->update_vehicle_fleet_card($cs_no,$fleet_card_no);
    $remarks = "Success";
    echo "<tr>
                <td>".$ctr."</td>
                <td>".$cs_no."</td>
                <td>".$plate_no."</td>
                <td>".$fleet_card_no."</td>
                <td>".$remarks."</td>

           </tr>";
    $ctr++;
}


echo "</tbody></table>";