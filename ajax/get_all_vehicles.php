<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 require_once("../initialize.php");
 $encryption = new Encryption();

require_once("../../../classes/class.main_conn.php");
require_once("../../../classes/class.employee_masterfile.php");
$dbconn = new Main_Conn();
$employee_masterfile = new Employee_Masterfile();
$user_data = (object)$_SESSION['user_data'];
$user_access = (object)$employee_masterfile->getUserAccess($user_data->employee_id,SYSTEM_ID);
// DB table to use
$table = 'v_vehicle_list';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
     array( 
        'db' => 'id', 
        'dt' => 0,
        'formatter' => function( $d, $row ) {
            return Format::formatVehicleId($d);
        }
    ),
    array( 'db' => 'cs_no',  'dt' => 1 ),
    array( 'db' => 'plate_no', 'dt' => 2 ),
    array( 'db' => 'assignee', 'dt' => 3 ),
    array( 'db' => 'classification', 'dt' => 4 ),
    array( 'db' => 'model', 'dt' => 5 ),
    array( 
        'db' => 'id',
        'dt' => 6,
        'formatter' => function($d, $row){
            global $encryption;
            $enc_id = $encryption->encrypt($d);
            return "<a href='pages/print_vehicle_barcode.php?d=$enc_id' target='_blank'><i class='fa fa-print'></i> Print Barcode</a>";
        }
    ),
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'latropcpi',
    'db'   => 'sys_vehicle_monitoring',
    'host' => 'localhost'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( '../classes/ssp.class.php' );


$arr = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns);

$_SESSION['vehicle_list'] = $arr['data'];

$arr["data"] = Format::utf8ize($arr["data"]);

echo json_encode($arr);

