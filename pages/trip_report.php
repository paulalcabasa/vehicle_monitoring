<?php
    $trip_ticket = new TripTicket();
    $encryption = new Encryption();
    $driver = new Driver();
    $vehicle = new Vehicle();
    $trip_ticket_no = $encryption->decrypt($get->d);
    $trip_details = $trip_ticket->getCompleteTripTicketDetails($trip_ticket_no);
  //    $tt_details = $trip_ticket->getCompleteTripTicketDetails($trip_ticket_no); // get the trip ticket details
  
    $driver_name = $driver->getDriverName($trip_details->driver_no);
    $vehicle_details = $vehicle->getVehicleDetails($trip_details->vehicle_id);
    $employee_details = $driver->getEmployeeDetailsById($trip_details->create_user);
    $time_logs = $trip_ticket->getTimeLogsByTripTicketNo($trip_ticket_no);
    $trip_ticket_passengers = "* * * NONE * * *";
    $passenger_list = $trip_ticket->getTripTicketPassengers($trip_ticket_no);
    $user_access = (object)$user_access;
    if(!empty($passenger_list)){
        $trip_ticket_passengers = "";
        foreach($passenger_list as $passenger){
            $passenger = (object)$passenger;
            $trip_ticket_passengers .= "<li>" . $passenger->passenger_name . "</li>";
        }
    }
    $approver = new Approver();

  /*  $checklist_no ="";
    $checklist_condition="";
    $checklist_attachments_list="";
    $checklist_date_created="";
    
    if($trip_details->checklist_id != 0){
        $checklist_details = $vehicle->getChecklistDetails2($trip_details->checklist_id);
        $checklist_date_created = Format::format_date($checklist_details->date_created);
    }
*/
    $ob_date = "";
    if($trip_details->ob_date_from != "" && $trip_details->ob_date_to != ""){
        $ob_date = Format::format_date($trip_details->ob_date_from) . " to " . Format::format_date($trip_details->ob_date_to);
    }
?>

