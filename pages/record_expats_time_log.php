<?php
    $driver = new Driver();
    $vehicle = new Vehicle();
    $format = new Format();
    $trip_ticket = new TripTicket();
    $log_type_list = $trip_ticket->getLogType();
?>
<section class="col-lg-12">
    <div class="nav-tabs-custom">
        <!--  header of tabs -->
        <ul class="nav nav-tabs">
          <li class="active"><a aria-expanded="true" href="#tab_1" data-toggle="tab">Log Details</a></li>
          <li class=""><a aria-expanded="false" href="#tab_3" data-toggle="tab">Other Vehicle Details</a></li>
        </ul>
        <!-- tab contents -->
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
                <div class="col-md-6">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">Vehicle ID</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_vehicle_id" tabindex="1"  style="text-transform:uppercase;"/>
                            </div>
                        </div>

                         <div class="form-group">

                            <div class="col-md-6">
                                <label class="control-label col-md-4">CS No.</label>
                                <div class="col-md-8" style="margin-left:-0.3em;">
                                   <input type="text" class="form-control" id="txt_tl_cs_no" disabled="disabled" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label col-md-4">Plate No.</label>
                                <div class="col-md-8">
                                   <input type="text" class="form-control" style="width:110%;" id="txt_tl_plate_no" disabled="disabled" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Driver ID</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" style="text-transform:uppercase;" id="txt_tl_driver_id" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_tl_driver_name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Type</label>
                            <div class="col-md-10">
                                <?php
                                $ctr = 0; 
                                foreach($log_type_list as $log_type){
                                    $log_type = (object)$log_type;
                                ?>
                                <div class="radio">
                                    <label>
                                    <input type="radio" name="rdo_log_type" class="rdo_log_type" value="<?php echo $log_type->id;?>" <?php echo ($ctr == 0) ? "checked" : "" ?> />
                                    <?php echo $log_type->type; ?>
                                    </label>
                                </div>
                                <?php
                                    $ctr = 1;
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <form class="form-horizontal">
                <!-- 
                        <div class="form-group">
                            <label class="col-md-3">Km Reading</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txt_km_reading" />
                            </div>
                        </div> -->

                 <!--        <div class="form-group">
                            <label class="col-md-3">Fuel status</label>
                            <div class="col-md-9">
                                <select class="form-control" id="sel_fuel_status">
                                    <option value="">Select fuel status</option>
                                <?php
                                    $list_of_fuel_status = $vehicle->getFuelStatusList();
                                    foreach($list_of_fuel_status AS $fuel_status){
                                        $fuel_status = (object)$fuel_status;
                                ?>
                                    <option value="<?php echo $fuel_status->id;?>"><?php echo $fuel_status->status?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div> -->

                       <!--  <div class="form-group">
                            <label class="col-md-3">E-pass No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txt_epass" data-id="" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Fleet Card No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txt_fleet_card" data-id="" />
                            </div>
                        </div> -->
                  
                     <!--    <div class="form-group">
                            <label class="col-md-3">Purpose</label>
                            <div class="col-md-9">
                                <textarea class="form-control noresize" placeholder="Purpose" id="txt_remarks"></textarea>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label class="col-md-3">Passengers</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="txt_passenger"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button" id="btn_add_passenger">Add</button>
                                    </span>
                                </div>
                                 <ul class="list-group" id="list_of_passengers"></ul>
                            </div>
                        </div>
                    </form>
                </div>
        <div class="clearfix"></div>
            <hr/>
                <button type="button" class="btn btn-danger pull-right" id="btn_save">Save</button>
    <div class="clearfix"></div>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_2">

           

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
            <div class="clearfix"></div>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_3">
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
                               <input type="text" class="form-control" id="txt_lbl_vehicle_id" tabindex="1" disabled="disabled"  style="text-transform:uppercase;"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">CS No.</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_lbl_cs_no" disabled="disabled" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Plate No.</label>
                            <div class="col-md-10">
                               <input type="text" class="form-control" id="txt_lbl_plate_no" disabled="disabled" />
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
               
            </form>
        </div>

        <div class="col-md-6">
          
        </div>
        <div class="clearfix"></div>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
</section>



<?php include("includes/modal-information.php");?>