<?php
    $vehicle = new Vehicle();
    $encryption = new Encryption();
    $vehicle_id = $encryption->decrypt($get->d);
    $vehicle_details = $vehicle->getVehicleDetails($vehicle_id);
    $vehicle_label = $vehicle_details->plate_no != "" ? $vehicle_details->plate_no : $vehicle_details->cs_no;
     $condition_list = $vehicle->getVehicleCondition();
?>
<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-list"></i>
      <h3 class="box-title">Recent Checklist for <strong><?php echo $vehicle_label; ?></strong></h3>
    
      <button class="btn btn-danger pull-right" type="button" data-toggle="modal" data-target="#modal-add-checklist">New Checklist</button>
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
        <table class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%" id="tbl_checklist">
            
            <thead>
                <th>Checklist ID</th>
                <th>Condition</th>
                <th>Attachment</th>
                <th>Status</th>
                <th>Date Created</th>     
                <th>Created By</th>     
            </thead>
            
            <tbody>
                <?php
                    $recent_checklist = $vehicle->getAllRecentChecklist($vehicle_details->orig_id,10);
                    foreach($recent_checklist as $checkist){
                        $checkist = (object)$checkist;
                        $v_cond = "";
                        $status = "";
                        if($checkist->vehicle_condition_id == 1){ // if okay
                            $v_cond = "<span class='label label-success'>".$checkist->v_cond."</span>";
                        }
                        else {
                            $v_cond = "<span class='label label-danger'>".$checkist->v_cond."</span>";
                        }

                        if($checkist->vehicle_checklist_status_id == 1){ // if okay
                            $status = "<span class='label label-success'>".$checkist->checklist_status."</span>";
                        }
                        else {
                            $status = "<span class='label label-danger'>".$checkist->checklist_status."</span>";
                        }

                        
                ?>
                    <tr>
                        <td><?php echo Format::formatChecklistId($checkist->id);?></td>
                        <td><?php echo $v_cond;?></td>
                        <td>
                            <?php
                                $checklist_attachments = $vehicle->getChecklistAttachments($checkist->id);
                                foreach($checklist_attachments as $attachment){
                                    $attachment = (object)$attachment;
                                    echo "<a href='attachments/".$attachment->attachment."' target='_blank'><i class='fa fa-paperclip'></i> ".$attachment->attachment."</a><br/>";
                                }
                            ?>
                        </td>
                        <td><?php echo $status;?></td>
                        <td><?php echo Format::format_date($checkist->date_created);?></td>
                        <td><?php echo $checkist->created_by;?></td>
                        
                    </tr>
                <?php
                    }
                ?>
            </tbody>
            
        
            
        </table>
    </div>
   <!--  <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="sendEmail">Save <i class="fa fa-save"></i></button>
    </div> -->
  </div>
</section><!-- /.Left col -->


<div class="modal fade modal-default" id="modal-add-checklist"  tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Vehicle Checklist</h4>
            </div>

            <div class="modal-body">
                <p class="alert alert-danger" id="upload_notif"></p>
                <form id="frm_checklist" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="txt_vehicle_id" name="txt_vehicle_id" value="<?php echo $vehicle_details->orig_id;?>"/>
                    <div class="form-group">
                        <label>Condition</label>
                      <!--   <select class="form-control" id="sel_condition" name="sel_condition">
                        <?php
                            $condition_list = $vehicle->getVehicleCondition();
                            foreach($condition_list as $condition){
                                $condition = (object)$condition;
                        ?>
                            <option value="<?php echo $condition->id;?>"><?php echo $condition->description;?></option>
                        <?php
                            }
                        ?>
                        </select> -->
                      
                        <div class="radio">
                            <label>
                                <input type="radio"  checked="checked" name="rdo_condition" value="1" id="rdo_cond_ok" />
                                Okay
                            </label>
                        </div>

                        <div class="radio">
                            <label>
                                <input type="radio" value="2" name="rdo_condition" id="rdo_cond_ng" />
                                Not good
                            </label>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Attachment <small style="font-weight:normal;">(Maximum file upload size of 4mb)</small></label>
                        <input type="file" id="file_checklist_attachment" name="file_checklist_attachment[]" multiple />
                        <ol id="attachment_list"></ol>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_add_checklist">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
