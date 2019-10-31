<?php
include("../classes/class.driver.inc");
include("../classes/class.connection.inc");
$driver = new Driver();
$conn = Connection::getInstance();
//  Include PHPExcel_IOFactory
include '../../../libs/phpexcel/classes/PHPExcel/IOFactory.php';

$inputFileName = 'vehicle_master.xlsx';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
echo "<table border='1'>";
//  Loop through each row of the worksheet in turn
echo "<thead>";
echo "<th>Plate No</th>";
echo "<th>CS NO</th>";
echo "<th>Classification</th>";
echo "<th>Status</th>";
echo "<th>Assignee</th>";
echo "<th>Employee ID</th>";
echo "<th>Employee No</th>";
echo "<th>Department</th>";
echo "<th>Section</th>";
echo "<th>Is Available</th>";
echo "</thead>";
for ($row = 2; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
  	$rowData = $rowData[0];
  	$plate_no = $rowData[1];
  	$cs_no = $rowData[2];
  	$class = trim($rowData[5]);
  	$assignee = $rowData[6];
  	$employee_id = $rowData[7];
  	$status = 1;
  	$is_available = 1;
  	$employee_no = NULL;
  	$department = NULL;
  	$section = NULL;
  	$vehicle_class = 0;

  	switch($class){
  		case "CARPLAN":
  			$vehicle_class = 2;
  		break;

  		case 'CRT':
  			$vehicle_class = 6;
  		break;

  		case 'CSS':
  			$vehicle_class = 1;
  		break;

  		case "DEV'T":
  			$vehicle_class = 7;
  		break;

  		case "DEV'T":
  			$vehicle_class = 7;
  		break;

  		case "EXEC":
  			$vehicle_class = 4;
  		break;

  		case "MEDIA":
  			$vehicle_class = 3;
  		break;

  		case "ORIX":
  			$vehicle_class = 12;
  		break;

  		case "PARTS":
  			$vehicle_class = 13;
  		break;

  		case "PRODUCT":
  			$vehicle_class = 14;
  		break;
  		
  		case "SALES":
  			$vehicle_class = 15;
  		break;

  		case "SERVICE":
  			$vehicle_class = 16;
  		break;

  		case "SPECIAL":
  			$vehicle_class = 17;
  		break;

  		default:
  			$vehicle_class = 0;
  		break;
  	}
 
  	if($vehicle_class == 2){
  		$emp_details = $driver->getEmployeeDetailsById($employee_id);
  		$department = $emp_details->department;
  		$section = $emp_details->section;
  		$employee_no = $emp_details->employee_no;
  	}

  	echo "<tr>";
  		echo "<td>" . $plate_no . "</td>";
  		echo "<td>" . $cs_no . "</td>";
  		echo "<td>" . $class . " - " .$vehicle_class . "</td>";
  		echo "<td>" . $status. "</td>";
  		echo "<td>" . $assignee . "</td>";
  		echo "<td>" . $employee_id . "</td>";
  		echo "<td>" . $employee_no . "</td>";
  		echo "<td>" . $department . "</td>";
  		echo "<td>" . $section . "</td>";
  		echo "<td>" . $is_available . "</td>";
  	echo "</tr>";

  	// insert to database
  /*	$sql = "INSERT INTO sys_insurance_and_registration.iar_company_car_units(cs_no,plate_no,classification,status,assignee,employee_id,employee_no,department,section,is_available)
  			VALUES(:cs_no,:plate_no,:classification,:status,:assignee,:employee_id,:employee_no,:department,:section,:is_available)";
  	$result = $conn->query($sql,array(
  									":cs_no" => $cs_no,
  									":plate_no" => $plate_no,
  									":classification" => $vehicle_class,
  									":status" => $status,
  									":assignee" => $assignee,
  									":employee_id" => $employee_id,
  									":employee_no" => $employee_no,
  									":department" => $department,
  									":section" => $section,
  									":is_available" => $is_available
  								)
  							);		*/
    //  Insert row data array into your database of choice here
}

echo "</table>";