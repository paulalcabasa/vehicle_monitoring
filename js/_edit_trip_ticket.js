/*
 *
 * Author: 		 John Paul M. Alcabasa
 * System : 	 Vehicle Monitoring
 * Date Created: January 7, 2016
 * Description:  Processes user input in updating trip ticket 
 * Version: 	 1.0 
 *
 */

function loadVehicleDetails(vehicle_id,trip_ticket_no){
	$.ajax({
		type:"POST",
		data:{
			vehicle_id : vehicle_id,
			trip_ticket_no : trip_ticket_no
		},
		url:"ajax/get_vehicle_details_only.php",
		success:function(response){

			var data = JSON.parse(response);
			// label for details of vehicle
			$("#lbl_vehicle_id").text(data.vehicle_details.unit_id);
			$("#lbl_cs_no").text(data.vehicle_details.cs_no);
			$("#lbl_plate_no").text(data.vehicle_details.plate_no);
			$("#lbl_classification").text(data.vehicle_details.classification);
			$("#lbl_model").text(data.vehicle_details.model);
			$("#lbl_body_color").text(data.vehicle_details.body_color);
			// label for details of assignee
			$("#lbl_assignee").text(data.vehicle_details.assignee);
			$("#lbl_department").text(data.vehicle_details.department);
			$("#lbl_section").text(data.vehicle_details.section);
			// $("#lbl_fuelrate").text(data.trip_ticket_details.fuelrate);
		
			/*$("#lbl_checklist_id").text(data.checklist.cl_fid);
			$("#lbl_checklist_condition").text(data.checklist.v_cond);
			$("#lbl_checklist_date_created").text(data.checklist.date_created);
			$("#lbl_checklist_attachment").html(data.checklist.attachments);

			$("#txt_checklist_id").val(data.checklist.cl_id);*/

			$("#mdl_plate_no").text(data.vehicle_details.plate_no);
		}
	});
}

/*function loadVehicleChecklist(vehicle_id){

	$.ajax({
		type:"POST",
		data:{
			vehicle_id : vehicle_id
		},
		url:"ajax/get_recent_vehicle_checklist.php",
		success:function(response){
		
		}
	});
}*/

function setDriverDetails(employee_no,name){
	$("#lbl_driver_id").text(employee_no);
	$("#lbl_driver_name").text(name);
}

