<?php
    $driver = new Driver();
    $vehicle = new Vehicle();
    $trip_ticket = new TripTicket();
    $format = new Format();
    $encryption = new Encryption();
   // $epass = new Epass();
    //$fleet_card = new FleetCard();
    $log_type_list = $trip_ticket->getLogType();
    $log_classification_id = $get->c;
    //$epass_list = $epass->getEpassList();
    //$fleet_card_list = $fleet_card->getFleetCardList();
    $log_id = $encryption->decrypt($get->d);
    $tl_details = $trip_ticket->getTimeLogDetails($log_id);
    $passenger_list = $trip_ticket->getPassengersByTimeLogId($log_id);?>

<input type="hidden" value="<?php echo $log_classification_id;?>" id="txt_log_classification_id" name="log_classification_id"/>

<!-- Left col -->
<section class="col-lg-12 <?php echo ($log_classification_id == 2) ? 'hidden' : '';?>">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Update Trip Ticket</h3>
    </div>
    <div class="box-body">

        <div class="col-md-6">

            
            <!-- fielset for trip details -->
            <fieldset class="my-fieldset">
                <legend>Trip Details</legend>
                <form class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="control-label col-md-2">Trip Ticket No.</label>
                        <div class="col-md-10">
                            <span class="input-group">
                                <input type="text" class="form-control" placeholder="Please enter the trip ticket no." id="txt_tt_no" value="<?php echo $tl_details->trip_ticket_no;?>"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button" id="btn_generate_tt_data" data-loading-text="Loading... <img src='../../img/ajax-loader.gif'/>">Generate</button>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2"><abbr title="Job Order Number">JO No.</abbr></label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_jo_no" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Nature of Trip</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_nature_of_trip" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Purpose</label>
                        <div class="col-md-10">
                            <textarea class="form-control noresize" id="txt_purpose" disabled="disabled" placeholder="Please enter the purpose or destination of the trip..."></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Destination</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" disabled="disabled" id="txt_destination"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Date Requested</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" disabled="disabled" placeholder="Please select date requested..." id="txt_date_requested"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-2">Expected Time of Return</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" disabled="disabled" placeholder="Please select expected time of return..." id="txt_expected_time_return"/>
                        </div>
                    </div>
                </form>
            </fieldset>
            <!-- end of fieldset for trip details -->

            <!-- fieldset for driver details -->
            <fieldset class="my-fieldset">
                <legend>Driver Details</legend>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-2">Driver ID</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_driver_id" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Name</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_driver_name" disabled="disabled" />
                        </div>
                    </div>
                </form>
            </fieldset>
            <!-- end of fieldset for driver details -->


        </div>

        <div class="col-md-6">
             <!-- fieldset for vehicle details -->
             <fieldset class="my-fieldset">
                <legend>Vehicle Details</legend>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-2">Vehicle ID</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_vehicle_id" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">CS No.</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_cs_no" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Plate No.</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_plate_no" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Classification</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_classification" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Model</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_model" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Body Color</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_body_color" disabled="disabled" />
                        </div>
                    </div>

                     <div class="form-group">
                        <label class="control-label col-md-2">Assignee</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_assignee" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Department</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_department" disabled="disabled" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Section</label>
                        <div class="col-md-10">
                           <input type="text" class="form-control" id="txt_section" disabled="disabled" />
                        </div>
                    </div>
                </form>
            </fieldset>
            <!-- end of fieldset for vehicle details -->
        </div>
    </div>
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="btn_save"><i class="fa fa-save"></i>&nbsp; Save</button>
    </div> -->
  </div>
</section><!-- /.Left col -->

