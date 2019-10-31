<?php
  $vehicle = new Vehicle();
  $vehicle_list = $vehicle->getVehicles();
  $trip_ticket = new TripTicket();
  $encryption = new Encryption();
  $format = new Format();
  $from_date = date('Y-m-01'); // hard-coded '01' for first day
  $to_date  = date('Y-m-t');
  $vehicle_id = "";
  $user_data = (object)$_SESSION['user_data'];
  require_once("../../classes/class.main_conn.php");
  require_once("../../classes/class.employee_masterfile.php");
  $user_access = (object)$employee_masterfile->getUserAccess($user_data->employee_id,SYSTEM_ID);


  if(isset($_POST['start_date'])){
    $from_date =  $_POST['start_date'];
    $to_date =  $_POST['to_date'];
  }
  if(isset($_POST['sel_vehicle'])){
    $vehicle_id = $_POST['sel_vehicle'];
  }
 
  $errand_logs = $vehicle->get_errand_logs($from_date,$to_date,$vehicle_id);
  
?>
<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-clock-o"></i>
      <h3 class="box-title">Errand Logs</h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?p=vertl";?>">
        <div class="row">
          <div class="col-md-6">
            
              <div class="form-group">
                <label class="col-md-3">Date</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="txt_date" value="<?php echo Format::format_date_slash2($from_date).' - '.Format::format_date_slash2($to_date)?>"/>
                </div>
                  <input type="hidden" name="start_date"  value="<?php echo $from_date;?>"/>
                <input type="hidden" name="to_date" value="<?php echo $to_date;?>"/>
            
              </div>

              <div class="form-group">
                <label class="col-md-3">Vehicle</label>
                <div class="col-md-9">
                  <select class="form-control" id="sel_vehicle" name="sel_vehicle">
                    <option value="">Select Vehicle</option>
                  <?php
                    foreach($vehicle_list as $row){
                      $row = (object)$row;
                      $is_selected = $vehicle_id == $row->id ? "selected" : "";
                      $label = $row->plate_no == "" ? $row->cs_no : $row->plate_no;
                  ?>
                    <option value="<?php echo $row->id;?>" <?php echo $is_selected;?>><?php echo $label; ?></option>
                  <?php
                    }
                  ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3"></label>
                <div class="col-md-9">
                  <button type="submit" class="btn btn-danger btn-sm" id="txt_search">Search</button>
                </div>
              </div>
          </div>

          <div class="col-md-6">
           
          </div>
          </form>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered table-striped"  cellspacing="0" width="100%" id="tbl_logs">
                <thead>
                    <th>Log ID</th>
                    <th>Vehicle ID</th>
                    <th>Plate No</th>
                    <th>CS No</th>
                    <th>Driver</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Event Type</th>
                    <th>Edit</th>
                </thead>

                <tbody>
                <?php foreach($errand_logs as $row) : 
                  $row = (object)$row;
                ?>
                  <tr>
                    <td><?php echo $row->id;?></td>
                    <td><?php echo $row->vehicle_id;?></td>
                    <td><?php echo $row->plate_no;?></td>
                    <td><?php echo $row->cs_no;?></td>
                    <td><?php echo $row->driver_name;?></td>
                    <td><?php echo Format::format_readable_date_only($row->log_time);?></td>
                    <td><?php echo Format::format_12h_time($row->log_time);?></td>
                    <td><?php echo $row->type;?></td>
                    <td>
                      <?php
                          $value = "";
                          $enc_id = $encryption->encrypt($row->id);
                          if($user_access->user_type == "Administrator" || $user_access->user_type == "SuperUser"){
                              $value = "<a target='_blank' href='page.php?p=uetl&d=$enc_id'><i class='fa fa-edit'></i></a>";
                          }
                          else if($user_access->user_type == "ReadOnly"){
                               $value = "<button type='button' class='btn btn-xs btn-default disabled' disabled='disabled'><i class='fa fa-edit'></i></button>";
                          } 
                          echo $value;
                      ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <td>Log ID</td>
                    <td>Vehicle ID</td>
                    <td>Plate No</td>
                    <td>CS No</td>
                    <td>Driver</td>
                    <td>Date</td>
                    <td>Time</td>
                    <td>Event Type</td>
                    <th>Edit</th>
                </tfoot>
                
            </table>
          </div>
        </div>
    </div>
    
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="sendEmail">Save <i class="fa fa-save"></i></button>
    </div> -->
  </div>
</section><!-- /.Left col -->

