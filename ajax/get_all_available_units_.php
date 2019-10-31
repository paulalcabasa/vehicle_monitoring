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
$table = 'v_available_units';
 
// Table's primary key
$primaryKey = 'id';
$post = (object)$_POST;
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
    array( 'db' => 'classification', 'dt' => 3 ),
    array( 'db' => 'model', 'dt' => 4 )
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'latropcpi',
    'db'   => 'sys_vehicle_monitoring',
    'host' => 'localhost'
);
 


require( '../classes/ssp.class.php' );



echo json_encode($arr);

