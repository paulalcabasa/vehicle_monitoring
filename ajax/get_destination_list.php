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

// DB table to use
$table = 'destination_master';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes

$columns = array(
    array( 'db' => 'destination', 'dt' => 0 ),
    array( 'db' => 'toll_fee',  'dt' => 1 ),
    array( 'db' => 'distance',  'dt' => 2 ),
    array( 'db' => 'zip_code',  'dt' => 3 ),
    array( 'db' => 'area_code',  'dt' => 4 ),
    array(
        'db' => 'id',
        'dt' => 5,
        'formatter' => function($d, $row){
            global $encryption;
            $enc_id = $encryption->encrypt($d);
            $value = "<a href='#' class='btn_edit' data-id='".$d."'><i class='fa fa-edit fa-1x'></i></a>";
            return $value;
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



$arr["data"] = Format::utf8ize($arr["data"]);

echo json_encode($arr);

