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
$approver = new Approver();
// DB table to use
$table = 'v_closed_trip_tickets';
 
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
        'formatter' => function($d, $row){
            return "<input type='checkbox' class='cb_trip_ticket_no' value='".$d."' />";
        }
    ),
    array( 'db' => 'id', 'dt' => 1 ),
    array( 'db' => 'vehicle', 'dt' => 2 ),
    array( 'db' => 'trip_type',  'dt' => 3 ),
    array( 'db' => 'purpose', 'dt' => 4 ),
    array( 'db' => 'destination', 'dt' => 5 ),
    array( 
        'db' => 'date_requested', 
        'dt' => 6,
        'formatter' => function( $d, $row ) {
            return Format::format_date($d);
        }
    ),
    array( 
        'db' => 'expected_time_of_return', 
        'dt' => 7,
        'formatter' => function( $d, $row) {

            return ($d != "" ? Format::format_date($d) : "");
        }
    ),
    array( 
        'db' => 'requestor_name', 
        'dt' => 8,
        'formatter' => function($d,$row){
          //  return mb_convert_case($d, MB_CASE_TITLE, "utf8");
            return $d;
          //  return htmlentities(Format::makeUppercase($d));
        }
    ),
    array( 
        'db' => 'approval_status_count',
        'dt' => 9,
        'formatter' => function($d, $row){
            $data = explode(";",$d);
            $approver_count = $data[0];
            $approved_count = $data[1];
            $is_active = "";
            $percentage = ($approved_count/$approver_count) * 100;
            if($percentage >= 0 && $percentage <= 29){
                $progress_color = "progress-bar-danger";
                $is_active = "active";
            }
            else if($percentage >= 30 && $percentage <= 79){
                $progress_color = "progress-bar-warning";
                $is_active = "active";
            }
            else if($percentage == 100){
                $progress_color = "progress-bar-success";
            }
            return '<div class="progress view_progress" style="position: relative;text-align:center;">
                        <div class="progress-bar '.$progress_color.' progress-bar-striped '.$is_active.'" role="progressbar" 
                        aria-valuenow="'.$percentage.'" aria-valuemin="0" 
                        aria-valuemax="100" style="width: '.$percentage.'%;text-align:center;">
                        <span >'.$approved_count.' out of '.$approver_count.'</span>
                        </div>
                    </div>';
        }
    ),
    array( 
        'db' => 'trip_status',
        'dt' => 10,
        'formatter' => function($d, $row){
            if (strpos($d, 'Canceled') !== false) { // checks if the status has overdue
                return "<span class='label label-default'>Canceled</span>";
            }
            else if (strpos($d, 'Overdue') !== false) { // checks if the status has overdue
                return "<span class='label label-danger'>".$d."</span>";
            }
            else if($d == "Open Trip") { // if status is open trip
                return "<span class='label label-success'>".$d."</span>";
            }
            else if($d == "Closed Trip") { // if status is closed trip
                return "<span class='label label-primary'>".$d."</span>";
            }
            else {
                return "<span class='label label-info'>Pending Approval</span>";
            }

        }
    ),
    array(
        'db' => 'id',
        'dt' => 11,
        'formatter' => function($d, $row){
            global $encryption;
            global $user_access;
            global $approver;
            global $user_data;
            $approvers_list = "";
            $enc_id = $encryption->encrypt($d);
            $approval_list = $approver->getApproverByTripTicketNoPending($d);
            if(!empty($approval_list)){
                $approvers_list = "<li class='dropdown-header'>APPROVE ON BEHALF OF</li>";
                foreach($approval_list as $approval){
                    $approval = (object)$approval;
                    $approvers_list .= "<li>
                                            <a href='#' class='btn_approval' data-approver_id=".$approval->approver_id." data-trip_ticket_no=".$d."><i class='fa fa-chevron-right'></i> ".Format::makeUpperCase($approval->approver_name)."</a>
                                        </li>";
                }                       
            }
            $value = "";
            if($user_access->user_type == "Administrator" || $user_access->user_type == "SuperUser") {
                $value = "<div class='btn-group'>
                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                               Action <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu dropdown-menu-right'>
                                <li><a href='page.php?p=et&d=$enc_id'><i class='fa fa-edit fa-1x'></i> Edit</a></li>
                                <li><a href='page.php?p=tr&d=$enc_id' class='btn_view_trip_report'><i class='fa fa-pie-chart fa-1x'></i> Trip Report</a></li>
                                <li role='separator' class='divider'></li>
                                <li><a href='#' data-id='$d' data-trip_status_id='1' class='btn_update_trip_status'><i class='fa fa-unlock fa-1x'></i> Open Trip</a></li>
                                <li><a href='#' data-id='$d' data-trip_status_id='2' class='btn_update_trip_status'><i class='fa fa-lock fa-1x'></i> Close Trip</a></li>
                                <li><a href='#' data-id='$d' data-trip_status_id='3' class='btn_update_trip_status'><i class='fa fa-ban fa-1x'></i> Cancel Trip</a></li>
                                <li role='separator' class='divider'></li>
                                ".$approvers_list."
                            </ul>
                          </div>";
            }
            else if($user_access->user_type == "ReadOnly" && $user_data->employee_id == 536){
                  $value = "<div class='btn-group'>
                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                               Action <span class='caret'></span>
                            </button>
                            <ul class='dropdown-menu dropdown-menu-right'>
                                <li><a href='page.php?p=tr&d=$enc_id' class='btn_view_trip_report'><i class='fa fa-pie-chart fa-1x'></i> Trip Report</a></li>
                                <li role='separator' class='divider'></li>
                                <li><a href='#' data-id='$d' data-trip_status_id='2' class='btn_update_trip_status'><i class='fa fa-lock fa-1x'></i> Close Trip</a></li>
                            </ul>
                          </div>";
            }
            else if($user_access->user_type == "ReadOnly" && $user_data->employee_id == 391){
                  $value = "<a href='page.php?p=tr&d=$enc_id' class='btn_view_trip_report btn btn-sm btn-default' ><i class='fa fa-pie-chart fa-1x'></i> Trip Report</a>";
            }
            return $value;
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


///$arr = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns,"date_requested BETWEEN '2017-08-01' AND '2017-08-30'");
$arr = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns);


$arr["data"] = Format::utf8ize($arr["data"]);

echo json_encode($arr);

