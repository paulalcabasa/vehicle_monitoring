<!-- Left col -->

<?php 

$vehicle = new Vehicle(); 

$inside_ipc_flag = true;
$outside_ipc_flag = false;
$available_only_flag = false;
$selected_class = "";

if(isset($_POST['cb_in'])){
    $inside_ipc_flag = true;
}
else {
    $inside_ipc_flag = false;
}
if(isset($_POST['cb_out'])){
    $outside_ipc_flag = true;
}
else {
    $outside_ipc_flag = false;
}
if(isset($_POST['cb_avail'])){
    $available_only_flag = true;
}
else {
    $available_only_flag = false;
}
if(isset($_POST['classification'])){
    $selected_class = $_POST['classification'];
}

$result = $vehicle->getVehicleUnits($inside_ipc_flag,$outside_ipc_flag,$available_only_flag,$selected_class);

$classification = $vehicle->getVehicleClassifications();

?>

<section class="col-md-12">
    <div class="box box-danger">
        <div class="box-header">
            <i class="fa fa-ticket"></i>
            <h3 class="box-title">IPC Units Status Report</h3>
        <!--     <a href="pages/print_all_available_units.php?id=<?php echo isset($_POST['classification'])? $_POST['classification'] : ''; ?>" target="_blank" class="btn btn-danger pull-right"><i class="fa fa-print"></i> PDF</a>

            <a href="pages/excel_available_units.php?id=<?php echo isset($_POST['classification'])? $_POST['classification'] : ''; ?>" target="_blank" class="btn btn-danger pull-right"><i class="fa fa-print"></i> Excel</a> -->
        </div>
        
       
        <div class="box-body">

            <div class="row">
                <div class="col-md-6">
                    <form class="form" method="POST" id="frm_filters">
                        <div class="form-group">
                            <label class="control-label col-md-3">Filters</label>
                            <div class="col-md-9">
                                <div class="checkbox">
                                    <label>
                                        <input id="cb_in" value="true" name="cb_in" class="form-check-input" type="checkbox" <?php echo ($inside_ipc_flag) ? "checked" : "";?> />
                                        Inside IPC
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label >
                                        <input id="cb_out" value="true" name="cb_out" class="form-check-input" type="checkbox" <?php echo ($outside_ipc_flag) ? "checked" : "";?>  />
                                        Outside IPC
                                    </label>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input id="cb_avail" value="true" name="cb_avail" class="form-check-input" type="checkbox" <?php echo ($available_only_flag) ? "checked" : "";?>  />
                                        Show only available units
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Classification</label>
                            <div class="col-md-9">
                               
                                <select class="form-control" name="classification" id="sel_classification">
                                  <option value="" <?php echo $selected_class == "" ? "selected" : "";?>>All</option>
                                  <?php
                                    foreach($classification as $class){
                                        $class = (object)$class;
                                        $is_selected = ($class->id == $selected_class) ? "selected" : "";
                                ?>
                                    <option value="<?php echo $class->id;?>" <?php echo $is_selected;?>><?php echo $class->classification;?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                      <!--   <div class="form-group">
                            <div class="col-md-12 ">
                                <button type="submit" class="btn btn-sm btn-wide btn-danger">Generate</button>
                            </div>
                        </div> -->
                    </form>
                </div>
            </div>
        
            <br>
            
            <table class="table table-bordered table-hover dt-responsive" cellspacing="0"  id="tbl_vehicle_units" style="font-size:90%;" width="100%">
                <thead>
                    <tr>
                        <!--<th>Vehicle ID</th> -->
                        <th>CS No</th>
                        <th>Plate No</th>
                        <th>Class</th>
                        <th>Model</th>
                        <!--<th>Last Driver</th> -->
                        <th>Event</th>
                        <th>Last Log Time</th>
                        <th>Trip Ticket</th>
                        <th>Trip Status</th>
                        <th>Expected Return</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($result as $row) { $row = (object)$row; 
                    ?>
                    <tr>
         
                        <td><?php echo $row->cs_no; ?></td>
                        <td><?php echo $row->plate_no; ?></td>
                        <td><?php echo $row->classification; ?></td>
                        <td><?php echo $row->model; ?></td>
                        <td><?php echo $row->type; ?></td>
                        <td><?php echo $row->log_time; ?></td>
                        <td><a href="#" class="btn_view_trip_ticket_details" data-trip_ticket_no="<?php echo $row->trip_ticket_no;?>"><?php echo $row->trip_ticket_no; ?></a></td>
                        <td><?php echo $row->status; ?></td>
                        <td><?php echo $row->etr; ?></td>
                    
                    </tr>
                    <?php } ?>
                </tbody>
              
            </table>

        </div>
    </div>


</section><!-- /.Left col -->
<?php include('includes/modal-information-large.php');?>
