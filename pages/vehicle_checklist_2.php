<?php
  $vehicle = new Vehicle();
  $encryption = new Encryption();
   $list_of_vehicle = $vehicle->getCompanyVehicles();
?>
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
                <tr>
                  <th>Vehicle ID</th>
                  <th>CS No</th>
                  <th>Plate No</th> 
                  <th>Model</th>     
                  <th>Assignee</th>    
                  <th>Checklist</th>
                </tr>     
            </thead>
            
            <tbody>
            <?php 
              foreach($list_of_vehicle as $row) : 
                $row = (object)$row;
                $enc_id = $encryption->encrypt($row->id);
            ?>
              <tr>
                <td><?php echo Format::formatVehicleId($row->id);?></td>
                <td><?php echo $row->cs_no;?></td>
                <td><?php echo $row->plate_no;?></td>
                <td><?php echo $row->model;?></td>
                <td><?php echo $row->assignee;?></td>
                <td><a href='page.php?p=vvc2&d=<?php echo $enc_id;?>' class='' data-id='".$d."'><i class='fa fa-list fa-1x'></i></a></td>
              </tr>
            <?php endforeach;?>
            </tbody>
            
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

