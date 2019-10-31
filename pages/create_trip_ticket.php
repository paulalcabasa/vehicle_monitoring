<?php
    $driver = new Driver();
    $vehicle = new Vehicle();
    $trip_ticket = new TripTicket();
    
    $driver_list = $driver->getDriversList();
    $nature_of_trip_list = $trip_ticket->getNatureOfTrip();
   // $destination_list = json_encode(array_column($trip_ticket->getDestinations(),"destination"));
?>

<!-- Left col -->
<section class="col-lg-7">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Trip Ticket</h3>
    </div>
    <div class="box-body">
        
            <form class="form-horizontal" action="#" method="post">

               <!--  <div class="form-group">
                    <label class="control-label col-md-2">Trip Ticket No.</label>
                    <div class="col-md-10">
                       <span style="font-size:16pt;font-weight:bold;">1</span>
                    </div>
                </div>
                -->
                <input type="hidden" id="txt_checklist_id" name="txt_checklist_id" />
                <div class="form-group">
                    <label class="control-label col-md-2"><abbr title="Job Order Number">JO No.</abbr></label>
                    <div class="col-md-10">
                       <input type="text" class="form-control" placeholder="Please enter the job order number..." id="txt_jo_no"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2"><abbr title="Expected Time of Return">ETR</abbr></label>

                    <div class="col-md-6">
                       <input type="text" class="form-control" placeholder="Please select expected time of return..." id="txt_expected_time_return"/>
                    </div>

                    <div class="col-md-4">
                        <div class="checkbox">
                            <label><input type="checkbox" id="cb_with_vehicle_reservation">Reserve Vehicle</label>
                        </div>
                    </div>  

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Vehicle</label>
                    <div class="col-md-10">
                        <select class="form-control" id="cbo_car_units">
                            <option value="" data-fid="" data-attachments="" data-id="" data-cond="" data-date_created="">Select Vehicle</option>
                        </select>
                        <table class="table table-bordered table-condensed" id="tbl_open_trip_list" style="margin-bottom:0;">
                            <thead>
                                <tr>
                                    <th style="width:10%;"><abbr title="Trip Ticket No">T No.</abbr></th>
                                    <th style="width:50%;">Destination</th>
                                    <th style="width:40%;"><abbr title="Expected Time of Return">ETR</abbr></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Driver</label>
                    <div class="col-md-10">
                        <select class="form-control" id="cbo_drivers">
                            <option value="">Select Driver</option>
                            <?php
                                foreach($driver_list as $d){
                                    $d = (object)$d;
                            ?>
                            <option value="<?php echo $d->driver_id;?>"><?php echo Format::makeUppercase($d->emp_name);?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Nature of Trip</label>
                    <div class="col-md-10">
                        <select class="form-control" id="cbo_nature_of_trip">
                            <option value="">Select nature of trip</option>
                        <?php
                            foreach($nature_of_trip_list as $trip) {
                                $trip = (object)$trip;
                        ?>
                            <option value="<?php echo $trip->id?>"><?php echo $trip->trip_type;?></option>
                        <?php 
                            } 
                        ?>
                        </select>
                    </div>
                </div>

                 <div class="form-group">
                    <label class="control-label col-md-2">Date</label>
                    <div class="col-md-10">
                       <input type="text" class="form-control" value='<?php echo Format::format_date_slash3(Format::getDateToday())." 7:30 AM - ".Format::format_date_slash3(Format::getDateToday())." 5:45 PM";?>' id="txt_attendance_date"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Requested By</label>
                    <div class="col-md-10">
                        <select class="form-control" id="sel_requestor">
                            <option value="">Select requestor</option>
                        <?php
                            $employees_list = $driver->getEmployeesList();
                            foreach($employees_list as $emp) {
                                $emp = (object)$emp;
                        ?>
                            <option value="<?php echo $emp->id?>"><?php echo $emp->emp_name;?></option>
                        <?php 
                            } 
                        ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Purpose</label>
                    <div class="col-md-10">
                        <textarea class="form-control noresize" autocomplete="off"  id="txt_purpose" data-provide="typeahead"  placeholder="Please enter the purpose of the trip..."></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Destination</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" autocomplete="off"  id="txt_destination" data-provide="typeahead"  placeholder="Please enter the destination of the trip..."/>
                    </div>
                </div>

                <!--
                <div class="form-group">
                    <label class="control-label col-md-2">Date Requested</label>
                    <div class="col-md-10">
                       <input type="text" class="form-control" placeholder="Please select date requested..." id="txt_date_requested"/>
                    </div>
                </div> 
                -->
                
                <div class="form-group">
                    <label class="control-label col-md-2">Passengers</label>
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="text" class="form-control" id="txt_passenger_name"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-danger" id="btn_add_passenger">Add</button>
                            </span>
                        </div>
                        <ul class="list-group" id="passenger_list" style="max-height:300px;overflow-y:auto;">
                        </ul>
                    </div>
                </div>
            </form>
   
            
    </div>
    <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="btn_save"><i class="fa fa-save"></i>&nbsp; Save</button>
    </div>
  </div>
</section><!-- /.Left col -->

<!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class="col-lg-5">

<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Checklist</h3>
     </div>
    <div class="box-body">

        <div class="row">
            <label class="col-md-3">Checklist No</label>
            <div class="col-md-9" id="lbl_checklist_id"></div>
        </div>
        
        <div class="row">
            <label class="col-md-3">Condition</label>
            <div class="col-md-9" id="lbl_checklist_condition"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Attachments</label>
            <div class="col-md-9" id="lbl_checklist_attachment"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Date Created</label>
            <div class="col-md-9" id="lbl_checklist_date_created"></div>
        </div>

    </div>

    <div class="box-footer no-border">

    </div>

</div>
  <!-- End of Vehicle Details -->


  <!-- Vehicle Details -->
  <div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Vehicle Details</h3>
     </div>
    <div class="box-body">

        <div class="row">
            <label class="col-md-3">Vehicle ID</label>
            <div class="col-md-9" id="lbl_vehicle_id"></div>
        </div>
        
        <div class="row">
            <label class="col-md-3">CS No</label>
            <div class="col-md-9" id="lbl_cs_no"></div>
        </div>

         <div class="row">
            <label class="col-md-3">Plate No</label>
            <div class="col-md-9" id="lbl_plate_no"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Classification</label>
            <div class="col-md-9" id="lbl_classification"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Model</label>
            <div class="col-md-9" id="lbl_model"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Body Color</label>
            <div class="col-md-9" id="lbl_body_color"></div>
        </div>

         <div class="row">
            <label class="col-md-3">Assignee</label>
            <div class="col-md-9" id="lbl_assignee"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Department</label>
            <div class="col-md-9" id="lbl_department"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Section</label>
            <div class="col-md-9" id="lbl_section"></div>
        </div>

    </div>

    <div class="box-footer no-border">

    </div>

  </div>
  <!-- End of Vehicle Details -->

   <!-- Assignee Details -->
<!--   <div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Assignee Details</h3>
     </div>
    <div class="box-body">

        <div class="row">
            <label class="col-md-3">Assignee</label>
            <div class="col-md-9" id="lbl_assignee"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Department</label>
            <div class="col-md-9" id="lbl_department"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Section</label>
            <div class="col-md-9" id="lbl_section"></div>
        </div>

    </div>


    <div class="box-footer no-border">

    </div>

  </div> -->
  <!-- End of Vehicle Details -->

  <!-- Driver Details -->
  <div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Driver Details</h3>
     </div>
    <div class="box-body">

        <div class="row">
            <label class="col-md-3" >Driver ID</label>
            <div class="col-md-9" id="lbl_driver_id"></div>
        </div>

         <div class="row">
            <label class="col-md-3">Name</label>
            <div class="col-md-9" id="lbl_driver_name"></div>
        </div>

    </div>

    <div class="box-footer no-border">

    </div>

  </div>
  <!-- End of Vehicle Details -->

   <!-- Destination Details -->
  <div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Destination Details</h3>
     </div>
    <div class="box-body">

        <div class="row">
            <label class="col-md-3" >Destination</label>
            <div class="col-md-9" id="lbl_destination"></div>
        </div>

        <div class="row">
            <label class="col-md-3">Zip Code</label>
            <div class="col-md-9" id="lbl_zip_code"></div>
        </div>

        <div class="row">
            <label class="col-md-3"><abbr title="Estimated">Est.</abbr> KM</label>
            <div class="col-md-9" id="lbl_dest_km"></div>
        </div>

        <div class="row">
            <label class="col-md-3"><abbr title="Estimated">Est.</abbr> Toll Fee</label>
            <div class="col-md-9" id="lbl_dest_toll"></div>
        </div>

    </div>

    <div class="box-footer no-border">

    </div>

  </div>
  <!-- End of Vehicle Details -->
  
</section><!-- right col -->

<?php include("includes/modal-information.php");?>