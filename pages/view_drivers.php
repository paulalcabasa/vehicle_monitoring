<?php
    $vehicle = new Vehicle();
    $vehicle_list = $vehicle->getVehicles();
?>
<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-user"></i>
      <h3 class="box-title">Company Drivers List</h3>
      <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-add-new"><i class="fa fa-user-plus"></i> New Driver</button>
       <a href="pages/print_all_drivers_barcode.php" target="_blank" class="btn btn-primary pull-right" style="margin-right:1em;"><i class="fa fa-barcode"></i> Barcode</a>
    </div>
    <div class="box-body">
        
        <table class="table table-striped table-bordered dt-responsive text-center" cellspacing="0" width="100%" id="tbl_drivers_list">
            <thead>
                <th>Picture</th>
                <th>Driver ID</th>
                <th>Name</th>
                <th>Contact No</th>
                <th>Company</th>
                <th>Assigned Vehicle</th>
                <th>Attachment</th> 
                <th>Date Added</th>     
                <th>Action</th>     
            </thead>
            
            <tbody></tbody>
            
            <tfoot>
                <th>Picture</th>
                <td>Driver ID</td>
                <td>Name</td>
                <td>Contact No</td>
                <th>Company</th>
                <th>Assigned Vehicle</th>
                <td>Attachment</td>  
                <td>Date Added</td>   
                <th>Action</th>   
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
                <h4 class="modal-title">Add New Driver</h4>
            </div>

            <div class="modal-body">
                <p class="alert alert-info" id="notif" style="display:none;">Successfully added driver!</p>
                <form id="frm_driver_data" enctype="multipart/form-data" method="POST">
                   
                    <div style="width:100%;margin-bottom:.5em;" class="text-center">
                        <div class="img-wrapper" >
                            <img src="images/driver_pics/anonymous.png" width='200' height='200' id='img_prev' class='img-reponsive img-rounded'/>
                        </div>
                        <span class="btn btn-danger btn-sm  btn-sm btn-file" style="width:18.7%;margin-top:1px;">
                        Select Image <input type="file" id="txt_pic" name="txt_pic" onchange="utils.previewImage('txt_pic','img_prev')"  />
                        </span>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" id="txt_first_name" name="txt_first_name"/>
                        </div>

                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" class="form-control" id="txt_middle_name" name="txt_middle_name"/>
                        </div>

                         <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" id="txt_last_name" name="txt_last_name"/>
                        </div>

                        <div class="form-group">
                            <label>Contact No.</label>
                            <input type="text" class="form-control" id="txt_contact_no" name="txt_contact_no"/>
                        </div>

                    </div>

                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" class="form-control" id="txt_company" name="txt_company"/>
                        </div>

                        <div class="form-group">
                            <label>Assigned Vehicle</label>
                            <select class="form-control" id="sel_car_units" name="sel_car_units">
                                <option value="">Select Vehicle</option>
                                <?php
                              
                                foreach($vehicle_list as $v){
                                    $v = (object)$v;
                                    $label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
                                    echo "<option value=" .$v->id . ">".$label."</option>";
                                   
                                }
                                ?>
                            </select>
                        </div>

                         <div class="form-group">
                            <label>Attachment</label>
                            <input type="file" id="txt_attachment" name="txt_attachment"/>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_add_driver" data-loading-text="Loading...">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade modal-default" id="modal-edit-driver"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Driver Details</h4>
            </div>

            <div class="modal-body">
                <p class="alert alert-info" id="u_notif" style="display:none;">Successfully updated driver details!</p>
                <form id="u_frm_driver_data" enctype="multipart/form-data" method="POST">
                   
                    <div style="width:100%;margin-bottom:.5em;" class="text-center">
                        <div class="img-wrapper" >
                            <img src="images/driver_pics/anonymous.png" width='200' height='200' id='u_img_prev' class='img-reponsive img-rounded'/>
                        </div>
                        <span class="btn btn-danger btn-sm  btn-sm btn-file" style="width:18.7%;margin-top:1px;">
                        Change Image <input type="file" id="u_txt_pic" name="u_txt_pic" onchange="utils.previewImage('u_txt_pic','u_img_prev')"  />
                        </span>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" id="u_txt_first_name" name="u_txt_first_name"/>
                        </div>

                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" class="form-control" id="u_txt_middle_name" name="u_txt_middle_name"/>
                        </div>

                         <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" id="u_txt_last_name" name="u_txt_last_name"/>
                        </div>

                        <div class="form-group">
                            <label>Contact No.</label>
                            <input type="text" class="form-control" id="u_txt_contact_no" name="u_txt_contact_no"/>
                        </div>

                    </div>

                    <div class="col-md-6">
                        

                         <div class="form-group">
                            <label>Company</label>
                            <input type="text" class="form-control" id="u_txt_company" name="u_txt_company"/>
                        </div>

                        <div class="form-group">
                            <label>Assigned Vehicle</label>
                            <select class="form-control" id="u_sel_car_units" name="u_sel_car_units">
                                <option value="">Select Vehicle</option>
                                <?php
                             
                                foreach($vehicle_list as $v){
                                    $v = (object)$v;
                                    $label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
                                    echo "<option value=" .$v->id . ">".$label."</option>";
                                   
                                }
                                ?>
                            </select>
                        </div>

                         <div class="form-group">
                            <label>Attachment</label>
                            <span class="btn btn-danger btn-sm  btn-sm btn-file" style="width:100%;">
                                Change Attachment <input type="file" id="u_txt_attachment" name="u_txt_attachment" />
                            </span>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_update_driver">Save Changes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->