<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-credit-card-alt"></i>
      <h3 class="box-title">Fleet Card Monitoring - Units with Fleet Card</h3>
      
    </div>
    <div class="box-body">
        
           <div class="row"> <!-- first row -->
                <form action="pages/print_fleet_card_monitoring2.php" id="frm_print" method="POST" target="_blank">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-3">Date Range</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="txt_report_date" id="txt_report_date" />
                                    <input type="hidden" class="form-control" name="start_date" id="start_date" />
                                    <input type="hidden" class="form-control" name="end_date" id="end_date" />
                                    <span class="input-group-btn">
                                         <button type="button" class="btn btn-danger pull-right" style="margin-right:1em;" id="btn_generate"><i class="fa fa-flash fa-1x"></i> Generate</button>
                                    </span>
                                </div>
                                <small class="help-block">Please select a range of date you want to view.</small>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-6">
<!--                         <button type="submit" class="pull-right btn btn-danger" disabled="disabled" id="btn_export_pdf"><i class="fa fa-file-pdf-o"></i> PDF</button> -->
                        <!-- <button type="button" class="pull-right btn btn-success" style="margin-right:1em;" disabled="disabled" id="btn_export_excel"><i class="fa fa-file-excel-o"></i> EXCEL</button> -->
                    </div>
                </form>
            </div>  <!-- end of first row -->
            <hr/>

      <!--   <table class="table table-striped table-bordered text-center" id="tbl_fleetcard_monitoring">

            <thead>
                <th>Fleet Card No</th>
                <th>Plate No.</th>
                <th>CS No.</th>
                <th>Vehicle Class</th>
                <th>Driver</th>
                <th>Date & Time</th>
                <th>Event Type</th>
            </thead>
            
            <tbody></tbody>
            

            
        </table> -->
    </div>
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="sendEmail">Save <i class="fa fa-save"></i></button>
    </div> -->
  </div>
</section><!-- /.Left col -->
