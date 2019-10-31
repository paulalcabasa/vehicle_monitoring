<?php
    $vehicle = new Vehicle();
    $encryption = new Encryption();
    $vehicle_id = $encryption->decrypt($get->d);
    $vehicle_details = $vehicle->getVehicleDetails($vehicle_id);
    $vehicle_label = $vehicle_details->plate_no != "" ? $vehicle_details->plate_no : $vehicle_details->cs_no;
    $condition_list = $vehicle->getVehicleCondition();
    $checklist = $vehicle->get_checklist_per_vehicle($vehicle_id);
?>
<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-list"></i>
      <h3 class="box-title">Checklist for <strong><?php echo $vehicle_label; ?></strong></h3>
      <a class="btn btn-danger pull-right" style="margin-left:2em;" href="page.php?p=nckl&d=<?php echo $get->d;?>">New Checklist</a>

    </div>
    <div class="box-body">
        <div class="well">
            <div class="col-md-6">
                <div class="row">
                    <span class="col-md-3 text-bold">Vehicle ID</span>
                    <span class="col-md-9"><?php echo $vehicle_details->unit_id;?></span>
                </div>

                <div class="row">
                    <span class="col-md-3 text-bold">CS No</span>
                    <span class="col-md-9"><?php echo $vehicle_details->cs_no;?></span>
                </div>

                <div class="row">
                    <span class="col-md-3 text-bold">Plate No</span>
                    <span class="col-md-9"><?php echo $vehicle_details->plate_no;?></span>
                </div>

                <div class="row">
                    <span class="col-md-3 text-bold">Classification</span>
                    <span class="col-md-9"><?php echo $vehicle_details->classification;?></span>
                </div>

                <div class="row">
                    <span class="col-md-3 text-bold">Model</span>
                    <span class="col-md-9"><?php echo $vehicle_details->model;?></span>
                </div>

                <div class="row">
                    <span class="col-md-3 text-bold">Body Color</span>
                    <span class="col-md-9"><?php echo $vehicle_details->body_color;?></span>
                </div>
            </div>
            <div class="col-md-6">

                <div class="row">
                    <span class="col-md-3 text-bold">Assignee</span>
                    <span class="col-md-9">
                        <?php
                            if($vehicle_details->vehicle_class_id == 2){
                                echo $vehicle_details->assignee_name;
                            }
                            else {
                                echo $vehicle_details->assignee;
                            }
                        ?>
                    </span>
                </div>

                <div class="row">
                    <span class="col-md-3 text-bold">Department</span>
                    <span class="col-md-9"><?php echo $vehicle_details->department; ?></span>
                </div>

                 <div class="row">
                    <span class="col-md-3 text-bold">Section</span>
                    <span class="col-md-9"><?php echo $vehicle_details->section; ?></span>
                </div>

            </div>
            <div class="clearfix"></div>
        </div>
        <div class="dt-responsive">
        <table class="table table-bordered table-hover" cellspacing="0" width="100%" id="tbl_vehicle_checklist" style="font-size:90%;width:100%">
            
            <thead>
                <th>Checklist ID</th>
                <th>Trip Ticket No</th>
                <th>Condition</th>
                <th class="hide-mobile hide-tablet">KM Reading IN</th>     
                <th class="hide-mobile hide-tablet">KM Reading OUT</th>     
                <th class="hide-mobile">Remarks</th>     
                <th >Date Created</th>     
                <th class="hide-mobile">Created By</th>     
                <th>View Details</th>     
            </thead>
            
            <tbody>
            <?php
                foreach($checklist as $row){
                    $row = (object)$row;
                    $enc_id = $encryption->encrypt($row->checklist_id);
            ?>
                <tr>
                    <td><?php echo $row->checklist_id;?></td>
                    <td><?php echo $row->trip_ticket_no;?></td>
                    <td><?php echo $row->vehicle_condition;?></td>
                    <td class="hide-mobile hide-tablet"><?php echo $row->km_reading_in;?></td>
                    <td class="hide-mobile hide-tablet"><?php echo $row->km_reading_out;?></td>
                    <td class="hide-mobile"><?php echo $row->remarks;?></td>
                    <td><?php echo $row->date_created;?></td>
                    <td class="hide-mobile"><?php echo $row->created_by;?></td>
                    <td><a href="page.php?p=vckd&d=<?php echo $enc_id;?>"><i class="fa fa-edit"></i></a></td>
                </tr>
            <?php
                }
            ?>
            </tbody>
            
        
            
        </table>
    </div>
    </div>

  </div>
</section><!-- /.Left col -->
