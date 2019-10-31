<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-line-chart"></i>
      <h3 class="box-title">Trip Ticket Report</h3>
    </div>
    <div class="box-body">
    	<div class="row">
            <form action="pages/print_trip_ticket_report.php" method="POST" target="_blank">
	    	<div class="col-md-6">
	    		<div class="form-group">
	    			<label class="col-md-3">Date</label>
	    			<div class="col-md-9">
                        <div class="input-group">
	    		   		    <input id="txt_report_date" name="txt_report_date" class="form-control" type="text">
                            <input type="hidden" id="txt_search_by" name="txt_search_by" />
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Search by <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="btn_search" data-search="a.date_requested">Date Requested</a></li>
                                    <li><a class="btn_search" data-search="a.expected_time_of_return">Expected Time of Return</a></li>
                                    <li><a class="btn_search" data-search="b.log_time">Time Log</a></li>
                                </ul>
                            </div><!-- /btn-group -->
                        </div>
	    		   		<small class="help-block">
	    		   			Please select range of date to display
	    		   		</small>
	    			</div>
	    		</div>
            
             

	    	</div>
	    	<div class="col-md-6">
                <button type="submit" class="pull-right btn btn-danger"><i class="fa fa-file-pdf-o fa-1x"></i> PDF</button>
                <button type="button" class="pull-right btn btn-success" style="margin-right:1em;" id="btn_excel"><i class="fa fa-file-excel-o fa-1x"></i> Excel</button>
	    	</div>

    	</div>

    	<div class="row">
    		<div class="col-md-12">
                <br/>
    			<table class="table table-bordered" id="tbl_data">
    				<thead>
    					<tr>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;" >Trip Ticket No.</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Jo No.</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;">Plate No</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Purpose</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;">Destination</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Nature of Trip</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Requested By</th>
                            <th rowspan="2" style="vertical-align:middle;text-align:center;">Date Requested</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Expected Time of Return</th>
    						<th rowspan="2" style="vertical-align:middle;text-align:center;">Status</th>
                            <th colspan="2" style="vertical-align:middle;text-align:center;">Time</th>
    					</tr>

                        <tr>
                            <th style="vertical-align:middle;text-align:center;">In</th>
                            <th style="vertical-align:middle;text-align:center;">Out</th>
                        </tr>
    				</thead>
    				<tbody>

    				</tbody>
    			</table>
    		</div>
    	</div>
    </div>
  <!--   <div class="box-footer clearfix">
     
    </div> -->
  </div>
</section><!-- /.Left col -->

