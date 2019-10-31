<?php
	$vehicle = new Vehicle();
	$encryption = new Encryption();
	$checklist_id = $encryption->decrypt($get->d);
  $checklist_details = $vehicle->get_checklist_header_details($checklist_id);
  $vehicle_details = $vehicle->getVehicleDetails($checklist_details->vehicle_id);
	$checklist_categories = $vehicle->getChecklistCategories();  
  $vehicle_condition_list = $vehicle->getVehicleCondition();
?>

<!-- top column -->
<section class="col-lg-12">
   
  <?php if(isset($_SESSION['flash_message'])) : ?>
  <div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <span><?php echo $_SESSION['flash_message']?></span>
  </div>
  <?php unset($_SESSION['flash_message']);
        endif;?>
    <input type="hidden" value="<?php echo $checklist_details->body_defects_image;?>" id="txt_defect_image"/>

	  <!-- Vehicle Details -->
  <div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Vehicle Details</h3>
     </div>
    <div class="box-body">

    	<div class="col-md-6">
        <div class="row">
          <label class="col-md-3">Checklist Number</label>
              <div class="col-md-9 text-bold" style="font-size:14pt;"><?php echo $checklist_id;?></div>
              <input type="hidden" id="txt_checklist_id" value="<?php echo $checklist_id;?>"/>
          </div>

	    	<div class="row">
	            <label class="col-md-3">Model</label>
	            <div class="col-md-9" id="lbl_model"><?php echo $vehicle_details->model;?></div>
	        </div>

	         <div class="row">
	            <label class="col-md-3">Plate No</label>
	            <div class="col-md-9" id="lbl_plate_no"><?php echo $vehicle_details->plate_no;?></div>
	        </div>

	        <div class="row">
	            <label class="col-md-3">Body Color</label>
	            <div class="col-md-9" id="lbl_body_color"><?php echo $vehicle_details->body_color;?></div>
	        </div>

	         <div class="row">
	            <label class="col-md-3">Assigned To</label>
	            <div class="col-md-9" id="lbl_assignee"><?php echo $vehicle_details->assignee;?></div>
	        </div>

	        <div class="row">
	            <label class="col-md-3">Date</label>
	            <div class="col-md-9" id="lbl_model"><?php echo $checklist_details->date_created;?></div>
	        </div>

    	</div>

    	<div class="col-md-6">
    		<form class="form-horizontal">
		        <div class="form-group">
		            <label class="col-md-3">KM Reading Out:</label>
		            <div class="col-md-9">
		            	<input type="text" class="form-control input-sm" name="txt_km_reading_out" id="txt_km_reading_out" value="<?php echo $checklist_details->km_reading_out;?>"/>
		            </div>
		        </div>

		        <div class="form-group">
		            <label class="col-md-3">KM Reading In:</label>
		            <div class="col-md-9">
		            	<input type="text" class="form-control input-sm" name="txt_km_reading_in" id="txt_km_reading_in" value="<?php echo $checklist_details->km_reading_in;?>"/>
		            </div>
		        </div>

		        <div class="form-group">
		            <label class="col-md-3">Trip Ticket No</label>
		            <div class="col-md-9">
		            	<input type="text" readonly="readonly" value="<?php echo $checklist_details->trip_ticket_no;?>" class="form-control input-sm" />
		            </div>
		        </div>
	    	</form>
    	</div>
    </div>

  </div>
  <!-- End of Vehicle Details -->
</section>

