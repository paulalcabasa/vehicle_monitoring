  <!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
    <div class="box box-danger">

        <div class="box-header">
            <i class="fa fa-bar-chart-o"></i>
            <h3 class="box-title">Vehicle Usage - Company Car</h3>
        </div>

        <div class="box-body">

            <div class="row"> <!-- first row -->
                <form action="pages/print_vehicle_usage.php" id="frm_print" class="form-horizontal" method="POST" target="_blank">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3">Date Range</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="txt_report_date" id="txt_report_date" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3">Vehicle</label>
                            <div class="col-md-9">
                                <select id="sel_vehicle_units" class="form-control" name="sel_vehicle_units">
                                    <option value="">Select Vehicle</option>
                                <?php
                                    $vehicle = new Vehicle();
                                    $vehicle_list = $vehicle->getVehicles();
                                    foreach($vehicle_list as $v){
                                        $v = (object)$v;
                                        $label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
                                ?>
                                    <option value="<?php echo $v->id;?>"><?php echo $label; ?></option>     
                                <?php    
                                    }
                                ?>
                                </select>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-md-3">Driver</label>
                            <div class="col-md-9">
                                <select class="form-control" id="sel_drivers" name="sel_driver_no">
                                    <option value="">Select Driver</option>
                                    <?php
                                        $driver = new Driver();
                                        $driver_list = $driver->getDriversList();
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
                            <div class="col-md-12">
                                 <button type="submit" class="pull-right btn btn-danger" disabled="disabled" id="btn_export_pdf" style="margin-left:1em;"><i class="fa fa-file-pdf-o"></i> PDF</button>
                                <button type="button" class="btn btn-success pull-right" id="btn_generate">Generate</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                       
                      <!--   <button type="button" class="pull-right btn btn-success" style="margin-right:1em;" disabled="disabled" id="btn_export_excel"><i class="fa fa-file-excel-o"></i> EXCEL</button> -->
                    </div>
                </form>
            </div>  <!-- end of first row -->
            <hr/>

            <div class="row"> <!-- second row -->
                <div class="col-md-12">
                    <table class="table table-bordered table-striped vm-table" border="1" id="tbl_report_data" class="display" cellspacing="0" width="100%">
                        
                        <thead style="background-color:#1f497d;color:#fff;">
                            <tr>
                                <th rowspan="2">CS No.</th>
                                <th rowspan="2">Plate No.</th>
                                <th rowspan="2">Driver</th>
                                <th colspan="2">Passenger</th>
                                <th rowspan="2"><abbr title="Expected Time of Return">ETR</abbr></th>
                                <th rowspan="2">Status</th>
                                <th colspan="2">Time</th>
                                <th colspan="2">KM Reading</th>
                                <th colspan="2"> Fuel Status</th>
                            </tr>
                            <tr>
                                <th>In</th>
                                <th>Out</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>In</th>
                                <th>Out</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>

                    </table>
                </div>
             </div> <!-- end of second row -->
        </div> <!-- end of box body -->

    </div>
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="sendEmail">Save <i class="fa fa-save"></i></button>
    </div> -->
 
</section><!-- /.Left col -->


<?php include('includes/modal-information.php');?>
