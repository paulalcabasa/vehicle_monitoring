<div class="modal fade" id="exampleModal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Add RFID Detail</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST">
      <div class="modal-body">

            <div class="form-group">
              <label class="col-form-label">Tag number</label>
              <input type="text" class="form-control" placeholder="Please enter tag number" id="txt_tag_no">
            </div>
            <div class="form-group">
                <label class="col-form-label" >Plate Number</label>
                <select class="form-control" id="plate_number" disabled="disabled">
                    <option value="">Plate Number</option>
                    <?php
                        foreach($vehicle_list as $v){
                            $v = (object)$v;
                            $label = ($v->plate_no != "" ? $v->plate_no : $v->cs_no);
                            $is_selected = ($tt_details->vehicle_id == $v->id ? "selected" : "");
                            $checklist = $vehicle->getRecentCheckList($v->id,1);
                            $checklist_ctr = count($checklist);
                            if($is_selected == "selected"){
                                echo '<option value="'.$v->id.'" selected>'.$label.'</option>';
                            }
                            else {
                                if($checklist_ctr != 0){
                                     echo '<option value="'.$v->id.'" $is_selected>'.$label.'</option>';
                                }
                                else {
                                    echo '<option value="'.$v->id.'" $is_selected disabled="disabled">'.$label.' - No checklist</option>';
                                }
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
              <label class="col-form-label" >Transaction Date</label>
              <input type="text" class="form-control"  placeholder="Please select expected time of return..." id="txt_transaction_date" value="<?php echo Format::format_date_slash2(date("Y/m/d g:i a"));?>"/>
          <!--     <input type="date" class="form-control" placeholder="Please enter tag number" id="txt_transaction_date" style="text-transform: uppercase"> -->
            </div>

            <div class="form-group">
              <label class="col-form-label" >Entry Plaza</label>
              <input type="text" class="form-control" placeholder="Please enter entry plaza" id="txt_entry_plaza" style="text-transform: uppercase">
            </div>
            <div class="form-group">
              <label class="col-form-label" >Exit Plaza</label>
              <input type="text" class="form-control" placeholder="Please enter exit plaza" id="txt_exit_plaza" style="text-transform: uppercase">
            </div>
            <div class="form-group">
              <label class="col-form-label" >Amount</label>
              <input type="text" class="form-control input-element" placeholder="Please enter amount" id="txt_amount">
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="Save_Changes">Save changes</button>
      </div>
  </form>
    </div>
  </div>
</div>