<?php
	$radio_index = 0;
	foreach($checklist_categories as $row){
		$row = (object)$row;
    $params = array(
      "checklist_header_id" => $checklist_id,
      "category_id" => $row->id
    );
		$checklist_lines = $vehicle->get_checklist_lines_per_category($params);
?>
<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title"><?php echo $row->category;?></h3>
    </div>
    <div class="box-body">
    	<table class="table table-bordered table-condensed text-center" id="tbl_checklist_items">
    		<thead>
    			<tr>
            <th rowspan="2">Part Description</th>
            <th colspan="4">Status</th>
            <th rowspan="2">Remarks</th>
            <th rowspan="2">Date Repaired</th>
    			</tr>
    			<tr>
    				<th>Good</th>
    				<th>Missing</th>
    				<th>Damage</th>
    				<th>Not Applicable</th>
    			</tr>
    		</thead>
    		<tbody>
		<?php 
			
			foreach($checklist_lines as $item) { 
				$item = (object)$item;
		?>
    			<tr>
    				<td data-line_id="<?php echo $item->line_id;?>"><?php echo $item->description;?></td>
    				<td><label class="radio-inline"><input type="radio" value="1" <?php if($item->item_status_id == 1){ echo 'checked';} ?> name="rdo<?php echo $radio_index;?>" checked="checked"/></label></td>
  					<td><label class="radio-inline"><input type="radio" value="2" <?php if($item->item_status_id == 2){ echo 'checked';} ?> name="rdo<?php echo $radio_index;?>"></label></td>
  					<td><label class="radio-inline"><input type="radio" value="3" <?php if($item->item_status_id == 3){ echo 'checked';} ?> name="rdo<?php echo $radio_index;?>"></label></td>
  					<td><label class="radio-inline"><input type="radio" value="4" <?php if($item->item_status_id == 4){ echo 'checked';} ?> name="rdo<?php echo $radio_index;?>"></label></td>
  					<td><input type="text" class="form-control input-sm" value="<?php echo $item->remarks;?>"/></td>
  					<td><input type="text" class="form-control input-sm txt_date_repaired" value="<?php echo $item->date_repaired;?>"/></td>
    			</tr>
		<?php
				$radio_index++;
			}
		?>
    		</tbody>
    	</table>
    </div>
   
  </div>
</section><!-- /.Left col -->
<?php
	}
?>

<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Body Defects</h3>
    </div>
    <div class="box-body">

    	<div id="wPaint" style="position:relative; width:300; height:500px; border:2px solid #7a7a7a;"></div>
      <br/>
        <center style="margin-bottom: 0;">
          <input type="button" class="btn btn-danger btn-sm" value="Toggle Menu" onclick="console.log($('#wPaint').wPaint('menuOrientation')); $('#wPaint').wPaint('menuOrientation', $('#wPaint').wPaint('menuOrientation') === 'vertical' ? 'horizontal' : 'vertical');"/>
        </center>

      <center id="wPaint-img"></center>

      <br/>

      	<div class="row text-bold">
      		<div class="col-md-2">Legend : </div>
      		<div class="col-md-2">C = Crack</div>
      		<div class="col-md-2">M = Missing</div>
      		<div class="col-md-2">D = Dent</div>
      		<div class="col-md-2">S = Scratch</div>
      		<div class="col-md-2"></div>
      	</div>
      	<br/>
  		<div class="form-group">
  			<label>Remarks</label>
  			<textarea class="form-control" id="txt_remarks"><?php echo $checklist_details->remarks;?></textarea>
  		</div>


     
    </div>
   
  </div>
</section><!-- /.Left col -->

<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->
  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-ticket"></i>
      <h3 class="box-title">Overall Condition</h3>
    </div>
    <div class="box-body">
      <?php
        foreach($vehicle_condition_list as $row){
          $row = (object)$row;
          $is_selected = "";
          if($row->id == $checklist_details->vehicle_condition_id){
            $is_selected = "checked='checked'";
          }
      ?>
          <div class="col-md-4">
            <label class="radio-inline"><input <?php echo $is_selected;?> type="radio" name="rdo_vehicle_condition" value="<?php echo $row->id;?>"><?php echo $row->description;?></label>
          </div>
      <?php
        }
      ?>
    </div>
    <div class="box-footer clearfix">
      <button class="pull-right btn btn-danger" id="btn_save"><i class="fa fa-save"></i>&nbsp; Save Changes</button>
    </div>
  </div>
</section><!-- /.Left col -->
<?php include("includes/modal-information.php");?>