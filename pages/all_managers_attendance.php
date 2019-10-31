<?php
/*  $vehicle = new Vehicle();
  $carplan_list = $vehicle->getCarPlanList();
*/
?>
<!-- Left col -->
<section class="col-lg-12">
    <!-- quick email widget -->
    <div class="box box-danger">

        <div class="box-header">
            <i class="fa fa-calendar"></i>
            <h3 class="box-title">All Managers Attendance</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="form-horizontal" method="post" action="pages/print_all_managers_attendance.php" target="_blank">
                        <input type="hidden" name="start_date" id="txt_start_date"/>
                        <input type="hidden" name="end_date" id="txt_end_date"/>
                        <div class="form-group">
                            <label class="col-md-3">Date</label>
                            <div class="col-md-9">
                                <input id="txt_attendance_date" class="form-control" type="text">  
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger pull-right" id="btn_generate">Generate</button>
                                <button type="submit" class="btn btn-success pull-right" disabled="disabled" id="btn_print" style="margin-right:1em;">Print</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" id="tbl_attendance">
                        <thead>
                            <tr>
                                <th style="width:20%;">Employee No</th>
                                <th style="width:30%;">Name</th>
                                <th style="width:30%;">Date</th>
                                <th style="width:10%;">In</th>
                                <th style="width:10%;">Out</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

      <!--   <div class="box-footer clearfix">
            <button class="pull-right btn btn-danger" id="sendEmail"><i class="fa fa-save fa-2x"></i> Print</button>
        </div> -->

    </div>
</section><!-- /.Left col -->

