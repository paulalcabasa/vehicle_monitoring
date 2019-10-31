<div class="modal fade right" id="fuelratemodal"  role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <!-- Add class .modal-side and then add class .modal-top-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-rightt" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title w-100" id="myModalLabel">Update Fuel Rate Details</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post">
          
          <div class="col-md-12  form-group">
            <label class="col-form-label">Enter Fuel Rates</label>
            <p id="ticketno" style="display: none"><?php echo $trip_ticket_no;?></p>
            <input type="text" class="form-control" placeholder="Please enter fuel rate" id="fuelrate">
          </div>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger" id='updateFuelRates'>Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>  