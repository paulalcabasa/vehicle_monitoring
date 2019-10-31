<?php
    $driver = new Driver();
    $vehicle = new Vehicle();
    $trip_ticket = new TripTicket();
    $format = new Format();
    $encryption = new Encryption();
    $approver = new Approver();
    $driver_list = $driver->getDriversList();
    $vehicle_list = $vehicle->getVehicles();
    $nature_of_trip_list = $trip_ticket->getNatureOfTrip();
    $trip_ticket_signatory = Constants::getTripTicketSignatory();
    $user_data = (object)$_SESSION['user_data'];
    require_once("../../classes/class.main_conn.php");
    require_once("../../classes/class.employee_masterfile.php");
    $user_access = (object)$employee_masterfile->getUserAccess($user_data->employee_id,SYSTEM_ID);
    $from_date = date('Y-m-01'); // hard-coded '01' for first day
    $to_date  = date('Y-m-t');
    
    $tt_no = "";
    if(isset($_POST['tt_no'])){
        $tt_no = $_POST['tt_no'];
        $from_date =  $_POST['from_date'];
        $to_date =  $_POST['to_date'];
    }
    $closed_trip_tickets = $trip_ticket->get_closed_trip_tickets($from_date,$to_date,$tt_no);
  
?>

<!-- Left col -->
<section class="col-md-12">

    
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Trip Ticket</h3>

      <?php
       if($user_access->user_type == "Administrator" || $user_access->user_type == "SuperUser") {
        if($user_data->employee_id == "184" || $user_data->employee_id == "483"){
      ?>
        <button type="button" class="btn btn-danger pull-right" id="btn_approve_selected" data-id="<?php echo $user_data->employee_id;?>">Approve Selected <span class="badge selected_label">0</span></button>
      <?php
        }
        else {
      ?>
      <div class="btn-group pull-right">
        <button type="button" class="btn btn-danger dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Approve on behalf of <span class="badge selected_label">0</span> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php
                foreach($trip_ticket_signatory as $signatory){
                    $signatory = (object)$signatory;
                    $emp_details = $driver->getEmployeeDetailsById($signatory->employee_id);
                    echo "<li><a href='#' data-id='".$signatory->employee_id."' class='btn_approve_selected_on_behalf'>".Format::makeUppercase($emp_details->emp_name)."</a></li>";
                }
            ?>
        </ul>
      </div>
      <?php
        }
      ?>
      
      <button type="button" class="btn btn-primary pull-right btn-sm" id="btn_unselect_all" style="margin-right:1em;">Unselect All</button>
      <button type="button" class="btn btn-info pull-right btn-sm" id="btn_select_all" style="margin-right:1em;">Select All</button>
    <?php 
        }
    ?>

    </div>
    <div class="box-body">

        <div class="col-md-8">
            <form class="form" method="POST" id="frm_tt" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?p=vct";?>">
                <div class="form-group">
                    <div class="row">
                        <label class="col-md-3">Request Date</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="lbl_to" style="color: #FFF;background-color: #D9534F;border-color: #D43F3A;">From</span>
                                <input type='text' id="from_date" name="from_date" class="form-control" aria-describedby="basic-addon1" placeholder="Please select from date..." value="<?php echo $from_date;?>"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="lbl_to" style="color: #FFF;background-color: #D9534F;border-color: #D43F3A;">To</span>
                                <input type='text' id="to_date" name="to_date" class="form-control" aria-describedby="lbl_to" placeholder="Please select to date..." value="<?php echo $to_date;?>"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="col-md-3">Trip Ticket No</label>
                        <div class="col-md-9">
                            <input style="width:88%;" type='text' id="tt_no" name="tt_no" class="form-control" placeholder="Enter trip ticket no." value="<?php echo $tt_no;?>"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="submit" value="Search" name="btn_search" id="btn_search" class="btn btn-danger pull-right" style="margin-right:4.2em;">
           
                </div>

            </form>
        </div>
        <div class="col-md-4"></div>

        <table class="table table-bordered table-condensed table-hover" cellspacing="0" style="font-size:85%;"  id="tbl_trip_ticket" width="100%">
            <thead>
                <tr>
                    <th class="no-sort"></th>
                    <th>Trip Ticket No</th>
                    <th>Vehicle</th>
                    <th>Nature of Trip</th>
                    <th>Purpose</th>
                    <th>Destination</th>
                    <th>Date Requested</th>
                    <th><abbr title="Expected Time of Return">ETR</abbr></th>
                    <th>Requestor</th>
                    <th>Approval</th>
                    <th>Status</th>
                    <th class="no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($closed_trip_tickets as $row){
                $row = (object)$row;
            ?>
                <tr>
                    <td><input type='checkbox' class='cb_trip_ticket_no' value='<?php echo $row->id;?>' /></td>
                    <td><?php echo $row->id;?></td>
                    <td><?php echo $row->vehicle;?></td>
                    <td><?php echo $row->trip_type;?></td>
                    <td><?php echo $row->purpose;?></td>
                    <td><?php echo $row->destination;?></td>
                    <td><?php echo Format::format_date($row->date_requested);?></td>
                    <td><?php echo ($row->expected_time_of_return != "" ? Format::format_date($row->expected_time_of_return) : "");?></td>
                    <td><?php echo $row->requestor_name;?></td>
                    <?php
                        $data = explode(";",$row->approval_status_count);
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
                        $app_status =  '<div class="progress view_progress" style="position: relative;text-align:center;">
                                    <div class="progress-bar '.$progress_color.' progress-bar-striped '.$is_active.'" role="progressbar" 
                                    aria-valuenow="'.$percentage.'" aria-valuemin="0" 
                                    aria-valuemax="100" style="width: '.$percentage.'%;text-align:center;">
                                    <span >'.$approved_count.' out of '.$approver_count.'</span>
                                    </div>
                                </div>';
                    ?>
                    <td><?php echo $app_status;?></td>
                    <?php
                        $status = "";
                        if (strpos($row->trip_status, 'Canceled') !== false) { // checks if the status has overdue
                            $status =  "<span class='label label-default'>Canceled</span>";
                        }
                        else if (strpos($row->trip_status, 'Overdue') !== false) { // checks if the status has overdue
                            $status = "<span class='label label-danger'>".$row->trip_status."</span>";
                        }
                        else if($row->trip_status == "Open Trip") { // if status is open trip
                            $status = "<span class='label label-success'>".$row->trip_status."</span>";
                        }
                        else if($row->trip_status == "Closed Trip") { // if status is closed trip
                            $status = "<span class='label label-primary'>".$row->trip_status."</span>";
                        }
                        else {
                            $status = "<span class='label label-info'>Pending Approval</span>";
                        }
                    ?>
                    <td><?php echo $status;?></td>
                    <?php
                        $button_element = "";
                        $approvers_list = "";
                        $enc_id = $encryption->encrypt($row->id);
                        $approval_list = $approver->getApproverByTripTicketNoPending($row->id);
                        if(!empty($approval_list)){
                            $approvers_list = "<li class='dropdown-header'>APPROVE ON BEHALF OF</li>";
                            foreach($approval_list as $approval){
                                $approval = (object)$approval;
                                $approvers_list .= "<li>
                                                        <a href='#' class='btn_approval' data-approver_id=".$approval->approver_id." data-trip_ticket_no=".$row->id."><i class='fa fa-chevron-right'></i> ".Format::makeUpperCase($approval->approver_name)."</a>
                                                    </li>";
                            }                       
                        }
                        $value = "";
                        if($user_access->user_type == "Administrator" || $user_access->user_type == "SuperUser") {
                            $button_element = "<div class='btn-group'>
                                        <button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                           Action <span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu dropdown-menu-right'>
                                            <li><a href='page.php?p=et&d=$enc_id'><i class='fa fa-edit fa-1x'></i> Edit</a></li>
                                            <li><a href='page.php?p=tr&d=$enc_id' class='btn_view_trip_report'><i class='fa fa-pie-chart fa-1x'></i> Trip Report</a></li>
                                            <li role='separator' class='divider'></li>
                                            <li><a href='#' data-id='$row->id' data-trip_status_id='1' class='btn_update_trip_status'><i class='fa fa-unlock fa-1x'></i> Open Trip</a></li>
                                            <li><a href='#' data-id='$row->id' data-trip_status_id='2' class='btn_update_trip_status'><i class='fa fa-lock fa-1x'></i> Close Trip</a></li>
                                            <li><a href='#' data-id='$row->id' data-trip_status_id='3' class='btn_update_trip_status'><i class='fa fa-ban fa-1x'></i> Cancel Trip</a></li>
                                            <li role='separator' class='divider'></li>
                                            ".$approvers_list."
                                        </ul>
                                      </div>";
                        }
                        else if($user_access->user_type == "ReadOnly" && $user_data->employee_id == 536){
                              $button_element = "<div class='btn-group'>
                                        <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                           Action <span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu dropdown-menu-right'>
                                            <li><a href='page.php?p=tr&d=$enc_id' class='btn_view_trip_report'><i class='fa fa-pie-chart fa-1x'></i> Trip Report</a></li>
                                            <li role='separator' class='divider'></li>
                                            <li><a href='#' data-id='$row->id' data-trip_status_id='2' class='btn_update_trip_status'><i class='fa fa-lock fa-1x'></i> Close Trip</a></li>
                                        </ul>
                                      </div>";
                        }
                        else if($user_access->user_type == "ReadOnly" && $user_data->employee_id == 391){
                              $button_element = "<a href='page.php?p=tr&d=$enc_id' class='btn_view_trip_report btn btn-sm btn-default' ><i class='fa fa-pie-chart fa-1x'></i> Trip Report</a>";
                        }
                        
                    ?>
                    <td><?php echo $button_element;?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <td>Trip Ticket No</td>
                    <td>Vehicle</td>
                    <td>Nature of Trip</td>
                    <td>Purpose</td>
                    <td>Destination</td>
                    <td>Date Requested</td>
                    <td><abbr title="Expected Time of Return">ETR</abbr></td>
                    <td>Requestor</td>
                    <th>Approval</th>
                    <td>Status</td>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table> 
    </div>
    <!-- <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="btn_save"><i class="fa fa-save"></i>&nbsp; Save</button>
    </div> -->

  </div>

       
</section><!-- /.Left col -->

<?php include("includes/modal-information.php");?>
<?php include("includes/modal-confirmation.php");?>