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
      <h3 class="box-title">Trip Ticket Report</h3>
    </div>
    <div class="box-body">
    	
            <form action="pages/print_trip_ticket_report.php" class="form-horizontal" method="POST" target="_blank">
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
                        <select class="form-control" id="sel_report_type" name="sel_report_type">
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
                <div class="form-group">
                    <label class="col-md-3"></label>
                    <div class="col-md-9">
                     
                          <button type="button" class="pull-right btn btn-primary" id="btn_search"><i class="fa fa-search fa-1x"></i> Search</button>
                    
                    </div>
                </div>

	    	</div>
	    	<div class="col-md-6">
                <button type="submit" class="pull-right btn btn-danger"><i class="fa fa-file-pdf-o fa-1x"></i> PDF</button>
                <button type="button" class="pull-right btn btn-success" style="margin-right:1em;" id="btn_excel"><i class="fa fa-file-excel-o fa-1x"></i> Excel</button>
	    	</div>

    	

    	<div class="row">
    		<div class="col-md-12">
                
    			<table class="table table-bordered" id="tbl_data">
    				<thead>
    					<tr>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;" >Trip Ticket No.</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Jo No.</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;">Plate No</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Purpose</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;">Destination</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Nature of Trip</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Driver</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;">Date Requested</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Expected Time of Return</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Status</th>
                            <th colspan="2" style="vertical-align:middle;text-align:center;">Time</th>
    					</tr>

                        <tr>
                            <th style="vertical-align:middle;text-align:center;">Out</th>
                            <th style="vertical-align:middle;text-align:center;">In</th>
                        </tr>
    				</thead>
    				<tbody>

    				</tbody>

                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th colspan="13"><span style="font-size:14px;font-weight:bold;" id="lbl_total">0</span></th>
                        </tr>
                    </tfoot>
    			</table>
    		</div>
    	</div>
    </div>
  <!--   <div class="box-footer clearfix">
     
    </div> -->
  </div>
</section><!-- /.Left col -->

