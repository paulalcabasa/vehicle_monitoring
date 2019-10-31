<?php

    $driver = new Driver(); // driver class
    $vehicle = new Vehicle(); // vehicle class
    $trip_ticket = new TripTicket(); // trip ticket class
    $format = new Format(); // format class
    $encryption = new Encryption(); // encryption class
    $driver_list = $driver->getDriversList(); // load list of drivers
    $vehicle_list = $vehicle->getVehicles(); // load list of vehicles
    $nature_of_trip_list = $trip_ticket->getNatureOfTrip(); // load list of nature of trip
    $trip_ticket_no = $encryption->decrypt($get->d); // get trip ticket number and decrypt the passed "d" via get
    $tt_details = $trip_ticket->getCompleteTripTicketDetails($trip_ticket_no); // get the trip ticket details
    $enc_checklist_id = $encryption->encrypt($tt_details->checklist_id);
    $vehicle_rfid = $vehicle->get_use_rfid_by_id($tt_details->vehicle_id, $trip_ticket_no);
    date_default_timezone_set('Asia/Manila');
 
 //   $checklist_details = $vehicle->getChecklistDetails($tt_details->checklist_id);
   // $checklist_attachments = $vehicle->getChecklistAttachments($tt_details->checklist_id);

?>

<!-- Left col -->
<section class="col-lg-7">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Update Trip Ticket <!-- <?php echo $user_data->employee_id; ?> --></h3>
    </div>
    <div class="box-body">
            
            <form class="form-horizontal" action="#" method="post">
                <input type="hidden" id="txt_checklist_id" name="txt_checklist_id" value="<?php echo $tt_details->checklist_id;?>"/>
                <div class="form-group">
                    <label class="control-label col-md-2">Trip Ticket No.</label>
                    <div class="col-md-10">
                       <span style="font-size:16pt;font-weight:bold;"><?php echo $trip_ticket_no;?></span>
                       <input type="hidden" id="txt_trip_ticket_no" name="txt_trip_ticket_no" value="<?php echo $trip_ticket_no;?>" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-2"><abbr title="Job Order Number">JO No.</abbr></label>
                    <div class="col-md-10">
                       <input type="text" class="form-control" placeholder="Please enter the job order number..." id="txt_jo_no" value="<?php echo $tt_details->jo_no;?>"/>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-md-2">Expected Time of Return</label>
                    <div class="col-md-10">
                       <input type="text" class="form-control"  placeholder="Please select expected time of return..." id="txt_expected_time_return" value="<?php echo Format::format_date_slash2($tt_details->expected_time_of_return);?>"/>
                    </div>
                </div>
