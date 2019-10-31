<div class="modal fade right" id="sideModalTR"  role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-rightt" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title w-100" id="myModalLabel">Update Details</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
          <p id="rfid" style="display: none"></p>
          <div class="col-md-6  form-group">
            <label class="col-form-label">Tag number</label>
            <input type="text" class="form-control" placeholder="Please enter tag number" id="tagno">
          </div>
          <div class="col-md-6  form-group">
            <label class="col-form-label">Transaction Date</label>
<!--             <input type="date" class="form-control" placeholder="Please enter tag number" value="<?php echo date("Y-m-d", strtotime($rfid->transaction_date)); ?>" id="transactiondate"> -->

 <!--            <input type="text" class="form-control"  placeholder="Please select expected time of return..." id="transactiondate" value="<?php echo Format::format_date_slash2($rfid->transaction_date);?>"/>
 -->
                        <input type="text" class="form-control"  placeholder="Please select expected time of return..." id="transactiondate" "/>
          </div>
          <div class="col-md-12 form-group">
            <label class="col-form-label">Entry Plaza</label>
            <input type="text" class="form-control" placeholder="Please enter tag number" id="entryplaza">
          </div>
          <div class="col-md-12 form-group">
            <label class="col-form-label">Exit Plaza</label>
            <input type="text" class="form-control" placeholder="Please enter tag number" id="exitplaza">
          </div>
          <div class="col-md-12 form-group">
            <label class="col-form-label">Amount</label>
            <input type="text" class="form-control input-element2" placeholder="Please enter tag number" id="amount">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" id='delete'>Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger" id='updateChanges'>Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>