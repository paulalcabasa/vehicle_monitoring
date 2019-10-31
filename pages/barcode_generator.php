<!-- Left col -->
<section class="col-lg-12">
  <!-- quick email widget -->

  <div class="box box-danger">
    <div class="box-header">
      <i class="fa fa-barcode"></i>
      <h3 class="box-title">Barcode Generator</h3>
      <a href="reports/template/barcode_format.xlsx" class="btn btn-success pull-right"><i class="fa fa-file-excel-o"></i> Download format</a>
    </div>
    <div class="box-body">
        <div class="text-center">
          
            
            <label>Upload list of barcode</label><br/>
            <button type="button" id="btn_trigger_upload" style="border:none;background:transparent;">
                <img src="images/upload.png" width="150">
            </button>
            <form method="POST" enctype="multipart/form-data" id="frm_barcode">
            <input type="file" name="file_barcode_list" id="file_barcode_list" class="hidden"/>
            </form>
        </div>
    </div>

  </div>
</section><!-- /.Left col -->