<!--                 <div class="form-group">
                    <label class="control-label col-md-2">Expected Time of Return</label>
                    <div class="col-md-10">
                       <input type="text" class="form-control"  placeholder="Please select expected time of return..." id="txt_transaction_date" value="<?php echo Format::format_date_slash2($tt_details->expected_time_of_return);?>"/>
                    </div>
                </div> -->


                <div class="form-group">
                    <label class="control-label col-md-2">Vehicle</label>
                    <div class="col-md-10">
                        <select class="form-control" id="cbo_car_units" disabled="disabled">
                            <option value="">Select Vehicle</option>
                            <?php
                                foreach($vehicle_list as $v){
                                    $v = (object)$v;
                                    $label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
                                    $is_selected = ($tt_details->vehicle_id == $v->id ? "selected" : "");
                                    $checklist = $vehicle->getRecentCheckList($v->id,1);
                                    $checklist_ctr = count($checklist);
                                    if($is_selected == "selected"){
                                        echo '<option value="'.$v->id.'" selected>'.$label.'</option>';
                                    }
                                    else {
                                        if($checklist_ctr != 0){
                                             echo '<option value="'.$v->id.'" $is_selected>'.$label.'</option>';
                                        }
                                        else {
                                            echo '<option value="'.$v->id.'" $is_selected disabled="disabled">'.$label.' - No checklist</option>';
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>

              <!--   <div class="form-group">
                    <label class="control-label col-md-2">Checklist</label>
                    <div class="col-md-10">
                        <select class="form-control" id="cbo_checklist" name="cbo_checklist"></select>
                    </div>
                </div> -->

                 <div class="form-group">
                    <label class="control-label col-md-2">Driver</label>
                    <div class="col-md-10">
                        <select class="form-control" id="cbo_drivers">
                            <option value="">Select Driver</option>
                            <?php
                                foreach($driver_list as $d){
                                    $d = (object)$d;
                                    $is_selected = ($tt_details->driver_no == $d->driver_id ? "selected" : "");
                            ?>
                            <option value="<?php echo $d->driver_id;?>" <?php echo $is_selected;?> >
                                <?php echo $format->makeUppercase($d->emp_name);?>
                            </option>
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
                                $is_selected = ($tt_details->nature_of_trip_id == $trip->id ? "selected" : "");
                        ?>
                            <option value="<?php echo $trip->id?>" <?php echo $is_selected; ?> >
                                <?php echo $trip->trip_type;?>
                            </option>
                        <?php 
                            } 
                        ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Date</label>
                    <div class="col-md-10">
                    <?php
                        //var_dump($tt_details->ob_date_to);
                        $ob_date = "";

                        if($tt_details->ob_date_from != "" && $tt_details->ob_date_to != ""){
                            $ob_date = Format::format_date_slash2($tt_details->ob_date_from) . " - " . Format::format_date_slash2($tt_details->ob_date_to);
                        }
                       //     var_dump($tt_details->ob_date_from);
                        //var_dump($ob_date);
                    ?>
                 
                       <input type="text" class="form-control" value="<?php echo $ob_date;?>" id="txt_attendance_date"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Purpose</label>
                    <div class="col-md-10">
                        <textarea class="form-control noresize" autocomplete="off"  id="txt_purpose"  placeholder="Please enter the purpose of the trip..."><?php echo $tt_details->purpose; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Destination</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" autocomplete="off"  id="txt_destination" data-provide="typeahead"  placeholder="Please enter the destination of the trip..." value="<?php echo $tt_details->destination; ?>"/>
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
                                $is_selected = ($tt_details->requestor == $emp->id ? "selected" : "");
                        ?>
                            <option value="<?php echo $emp->id?>" <?php echo $is_selected;?> >
                                <?php echo $emp->emp_name;?>
                            </option>
                        <?php 
                            } 
                        ?>
                        </select>
                      
                    </div>
                </div>

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
                            <?php
                                $passenger_list = $trip_ticket->getTripTicketPassengers($trip_ticket_no);
                                if(!empty($passenger_list)){
                                    foreach($passenger_list as $passenger){
                                        $passenger = (object)$passenger;
                                        echo "<li class='list-group-item'>
                                                 <button type='button' class='btn btn-danger btn-xs btn_delete_passenger' data-id='".$passenger->id."'>
                                                    <i class='fa fa-trash fa-1x'></i>
                                                 </button> "
                                                 .$passenger->passenger_name. 
                                              "</li>";
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </div>

            </form>
   

    </div>
    <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="btn_save"><i class="fa fa-save"></i>&nbsp; Save Changes</button>
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
                <label class="col-md-3">Checklist ID</label>
                <div class="col-md-9" id="lbl_checklist_id"><a href='page.php?p=vckd&d=<?php echo $enc_checklist_id;?>'><?php echo $tt_details->checklist_id;?></a></div>
            </div>
            
            <div class="row">
                <label class="col-md-3">Condition</label>
                <div class="col-md-9" id="lbl_checklist_condition"><?php echo $tt_details->vc_condition;?></div>
            </div>


            <div class="row">
                <label class="col-md-3">Date Created</label>
                <div class="col-md-9" id="lbl_checklist_date_created"><?php echo $tt_details->checklist_date_created;?></div>
            </div>

        </div>

    </div>

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



  </div>
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

  

  </div>
  <!-- End of Vehicle Details -->
       <!-- Fuel Rate Details -->
  <div class="box box-danger">
    <div class="box-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="box-title">Fuel Rate Details</h3>
            </div>
            <div class="col-md-6 ">
                <button class='btn btn-sm btn-danger pull-right editedit' id='i_edit' ><i class="fa fa-edit " style="cursor: pointer"></i> Edit </button>
            </div>
        </div>
     </div>
    <div class="box-body">

        <div class="row">
            <label class="col-md-3">Fuel Rate</label>
            <div class="col-md-9" id="lbl_fuelrate">
                <?php
                                $fuel_rate = $trip_ticket->get_fuel_rate($trip_ticket_no);
                                if(!empty($fuel_rate)){
                                    foreach($fuel_rate as $fr){
                                        $fr = (object)$fr;
                                        echo "<span>"
                                                 .$fr->fuel_rate. 
                                              "</span>";
                                    }
                                }
                            ?>


<!--                 <?php
                foreach($fuel_rate as $f) { 
                $f = (object)$f;
                echo $f->fuel_rate; } ?></span>  -->
            </div>
 
        </div>
        </div>

  

  </div>
  <!-- End of Fuel Rate Details -->
  
</section><!-- right col -->

<section class="col-md-12">
    <form method="post" action='#'>
    <div class="box box-danger">                
        <div class="box-header">
           <div class="container-fluid">
               <div class="" >
                    <h3 class="box-title">RFID Table</h3>
                </div>   
            </div>
        </div>
        <div class="box-body">
            <div class='container-fluid'>
<!--                 <div class="col-md-6" >
                    <div class="form-group">
                        <div class="custom-control">
                            <input type="checkbox" class="custom-control-input" id="useRfid">
                            <label class="custom-control-label" for="customSwitch1">Use RFID? (Add Details)</label>
                        </div>
                    </div>
                </div> -->
            </div> 


    <div class="col-md-12" id="divTable">
    <table class="table table-bordered table-hover table-condensed" cellspacing="0" style="font-size:85%; white-space: nowrap; text-transform: uppercase;"  id="tbl_edit_trip_ticket" width="100%">
        <thead>
            <tr>
                <!-- <th class="no-sort"></th> -->
<!--                 <th>ID</th> -->
                <th>Tag no </th>
                <th>Plate No.</th>
                <th>Transaction Date</th>
                <th>Entry Plaza</th>
                <th>Exit Plaza</th>
                <th>Amount</th>
                <th>Last Updated</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>  
            <?php 
                 foreach($vehicle_rfid as $rfid) {
                        $rfid = (object)$rfid;
            ?>
 <tr>
              <!--      <td><input type='checkbox' class='cb_trip_ticket_no' value='<?php echo $row->id;?>' /></td> -->
<!--                 <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>'><?php echo $rfid->id; ?></td> -->
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo $rfid->tag_number; ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo $rfid->plateno; ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date)); ?>'><?php echo date("F j, Y, g:i a", strtotime($rfid->transaction_date));; ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo $rfid->entry_plaza; ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo $rfid->exit_plaza; ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo number_format($rfid->amount,2); ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo date("F j, Y, g:i a", strtotime($rfid->update_date));; ?></td>
                <td class='manage_rfid' data-tagno='<?php echo $rfid->tag_number; ?>' data-id='<?php echo $rfid->id; ?>' data-entryplaza='<?php echo $rfid->entry_plaza; ?>' data-exitplaza='<?php echo $rfid->exit_plaza; ?>' data-amount='<?php echo number_format($rfid->amount, 2); ?>' data-transdate='<?php echo date("m/d/Y h:i A", strtotime($rfid->transaction_date));?>'><?php echo $rfid->empname; ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <!-- <th class="no-sort"></th> -->
<!--                 <th>ID</th> -->
                <th>Tag no.</th>                
                <th>Plate No.</th>
                <th>Transaction Date</th>
                <th>Entry Plaza</th>
                <th>Exit Plaza</th>
                <th>Amount</th>
                <th>Last Updated</th>
                <th>Updated By</th>
            </tr>
        </tfoot>
    </table>
    
        <div class="box-footer clearfix">
<!--             <button class="pull-right btn btn-danger" id="save"><i class="fa fa-save"></i>&nbsp; Save Changes</button> -->  
            <button type="button" class="btn btn-danger pull-right" id='addDetails' data-toggle="modal" data-target="#exampleModal">
 Add Details
</button>           
        </div>
        </div>
        </div>
    </div>

    </form>
</section> 

<?php include("includes/modal-information.php");?>
<?php include("includes/modal-addition.php");?>
<?php include("includes/modal-success.php");?>
<?php include("includes/modal-fuelrates.php");?>