<!-- Left col -->
<section class="col-md-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Trip Report</h3>
      <a href="pages/print_trip_report.php?d=<?php echo $get->d;?>" class="btn btn-danger pull-right" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <fieldset class="my-fieldset" style="padding:.5em;">
                    <legend>Trip Ticket</legend>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Trip Ticket No. </span>
                        <span class="col-md-9"><?php echo $trip_ticket_no;?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;"><abbr title="Job Order Number">JO No.</abbr></span>
                        <span class="col-md-9"><?php echo $trip_details->jo_no;?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Nature of Trip</span>
                        <span class="col-md-9"><?php echo $trip_details->trip_type;?></span>
                    </div>
                    <?php if($trip_details->ob_date_from != "" && $trip_details->ob_date_to != ""){ ?>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">OB Date</span>
                        <span class="col-md-9"><?php echo $ob_date;?></span>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Purpose</span>
                        <span class="col-md-9"><?php echo $trip_details->purpose;?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Destination</span>
                        <span class="col-md-9"><?php echo $trip_details->destination;?></span>
                    </div>
                     <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Requested By</span>
                        <span class="col-md-9"><?php echo Format::makeUpperCase($trip_details->requestor);?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Date Requested</span>
                        <span class="col-md-9"><?php echo Format::format_date($trip_details->date_requested);?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;"><abbr title="Expected Time of Return">ETR</abbr></span>
                        <span class="col-md-9"><?php echo $trip_details->expected_time_of_return!="" ? Format::format_date($trip_details->expected_time_of_return) : "";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Prepared by</span>
                        <span class="col-md-9"><?php echo Format::makeUpperCase($employee_details->first_name . " " . substr($employee_details->middle_name,0,1) . ". " . $employee_details->last_name);?></span>
                    </div>
                     <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Passengers</span>
                        <span class="col-md-9">
                         
                            <ol style="padding:0;margin-left:1em;">
                                <?php echo $trip_ticket_passengers;?>
                            </ol>
                        </span>
                    </div>

                      <div class="row">
                        <hr/>
                        <span class="col-md-3" style="font-weight:bold;">Approval</span>
                        <span class="col-md-9">
                         
                            <ol style="padding:0;margin-left:1em;">
                                <?php
                                    $approval_list = $approver->getApproverByTripTicketNo($trip_ticket_no);
                                    foreach($approval_list as $approval){
                                        $approval = (object)$approval;
                                        $display = "";
                                        if($user_access->user_type == "Administrator" || $user_access->user_type == "SuperUser"){
                                            $display = "<a href='#' class='btn_resend' data-emp_id='".$approval->approver_id."' data-trip_ticket_no='".$trip_ticket_no."' ><i class='fa fa-refresh fa-1x'></i><span></span></a> ";
                                        }   
                                                                             
                                        if($approval->is_approved == 1){
                                            $display .=  $approval->approver_name . " <span class='label label-success'>Approved</span> at " . Format::format_date($approval->date_approved);
                                        }
                                        else {
                                            $display .= $approval->approver_name . " <span class='label label-warning'>Pending Approval</span>";
                                        }
                               ?>
                                    <li><?php echo $display;?></li>
                               <?php
                                    }
                               ?>
                            </ol>
                        </span>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-6">
                 <fieldset class="my-fieldset" style="padding:.5em;">
                    <legend>Vehicle and Driver</legend>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Vehicle ID</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? Format::formatVehicleId($trip_details->vehicle_id) : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">CS No</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->cs_no : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Plate No</span>
                        <span class="col-md-9" ><?php echo !empty($vehicle_details) ? $vehicle_details->plate_no : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Classification</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->classification : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Model</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->model : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Body Color</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->body_color : "*** TO FOLLOW ***";?></span>
                    </div>
                     <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Assignee</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->assignee : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Department</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->department : "*** TO FOLLOW ***";?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Section</span>
                        <span class="col-md-9"><?php echo !empty($vehicle_details) ? $vehicle_details->section : "*** TO FOLLOW ***";?></span>
                    </div>
                    <hr/>
                     <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Driver ID</span>
                        <span class="col-md-9" ><?php echo $trip_details->driver_no;?></span>
                    </div>
                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Driver Name</span>
                        <span class="col-md-9" ><?php echo $driver_name;?></span>
                    </div>
                </fieldset>

                <fieldset class="my-fieldset" style="padding:.5em;">
                    <legend>Checklist</legend>

                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Checklist No.</span>
                        <span class="col-md-9"><?php echo $trip_details->checklist_id;?></span>
                    </div>

                     <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Vehicle Condition</span>
                        <span class="col-md-9"><?php echo $trip_details->vc_condition;?></span>
                    </div>

                  <!--   <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Attachments</span>
                        <span class="col-md-9">
                            <?php echo $checklist_attachments_list; ?>
                        </span>
                    </div> -->

                    <div class="row">
                        <span class="col-md-3" style="font-weight:bold;">Date Created</span>
                        <span class="col-md-9"><?php echo $trip_details->checklist_date_created;?></span>
                    </div>

                </fieldset>

            </div>
        </div>
    </div>
    <!-- <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="btn_save"><i class="fa fa-save"></i>&nbsp; Save</button>
    </div> -->
  </div>


  <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                  <span class="bg-red">Time Logs</span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <?php
                foreach($time_logs as $log){
                    $log = (object)$log;

                    $create_user_details = $driver->getEmployeeDetailsById($log->create_user);
                    $passenger_list = $trip_ticket->getPassengersByTimeLogId($log->id);
                    $bg_log_icon = ($log->log_type_id == 1 ? "bg-green" : "bg-blue");
            ?>
            <li>
              <i class="fa fa-sign-<?php echo strtolower($log->log_type).' '. $bg_log_icon;?>"></i>

              <div class="timeline-item">
                

                <h3 class="timeline-header"><i class="fa fa-clock-o"></i> <strong><?php echo $log->log_type;?></strong> - <span style="font-size:12pt;"> <?php echo Format::format_date($log->log_time);?></span></h3>

                <div class="timeline-body">
                    <div class="row">
                        <div class="col-md-6">

                          

                            <div class="row">
                                <span class="col-md-3">Vehicle ID</span>
                                <span class="col-md-9"><?php echo Format::formatVehicleId($log->vehicle_id);?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">CS No</span>
                                <span class="col-md-9"><?php echo $log->cs_no;?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Plate No</span>
                                <span class="col-md-9"><?php echo $log->plate_no;?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Model</span>
                                <span class="col-md-9"><?php echo $log->model;?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Driver ID</span>
                                <span class="col-md-9"><?php echo $log->driver_no;?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Driver Name</span>
                                <span class="col-md-9"><?php echo ucwords(strtolower($log->driver_name));//$driver->getDriverName($log->driver_no);?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">KM Reading</span>
                                <span class="col-md-9"><?php echo $log->km_reading;?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Fuel Status</span>
                                <span class="col-md-9"><?php echo $log->status;?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Logged by</span>
                                <span class="col-md-9"><?php echo Format::makeUpperCase($create_user_details->first_name . " " . substr($create_user_details->middle_name,0,1) . ". " . $create_user_details->last_name);?></span>
                            </div>

                            <div class="row">
                                <span class="col-md-3">Remarks</span>
                                <span class="col-md-9"><?php echo $log->remarks;?></span>
                            </div>


                        </div>  
                       

                        <div class="col-md-6">
                        <?php
                            if(!empty($passenger_list)){    
                        ?>
                            <span>Passengers:</span>
                            <ol> 
                        <?php
                                foreach($passenger_list as $passenger){
                                    $passenger = (object)$passenger;
                        ?>
                                    <li><?php echo $passenger->passenger_name;?></li>
                        <?php 
                                } 
                        ?>
                            </ol>
                        <?php 
                            } 
                        ?> 
                        
                        </div>
                    </div>
                </div>
            
              </div>
            </li>        

            <?php

                } 
            ?>
            <!-- end timeline item -->

            <!-- timeline footer -->
            <li>
              <i class="fa fa-clock-o bg-red"></i>
            </li>

            <!-- end timeline footer -->
          </ul>




</section><!-- /.Left col -->

<?php include("includes/modal-information.php");?>