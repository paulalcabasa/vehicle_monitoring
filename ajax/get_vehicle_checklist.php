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
$table = 'v_vehicle_checklist';
 
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
    array( 'db' => 'model', 'dt' => 3 ),
    array( 'db' => 'assignee', 'dt' => 4 ),
    array( 
        'db' => 'is_available',
        'dt' => 5,
        'formatter' => function($d, $row){
            if($d == "Available"){
                return "<span class='label label-success'>" . $d . "</span>";
            }
            else if($d == "Not available"){
                return "<span class='label label-danger'>" . $d . "</span>";
            }
        }
    ),
    array( 'db' => 'remarks', 'dt' => 6 ),
  /*  array( 
        'db' => 'is_available', 
        'dt' => 7,
        'formatter' => function( $d, $row ) {
            $is_checked = ($d == "Available" ? 'checked' : "");
            $value = '<div class="checkbox">
                        <label><input type="checkbox" value="{$d}" class="cb_toggle_status" '.$is_checked.' /></label>
                      </div>';
            return $value;
        }
    ),*/
    array( 
        'db' => 'id', 
        'dt' => 7,
        'formatter' => function( $d, $row ) {
            return "<a href='#' class='btn_show_remarks' data-id='".$d."'><i class='fa fa-edit fa-1x'></i></button>";
        }
    ),
    array( 
        'db' => 'id', 
        'dt' => 8,
        'formatter' => function( $d, $row ) {
            global $encryption;
            $enc_id = $encryption->encrypt($d);
            return "<a href='page.php?p=vvc&d=$enc_id' class='' data-id='".$d."'><i class='fa fa-list fa-1x'></i></button>";
        }
    )
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

