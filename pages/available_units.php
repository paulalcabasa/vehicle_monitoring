<!-- Left col -->

<?php 

$classification = "";
    if(isset($_POST['classification'])){
        $classification = $_POST['classification'];
    }
$vehicle = new Vehicle(); 
$result = $vehicle->getAllAvailableUnitsbyclassification($classification);

?>
<form class="form" method="POST" id="frm_tt">
<section class="col-md-12">
    <div class="box box-danger">
        <div class="box-header">
            <i class="fa fa-ticket"></i>
            <h3 class="box-title">IPC Units</h3>
            <a href="pages/print_all_available_units.php?id=<?php echo isset($_POST['classification'])? $_POST['classification'] : ''; ?>" target="_blank" class="btn btn-danger pull-right"><i class="fa fa-print"></i> PDF</a>

            <a href="pages/excel_available_units.php?id=<?php echo isset($_POST['classification'])? $_POST['classification'] : ''; ?>" target="_blank" class="btn btn-danger pull-right"><i class="fa fa-print"></i> Excel</a>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-2" style="margin-top: 5px;">Classification</label>
                    <div class="col-md-6">
                        <select id="classification" class="form-control" name="classification">
                            <option value="" >Select Classification</option>
                            <?php
                            $vehicle = new Vehicle();
                            $classification_list = $vehicle->getClassification();
                            foreach($classification_list as $v){
                                $v = (object)$v;
                                ?>
                                <option value="<?php echo $v->id;?>" <?php echo ($classification == $v->id)? 'selected' : ''; ?>><?php echo $v->classification; ?></option>     
                                <?php    
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        <div class="box-body">

            <table class="table table-bordered table-hover dt-responsive" cellspacing="0"  id="tbl_all_available_units" width="100%">
                <thead>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Assignee</th>
                        <th>CS No</th>
                        <th>Plate No</th>
                        <th>Classification</th>
                        <th>Model</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($result as $row) { $row = (object)$row; ?>
                    <tr>
                        <td><?php echo Format::formatVehicleId($row->id) ; ?></td>
                        <td><?php echo $row->assignee; ?></td>
                        <td><?php echo $row->cs_no; ?></td>
                        <td><?php echo $row->plate_no; ?></td>
                        <td><?php echo $row->classification; ?></td>
                        <td><?php echo $row->model; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Assignee</th>
                        <th>CS No</th>
                        <th>Plate No</th>
                        <th>Classification</th>
                        <th>Model</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>


</section><!-- /.Left col -->
</form>