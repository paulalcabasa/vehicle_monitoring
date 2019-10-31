  <!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-credit-card"></i>
      <h3 class="box-title">List of Company Cars</h3>
  
    </div>
    <div class="box-body">
        
        <table class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%" id="tbl_checklist">
            <thead>
                <th>Vehicle ID</th>
                <th>CS No</th>
                <th>Plate No</th> 
                <th>Model</th>     
                <th>Assignee</th>     
                <th>Status</th>     
                <th>Remarks</th>     
              <!--   <th>Toggle</th>   -->   
                <th>Change Remarks</th>     
                <th>Checklist</th>     
            </thead>
            
            <tbody></tbody>
            
         <!--    <tfoot>
                <td>Vehicle ID</td>
                <td>CS No</td>
                <td>Plate No</td> 
                <td>Model</td>     
                <td>Assignee</td>     
                <td>Toggle</td>    
            </tfoot> -->
            
        </table>
    </div>
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="sendEmail">Save <i class="fa fa-save"></i></button>
    </div> -->
  </div>
</section><!-- /.Left col -->


<div class="modal fade modal-default" id="modal-add-remarks"  tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Vehicle Checklist remarks</h4>
            </div>

            <div class="modal-body">
               
                <form id="frm_remarks" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="txt_vehicle_id" name="txt_vehicle_id"/>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" id="txt_remarks"></textarea>
                        </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_add_remarks">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