$(document).ready(function(){

    var table = $('#tbl_edit_trip_ticket').DataTable({
        'searching' : true,
        'scrollX'	: true,
         "lengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]]
    });

	// load details of vehicle
	loadVehicleDetails($("#cbo_car_units").val(),$("#txt_trip_ticket_no").val());
	// set details of driver
//	loadVehicleChecklist($("#cbo_car_units").val());
	setDriverDetails($("#cbo_drivers").val(),$("#cbo_drivers option:selected").text());

	/* sets header of the page */
	utils.setPageHeader("page_header","Vehicle Monitoring System <small>Trip Ticket</small>","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Create Trip Ticket</li>");

	/*
	 *
	 * "elem_" variable name initial signifies that the variable is an "ELEMENT"
	 *
	 */
	var elem_trip_ticket_no = $("#txt_trip_ticket_no");
	var elem_jo_no = $("#txt_jo_no");
	var elem_driver_no = $("#cbo_drivers");
	var elem_vehicle_id = $("#cbo_car_units");
	var elem_nature_of_trip_id = $("#cbo_nature_of_trip");
	var elem_purpose = $("#txt_purpose");
	var elem_expected_time_return = $("#txt_expected_time_return");
	var elem_txt_transaction_date = $("#txt_transaction_date");
	var elem_transactiondate = $("#transactiondate");
	var elem_txt_tag_no = $("#txt_tag_no");
	var elem_destination = $("#txt_destination");
	var errorCtr = 0;
	var isSubmit = false;
	var elem_requestor = $("#sel_requestor");
	var start_date = "";
	var end_date = "";
	$("#cbo_drivers").select2(); // transforms combobox to select2
	$("#cbo_car_units").select2(); // transforms combobox to select2
	$("#cbo_nature_of_trip").select2(); // transforms combobox to select2
	$("#txt_expected_time_return").datetimepicker(); // transforms input to datetimpicker instance
	$("#txt_transaction_date").datetimepicker(); // transforms input to datetimpicker instance
	$("#transactiondate").datetimepicker(); // transforms input to datetimpicker instance
	$("#sel_requestor").select2();

	/*
	 *
     * Fetches data of driver from database
     * Generates vehicle details when the driver has a carplan
	 * @params employee_no = employee_no of employee from employee_masterfile
	 * @returns carplan details
	 *
	 */
	$("#cbo_drivers").on("change",function(){
		var employee_no = $(this).val(); 
		var driver_name = $("#cbo_drivers option:selected").text();
		setDriverDetails(employee_no,driver_name);
	});

	$('input[type="checkbox"]').click(function () {
		if($(this).prop('checked') == true) {
			$('#addDetails').css('display', 'block');
		} else {
			$('#addDetails').css('display', 'none');
		}
	});	
	/*
	 *
	 * Fetches data of vehicle from Insurance and Registration
	 * @params = vehicle_id
	 * @returns = vehicle data
	 *
	 */
	$("#cbo_car_units").on("change",function(){
		if($(this).val()!=""){
			loadVehicleDetails($(this).val(),$("#txt_trip_ticket_no").val());
		}
	});

	// Event Listeners for validation
	utils.validateChangeInput(elem_jo_no,"Please enter the job order number"); // triggers when  changes happen
	utils.validateChangeSelect(elem_driver_no,"Please enter the travel authorization number");  // triggers when  changes happen 
	utils.validateChangeSelect(elem_vehicle_id,"Please select the vehicle"); // triggers when  changes happen
	utils.validateChangeSelect(elem_nature_of_trip_id,"Please select nature of trip"); // triggers when  changes happen
	utils.validateChangeInput(elem_purpose,"Please enter the purpose of the trip ticket"); // triggers when  changes happen
	utils.validateChangeDateTime(elem_expected_time_return,"Please select the expected time of return"); // triggers when  changes happen
	// utils.validateChangeDateTime(elem_txt_transaction_date,"Please Enter Transaction date"); // triggers when  changes happen
	utils.validateChangeInput(elem_txt_tag_no,"Please Enter Tag number"); // triggers when  changes happen
	utils.validateChangeInput(elem_destination,"Please select the expected time of return"); // checks of input if it is blank and catches 1 if it is not valid
	utils.validateChangeSelect(elem_requestor,"Please select the requestor"); // triggers when  changes happen
	/*
	 *
	 * Saves data to trip_ticket
	 * Runs 
	 *
	 */
	$("#btn_save").click(function(e){
		e.preventDefault();
		errorCtr = 0; // flag used for error checking

		errorCtr += utils.validateTextInput(elem_jo_no,"Please enter the job order number"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_driver_no,"Please select the driver"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_vehicle_id,"Please select the vehicle"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_nature_of_trip_id,"Please select nature of trip"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_purpose,"Please enter the purpose of the trip ticket"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_expected_time_return,"Please select the expected time of return"); // checks of input if it is blank and catches 1 if it is not valid
		// errorCtr += utils.validateTextInput(elem_txt_tag_no,"Please enter tag number"); // triggers when  changes happen
		errorCtr += utils.validateTextInput(elem_destination,"Please select the expected time of return"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_requestor,"Please select the requestor"); // checks of input if it is blank and catches 1 if it is not valid
		
		// if no errors, 0 ctr signifies for valid field inputs
		if(errorCtr == 0) {

			// sets title of modal
			utils.setModalProperty($("#modal-info"),"Update Trip Ticket",".modal-title");
			utils.setModalProperty($("#modal-info"),"Please wait... <img src='../../img/ajax-loader.gif' />",".modal-body");
			$("#modal-info").modal("show");
			$.ajax({
				type:"POST",
				data:{
					trip_ticket_no       : elem_trip_ticket_no.val(),
					jo_no 				 : elem_jo_no.val(),
					driver_no 			 : elem_driver_no.val(),
					vehicle_id 			 : elem_vehicle_id.val(),
					nature_of_trip_id 	 : elem_nature_of_trip_id.val(),
					purpose 			 : elem_purpose.val(),
					destination          : elem_destination.val(),
					requestor            : elem_requestor.val(),
					checklist_id         : $("#txt_checklist_id").val(),
					start_date 			 : start_date,
					end_date			 : end_date,
					expected_time_return : moment(elem_expected_time_return.data("date"),["MM/DD/YYYY h:mm A"]).format("YYYY-MM-DD HH:mm")
				},
				url:"ajax/edit_trip_ticket.php",
				success:function(response){
					isSubmit = true;
					utils.setModalProperty($("#modal-info"),response,".modal-body");
				}/*,
				error: function (jqXHR, textStatus, errorThrown){
		 			alert(errorThrown);
				}*/
			});
		}

	});	

	$('#addDetails').on('click', function() {
		$("#modal-add").modal("show");
	});

	 /*
	  *
	  * Reloads page when modal is closed
	  *
	  */
	$("#modal-info").on("hidden.bs.modal",function(){
		if(isSubmit){
			location.reload();
		}
	});

	$.post( "ajax/load_destinations.php", function( data ) {
		$("#txt_destination").typeahead({
			source: JSON.parse(data)
		});
	});

	$.post( "ajax/load_employees.php", function( data ) {
		$("#txt_passenger_name").typeahead({
			source: JSON.parse(data)
		});
	});

	$("body").on("click",".btn_delete_passenger",function(){
		var id = $(this).data("id");
		var trip_ticket_no = $("#txt_trip_ticket_no").val();
		var element = $(this);

		$.ajax({
			type:"POST",
			data:{
				id : id,
				trip_ticket_no : trip_ticket_no
			},
			url:"ajax/delete_passenger.php",
			success:function(response){

				element.parent().fadeOut("slow");
			}
		});
	});

	$("#btn_add_passenger").click(function(){
		if($("#txt_passenger_name").val()!=""){
			$.ajax({
				type:"POST",
				data:{
					trip_ticket_no : $("#txt_trip_ticket_no").val(),
					passenger_name : $("#txt_passenger_name").val()
				},
				url:"ajax/add_passenger.php",
				success:function(response){
					var new_passenger = "<li class='list-group-item'>";
					new_passenger += "<button type='button' class='btn btn-danger btn-xs btn_delete_passenger' data-id='"+response+"'>";
                    new_passenger += "<i class='fa fa-trash fa-1x'></i>"
                    new_passenger += "</button> ";
                    new_passenger += $("#txt_passenger_name").val();
                    new_passenger += "</li>";
                    $("#passenger_list").append(new_passenger);
                    $("#txt_passenger_name").val("").focus();
				}
			});	
		}
		else {
			$("#txt_passenger_name").focus();
		}

	});


	var dpicker = $('#txt_attendance_date').daterangepicker({
		"showDropdowns": true,
		"showWeekNumbers": true,
		"timePicker": true,
		locale: {
			format: 'MM/DD/YYYY h:mm A'
		},
		"autoApply": true
	});

	// 
	$('#txt_attendance_date').on('apply.daterangepicker', function(ev, picker) {
		start_date = picker.startDate.format('YYYY-MM-DD HH:mm');
		end_date = picker.endDate.format('YYYY-MM-DD HH:mm');
	});

	// hide date first only show if nature of trip is Official Business
	$("#txt_attendance_date").parent().parent().hide();

	if($("#cbo_nature_of_trip").val() == 2){ // if official business
			// show date to enter
			$("#txt_attendance_date").parent().parent().show("slow");
		}
		else {
			// hide the date if not OB
			$("#txt_attendance_date").parent().parent().hide();
	}

	$("#cbo_nature_of_trip").change(function(){
		if($(this).val() == 2){ // if official business
			// show date to enter
			$("#txt_attendance_date").parent().parent().show("slow");
		}
		else {
			// hide the date if not OB
			$("#txt_attendance_date").parent().parent().hide();
		}
	});

	var cleave = new Cleave('.input-element', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
});

	var cleave = new Cleave('.input-element2', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
});





	$('#Save_Changes').click(function() {
		var trip_ticket = $('#txt_trip_ticket_no').val();
		var mdl_plate_no = elem_vehicle_id.val();
		var txt_tag_no = $('#txt_tag_no').val();
		var txt_transaction_date = $('#txt_transaction_date').val();
		var txt_entry_plaza = $('#txt_entry_plaza').val();
		var txt_exit_plaza = $('#txt_exit_plaza').val();
		var txt_amount_with_comma = $('#txt_amount').val();
		var txt_amount = $('#txt_amount').val().replace(/,/g, ''),
		    asANumber = +txt_amount;
		// alert (trip_ticket + ' ' + mdl_plate_no + ' ' +txt_tag_no + ' ' + txt_entry_plaza + ' ' + txt_exit_plaza + ' ' +  txt_amount);
		$.ajax({
				type:"POST",
				data:{
					mdl_plate_no : mdl_plate_no,
					txt_tag_no : txt_tag_no,
					txt_transaction_date : moment(elem_txt_transaction_date.data("date"),["MM/DD/YYYY h:mm A"]).format("YYYY-MM-DD HH:mm"),
					txt_entry_plaza : txt_entry_plaza,
					txt_exit_plaza : txt_exit_plaza,
					txt_amount : txt_amount,
					trip_ticket : trip_ticket
				},
				url:"ajax/add_vehicle_use_rfid.php",
				success:function(response){
					// console.log(response);
					Swal.fire({
						position: 'top-end',
						type: 'success',
						title: 'Your work has been saved',
						showConfirmButton: false,
						timer: 1000
					})
					setTimeout(location.reload.bind(location), 1000);
				}
			});	
	});

	$('#updateChanges').click(function () {
		var tagno = $('#tagno').val();
		var rfid = $('#rfid').html();
		var transactiondate = $('#transactiondate').val();
		var entryplaza = $('#entryplaza').val();
		var exitplaza = $('#exitplaza').val();
		// var amount = $('#amount').val();
		var amount = $('#amount').val().replace(/,/g, ''),
		    asANumber = +amount;

		console.log(tagno + ' ' + rfid + ' ' + entryplaza + ' ' + exitplaza + ' ' + amount + ' ' + transactiondate);
		Swal.fire({
			position: 'top-end',
			title: 'Are you sure?',
			text: "Please check all the information.",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, save it!'
		}).then((result) => {

  		if (result.value) {

			$.ajax({
				type: 'POST',
				data: {
					rfid : rfid,
					tagno : tagno,
					transactiondate : moment(elem_transactiondate.data("date"),["MM/DD/YYYY h:mm A"]).format("YYYY-MM-DD HH:mm"),
					entryplaza : entryplaza,
					exitplaza : exitplaza,
					amount : amount
				},
				url: "ajax/update_vehicle_use_rfid.php",
				success:function(response) {
					// console.log(response);
				Swal.fire({
				position: 'top-end',
				type: 'success',
				title: 'Update Successful.',
				showConfirmButton: false,
				timer: 1500
				})
					setTimeout(location.reload.bind(location), 1500);

				}
			});

			    // Swal.fire(
			    //   'Deleted!',
			    //   'Your file has been deleted.',
			    //   'success'
			    // )
			  }
			})


	});

	$("#delete").click(function() {
		var rfid = $('#rfid').html();
		Swal.fire({
		position: 'top-end',
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
  			if (result.value) {

  			$.ajax({
  				type: 'POST',
  				data: {
  					rfid: rfid
  				},
  				url: "ajax/delete_vehicle_use_rfid.php",
  				success:function(response) {
				    Swal.fire({
				position: 'top-end',
				type: 'success',
				title: 'Deleted!.',
				text: 'Successfully Deleted.',
				showConfirmButton: false,
				timer: 1000
				})
				    setTimeout(location.reload.bind(location), 1000);
  				}

  			});
		  }
		})
	});


	$("body").on("click", ".manage_rfid", function() {
		var tagno = $(this).data('tagno');
		var rfid = $(this).data('id');
		var entryplaza = $(this).data('entryplaza');
		var exitplaza = $(this).data('exitplaza');
		var transdate = $(this).data('transdate');
		var success = $('#sideModalTR');

		$("#rfid").html($(this).data('id'));
		$("#tagno").val($(this).data('tagno'));
		$("#tagno").val($(this).data('tagno'));
		$("#entryplaza").val($(this).data('entryplaza'));
		$("#exitplaza").val($(this).data('exitplaza'));
		$("#amount").val($(this).data('amount'));
		$("#transactiondate").val($(this).data('transdate'));

		
		// alert (tagno + ' ' + rfid + ' ' + transdate);


		$('#sideModalTR').modal('show');

		});


	// $('#i_edit').mouseover(function(){
	// 	$(this).css('cursor', 'pointer');
	// });

	$('.editedit').click(function() {
		var ticketno = $('#ticketno').html();
		$('#fuelratemodal').modal('show');

	});

	$('#updateFuelRates').click(function() {
		var ticketno = $('#ticketno').html();
		var fuelrate = $('#fuelrate').val();
		// alert (ticketno + " " + fuelrate);

		$.ajax({
			type: 'POST',
			data: {
				ticketno : ticketno,
				fuelrate : fuelrate
			},
			url: "ajax/update_fuelrate.php",
			success:function(response) {

				Swal.fire({
				position: 'top-end',
				type: 'success',
				title: 'Update Successful.',
				showConfirmButton: false,
				timer: 1000
				})
				$('#fuelratemodal').modal('hide');
				$('#lbl_fuelrate').html($('#fuelrate').val());
					// setTimeout(location.reload.bind(location), 1500);





			}
		});
	});









});