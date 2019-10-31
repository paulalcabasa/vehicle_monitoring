<?php 
    $vehicle = new Vehicle();
    $vehicle_list = $vehicle->getVehicles();
?>
<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-line-chart"></i>
      <h3 class="box-title">Trip Log Report</h3>
    </div>
    <div class="box-body">
    	
        <form action="ajax/xls_trip_log_report.php" class="form-horizontal" method="POST">
          <input type="hidden" name="start_date" id="start_date" />
          <input type="hidden" name="end_date" id="end_date" />
	    	<div class="col-md-6">
	    		<div class="form-group">
	    			<label class="col-md-3">Date</label>
	    			<div class="col-md-9">
	    		   		<input id="txt_report_date" name="txt_report_date" class="form-control" type="text">
	    		   		<small class="help-block">
	    		   			Please select range of date to display
	    		   		</small>
	    			</div>
	    		</div>
                
                <div class="form-group">
                    <label class="col-md-3">Search By</label>
                    <div class="col-md-9">
                        <select class="form-control" id="search_by" name="search_by">
                           <option value="a.date_requested">Date Requested</option>
                           <option value="a.expected_time_of_return">Expected Time of Return</option>
                           <option value="b.log_time">Time Log</option>
                        </select>
                    </div>
                </div> 
                
                <div class="form-group">
                    <label class="col-md-3">CS No. / Plate No.</label>
                    <div class="col-md-9">
                        <select class="form-control" id="sel_vehicle" name="sel_vehicle">
                            <option value=""></option>
                            <?php foreach ($vehicle_list as $v) { 
                                $v = (object) $v;?>
                            <option value="<?php echo $v->id;?>"><?php echo ($v->plate_no != ""  ? $v->plate_no : $v->cs_no) . " - " . $v->model . " - " . $v->body_color;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
              <!--   <div class="form-group">
                    <label class="col-md-3">Format</label>
                    <div class="col-md-9">
                        <select class="form-control" id="sel_report_format" name="sel_report_format">
                           <option value="">Excel</option>
                           <option value="">PDF</option>
                        </select>
                    </div>
                </div>  -->
                <div class="form-group">
                    <label class="col-md-3"></label>
                    <div class="col-md-9">
                          <button type="submit" class="pull-right btn btn-primary" id="btn_search">Generate</button>
                    </div>
                </div>

	    	</div>
      </form>
	    	<!-- <div class="col-md-6">
                <button type="submit" class="pull-right btn btn-danger"><i class="fa fa-file-pdf-o fa-1x"></i> PDF</button>
                <button type="button" class="pull-right btn btn-success" style="margin-right:1em;" id="btn_excel"><i class="fa fa-file-excel-o fa-1x"></i> Excel</button>
	    	</div> -->

    
    	</div>
    </div>
  <!--   <div class="box-footer clearfix">
     
    </div> -->
  </div>
</section><!-- /.Left col -->