<!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class="col-lg-12">

  <!-- Driver Details -->
  <div class="box box-danger">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-clock-o fa-1x"></i> Update Time Log</h3>
     </div>
    <div class="box-body">

        <!-- 2nd row left column -->
        <div class="col-md-6">
            <form class="form-horizontal">
                 <!-- fieldset for vehicle details -->
                 <fieldset class="my-fieldset">
                    <legend>Vehicle Details</legend>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">Vehicle ID</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_vehicle_id" tabindex="1"  style="text-transform:uppercase;" value="<?php echo Format::formatVehicleId($tl_details->vehicle_id);?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">CS No.</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_cs_no" disabled="disabled" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Plate No.</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_plate_no" disabled="disabled" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Classification</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_classification" disabled="disabled" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Model</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_model" disabled="disabled" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-2">Body Color</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_body_color" disabled="disabled" />
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="control-label col-md-2">Assignee</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_assignee" disabled="disabled" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Department</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_department" disabled="disabled" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Section</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_section" disabled="disabled" />
                            </div>
                        </div>
                    </form>
                </fieldset>
                <!-- end of fieldset for vehicle details -->
                <!-- fieldset for driver details -->
                <fieldset class="my-fieldset">
                    <legend>Driver Details</legend>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">Driver ID</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" style="text-transform:uppercase;" id="txt_tl_driver_id" tabindex="0" value="<?php echo $tl_details->driver_no;?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_driver_name" disabled="disabled" />
                            </div>
                        </div>
                    </form>
                </fieldset>
                <!-- end of fieldset for driver details -->
            </form>
        </div>

        <div class="col-md-6">
            <!-- form for trip details -->
            <fieldset class="my-fieldset">
                <legend>Log Details</legend>

                <form class="form-horizontal">
                    <input type="hidden" id="txt_time_log_id" value="<?php echo $log_id;?>"/>
                    <div class="form-group">
                        <label class="control-label col-md-3">Type</label>
                        <div class="col-md-9">
                            <?php
                            $ctr = 0; 
                            foreach($log_type_list as $log_type){
                                $log_type = (object)$log_type;
                                $is_checked = ($tl_details->log_type_id == $log_type->id ? "checked" : ""); 
                            ?>
                            <div class="radio">
                                <label>
                                <input type="radio" name="rdo_log_type" class="rdo_log_type" value="<?php echo $log_type->id;?>" <?php echo ($ctr == 0) ? "checked" : "" ?> <?php echo $is_checked; ?> />
                                <?php echo $log_type->type; ?>
                                </label>
                            </div>
                            <?php
                                $ctr = 1;
                            }
                            
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Km Reading</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="txt_km_reading" value="<?php echo $tl_details->km_reading;?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Fuel status</label>
                        <div class="col-md-9">
                            <select class="form-control" id="sel_fuel_status">
                                <option value="">Select fuel status</option>
                            <?php
                                $list_of_fuel_status = $vehicle->getFuelStatusList();
                                foreach($list_of_fuel_status AS $fuel_status){
                                    $fuel_status = (object)$fuel_status;
                                    $is_checked = ($tl_details->fuel_status_id == $fuel_status->id ? "selected" : "");
                            ?>
                                <option value="<?php echo $fuel_status->id;?>" <?php echo $is_checked; ?> >
                                    <?php echo $fuel_status->status?>
                                </option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">E-pass No.</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="txt_epass" data-id="" value="<?php echo $tl_details->e_pass_id;?>" />
                            <!--
                            <option value="" data-id="">Select E-pass No.</option>
                            <?php
                                foreach($epass_list as $e){
                                    $e = (object)$e;
                            ?>
                            <option value="<?php echo $e->e_pass_no;?>" data-id="<?php echo $e->id;?>"><?php echo $e->e_pass_no;?></option>
                            <?php 
                                }
                            ?>
                            -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Fleet Card No.</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="txt_fleet_card" data-id="" value="<?php echo $tl_details->fleet_card_id;?>" />
                            <!--
                            <option value="" data-id="">Select Fleet Card No.</option>
                            <?php
                                foreach($fleet_card_list as $f){
                                    $f = (object)$f;
                            ?>
                                <option value="<?php echo $f->fleet_card_no;?>" data-id="<?php echo $f->id;?>"><?php echo $f->fleet_card_no;?></option>
                            <?php 
                                }
                            ?>
                            -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Remarks</label>
                        <div class="col-md-9">
                            <textarea class="form-control noresize" placeholder="Remarks" id="txt_remarks"><?php echo $tl_details->remarks;?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Date and Time</label>
                        <div class="col-md-9">
                            <input type="text" readonly="" class="form-control" placeholder="Date and time of log" id="txt_time_log" value="<?php echo Format::format_date_slash2($tl_details->log_time);?>"/>
                        </div>
                    </div>

                </form>
            </fieldset>

            <!-- passengers -->
            <fieldset class="my-fieldset" style="max-height:400px;min-height:400px;overflow:auto;">

                <legend>Passengers</legend>
                <form class="form-horizontal">
                    <div class="input-group">
                        <input type="text" class="form-control" id="txt_passenger"/>
                        <span class="input-group-btn">
                            <button class="btn btn-danger" type="button" id="btn_add_passenger">Add</button>
                        </span>
                    </div>
                </form>
                
                <ul class="list-group" id="list_of_passengers">  
                <?php
                    foreach($passenger_list as $passenger) {
                        $passenger = (object)$passenger;
                ?>
                    <li class="list-group-item"><?php echo $passenger->passenger_name;?></li>
                <?php
                    }
                ?>
                </ul>
                  
            </fieldset>
        </div>
    </div>

    <div class="box-footer no-border">
        <button type="button" class="btn btn-danger pull-right" id="btn_save">Save</button>
    </div>

  </div>
  <!-- End of Vehicle Details -->


</section><!-- right col -->

<?php include("includes/modal-information.php");?>