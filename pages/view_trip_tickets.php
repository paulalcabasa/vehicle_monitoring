<?php
    $driver = new Driver();
    $vehicle = new Vehicle();
    $trip_ticket = new TripTicket();
    $format = new Format();
    $driver_list = $driver->getDriversList();
    $vehicle_list = $vehicle->getVehicles();
    $nature_of_trip_list = $trip_ticket->getNatureOfTrip();
    $trip_ticket_signatory = Constants::getTripTicketSignatory();
    $user_data = (object)$_SESSION['user_data'];
    require_once("../../classes/class.main_conn.php");
    require_once("../../classes/class.employee_masterfile.php");
    $user_access = (object)$employee_masterfile->getUserAccess($user_data->employee_id,SYSTEM_ID);

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
        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
      
      <button type="button" class="btn btn-primary pull-right" id="btn_unselect_all" style="margin-right:1em;">Unselect All</button>
      <button type="button" class="btn btn-info pull-right" id="btn_select_all" style="margin-right:1em;">Select All</button>
    <?php 
        }
    ?>

    </div>
    <div class="box-body">
        <table class="table table-bordered table-hover dt-responsive" cellspacing="0"  id="tbl_trip_ticket" width="100%">
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
            <tbody></tbody>
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