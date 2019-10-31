  <!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-map-marker"></i>
      <h3 class="box-title">Destination</h3>
      <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-add-new">
        <i class="fa fa-map"></i> New Destination
      </button>
    </div>
    <div class="box-body">
        
        <table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" id="tbl_destination">
            <thead>
                <th>Destination</th>
                <th>Toll Fee</th> 
                <th>Distance</th>
                <th>Zip Code</th>
                <th>Area Code</th> 
                <th>Edit</th>     
            </thead>
            
            <tbody></tbody>
            
            <tfoot>
                <td>Destination</td>
                <td>Toll Fee</td> 
                <td>Distance</td>
                <td>Zip Code</td>
                <td>Area Code</td> 
                <th>Edit</th>     
            </tfoot>
            
        </table>
    </div>
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="sendEmail">Save <i class="fa fa-save"></i></button>
    </div> -->
  </div>
</section><!-- /.Left col -->


<?php include("includes/modal-confirmation.php"); ?>

<div class="modal fade modal-default" id="modal-add-new"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add New Destination</h4>
            </div>

            <div class="modal-body">
                <p class="alert alert-info" id="notif" style="display:none;">Successfully added destination!</p>
                <form>
                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" class="form-control" id="txt_destination"/>
                    </div>
                     <div class="form-group">
                        <label>Toll Fee</label>
                          <div class="input-group" style="width:30%;">
                            <input type="text" class="form-control" size="5"  id="txt_toll_fee"/>
                            <span class="input-group-addon">PHP</span>
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Distance</label>
                        <div class="input-group" style="width:30%;">
                            <input type="text" class="form-control" size="5"  id="txt_distance"/>
                            <span class="input-group-addon">KM</span>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label>Zip Code</label>
                        <input type="text" class="form-control" style="width:30%;" id="txt_zip_code"/>
                    </div>
                    <div class="form-group">
                        <label>Area Code</label>
                        <input type="text" class="form-control" style="width:30%;" id="txt_area_code"/>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_add_destination">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade modal-default" id="modal-edit-destination"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Driver Details</h4>
            </div>

            <div class="modal-body">
                <p class="alert alert-info" id="u_notif" style="display:none;"></p>
                <form>
                   <div class="form-group">
                        <label>Destination</label>
                        <input type="text" class="form-control" id="u_txt_destination"/>
                    </div>
                     <div class="form-group">
                        <label>Toll Fee</label>
                          <div class="input-group" style="width:30%;">
                            <input type="text" class="form-control" size="5"  id="u_txt_toll_fee"/>
                            <span class="input-group-addon">PHP</span>
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Distance</label>
                        <div class="input-group" style="width:30%;">
                            <input type="text" class="form-control" size="5"  id="u_txt_distance"/>
                            <span class="input-group-addon">KM</span>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label>Zip Code</label>
                        <input type="text" class="form-control" style="width:30%;" id="u_txt_zip_code"/>
                    </div>
                    <div class="form-group">
                        <label>Area Code</label>
                        <input type="text" class="form-control" style="width:30%;" id="u_txt_area_code"/>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_update_destination">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

