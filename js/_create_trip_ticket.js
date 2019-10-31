/*
 *
 * Author: 		 John Paul M. Alcabasa
 * System : 	 Vehicle Monitoring
 * Date Created: December 4, 2015
 * Description:  Processes user input in creating trip ticket 
 * Version: 	 1.0 
 *
 */

$(document).ready(function(){

	/* sets header of the page */
	utils.setPageHeader("page_header","New Trip Ticket","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>New Trip Ticket</li>");

	/*
	 *
	 * "elem_" variable name initial signifies that the variable is an "ELEMENT"
	 *
	 */

	var elem_jo_no = $("#txt_jo_no");
	var elem_driver_no = $("#cbo_drivers");
	var elem_vehicle_id = $("#cbo_car_units");
	var elem_nature_of_trip_id = $("#cbo_nature_of_trip");
	var elem_purpose = $("#txt_purpose");
	var elem_expected_time_return = $("#txt_expected_time_return");
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
		$("#lbl_driver_id").text(employee_no);
		$("#lbl_driver_name").text(driver_name);
		/*$.ajax({
			type:"POST",
			data:{
				employee_no : employee_no
			},
			url:"ajax/get_driver_details.php",
			success:function(response){
				$("#lbl_vehicle_id,#lbl_cs_no,#lbl_plate_no,#lbl_classification,#lbl_model,#lbl_body_color,#lbl_assignee,#lbl_department,#lbl_section").text("");
				$("#cbo_car_units").select2("val","");
				if(response != "false"){
					var data = JSON.parse(response);
					// label for details of vehicle
					$("#lbl_vehicle_id").text(data.unit_id);
					$("#lbl_cs_no").text(data.cs_no);
					$("#lbl_plate_no").text(data.plate_no);
					$("#lbl_classification").text(data.classification);
					$("#lbl_model").text(data.model);
					$("#lbl_body_color").text(data.body_color);
					// label for details of assignee
					$("#lbl_assignee").text(data.assignee);
					$("#lbl_department").text(data.department);
					$("#lbl_section").text(data.section);

					$("#cbo_car_units").select2("val",data.orig_id);
				}
			}
		});*/
	});
	
	/*
	 *
	 * Fetches data of vehicle from Insurance and Registration
	 * @params = vehicle_id
	 * @returns = vehicle data
	 *
	 */

	$("#tbl_open_trip_list").hide();
	$("#cbo_car_units").on("change",function(){
		var is_reserved = ($("#cb_with_vehicle_reservation").is(":checked") ? "yes" : "no");
		var date = $("#txt_expected_time_return").data("date");
		var dt = moment(date, "MM/DD/YYYY HH:mm A").format('YYYY-MM-DD HH:mm');
		var attachments_list = [];
		var attachments = $("#cbo_car_units option:selected").data("attachments");
		var attachment_display = "";
		$("#lbl_checklist_id").text($("#cbo_car_units option:selected").data("fid"));
		$("#txt_checklist_id").val($("#cbo_car_units option:selected").data("id"));
		$("#lbl_checklist_condition").text($("#cbo_car_units option:selected").data("cond"));

		if(attachments!=undefined){
			attachments_list = attachments.split(";");
			for (i = 0; i < attachments_list.length; i++) {
				
				attachment_display += "<a href='attachments/"+attachments_list[i]+"' target='_blank'><i class='fa fa-paperclip'></i> "+attachments_list[i]+"</a><br/>";
			}
		
			if(attachments != ""){
				$("#lbl_checklist_attachment").html(attachment_display);
			}
		}
		$("#lbl_checklist_date_created").text($("#cbo_car_units option:selected").data("date_created"));

		$("#tbl_open_trip_list").hide();
		$("#tbl_open_trip_list tbody").html("");
		if($(this).val()!="" && date!==undefined){
			$.ajax({
				type:"POST",
				data:{
					vehicle_id : $(this).val(),
					is_reserved : is_reserved,
					etr : dt
				},
				url:"ajax/get_vehicle_details.php",
				success:function(response){

					if(is_reserved == "yes"){
						var data = JSON.parse(response);
						// label for details of vehicle
						$("#lbl_vehicle_id").text(data.vehicle_data.unit_id);
						$("#lbl_cs_no").text(data.vehicle_data.cs_no);
						$("#lbl_plate_no").text(data.vehicle_data.plate_no);
						$("#lbl_classification").text(data.vehicle_data.classification);
						$("#lbl_model").text(data.vehicle_data.model);
						$("#lbl_body_color").text(data.vehicle_data.body_color);
						// label for details of assignee
						$("#lbl_assignee").text(data.vehicle_data.assignee);
						$("#lbl_department").text(data.vehicle_data.department);
						$("#lbl_section").text(data.vehicle_data.section);

						$("#tbl_open_trip_list tbody").html(data.open_trips);
						$("#tbl_open_trip_list").show();

					}
					else if(is_reserved == "no"){

						var data = JSON.parse(response);
						// label for details of vehicle
						$("#lbl_vehicle_id").text(data.unit_id);
						$("#lbl_cs_no").text(data.cs_no);
						$("#lbl_plate_no").text(data.plate_no);
						$("#lbl_classification").text(data.classification);
						$("#lbl_model").text(data.model);
						$("#lbl_body_color").text(data.body_color);
						// label for details of assignee
						$("#lbl_assignee").text(data.assignee);
						$("#lbl_department").text(data.department);
						$("#lbl_section").text(data.section);
					}
				}
			});
		}
	});

	// Event Listeners for validation
	utils.validateChangeInput(elem_jo_no,"Please enter the job order number"); // triggers when  changes happen
	utils.validateChangeSelect(elem_driver_no,"Please enter the travel authorization number");  // triggers when  changes happen 
	utils.validateChangeSelect(elem_vehicle_id,"Please select the vehicle"); // triggers when  changes happen
	utils.validateChangeSelect(elem_nature_of_trip_id,"Please select nature of trip"); // triggers when  changes happen
	utils.validateChangeInput(elem_purpose,"Please enter the purpose of the trip ticket"); // triggers when  changes happen
	utils.validateChangeDateTime(elem_expected_time_return,"Please select the expected time of return"); // triggers when  changes happen
	utils.validateChangeInput(elem_destination,"Please select the expected time of return"); // checks of input if it is blank and catches 1 if it is not valid
	utils.validateChangeSelect(elem_requestor,"Please select a requestor"); // triggers when  changes happen
	/*
	 *
	 * Saves data to trip_ticket
	 * Runs 
	 *
	 */
	$("#btn_save").click(function(e){
		e.preventDefault();
		errorCtr = 0; // flag used for error checking
		var passenger_list = [];
		var index = 0;
		errorCtr += utils.validateTextInput(elem_jo_no,"Please enter the job order number"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_driver_no,"Please select the driver"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_vehicle_id,"Please select the vehicle"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_nature_of_trip_id,"Please select nature of trip"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_purpose,"Please enter the purpose of the trip ticket"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_expected_time_return,"Please select the expected time of return"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_destination,"Please select the destination"); // checks of input if it is blank and catches 1 if it is not valid
		errorCtr += utils.validateTextInput(elem_requestor,"Please select a requestor"); // checks of input if it is blank and catches 1 if it is not valid
		// if no errors, 0 ctr signifies for valid field inputs
		if(elem_nature_of_trip_id == 2){
			if(start_date == "" || end_date == ""){
				utils.markInputError($("#txt_attendance_date"),"Please select the date then click apply");
				errorCtr++;
			}
		}	

		if(errorCtr == 0) {
			$("#passenger_list li").each(function(){
				passenger_list[index] = $(this).text();
				index++;
			});
			
			utils.setModalProperty($("#modal-info"),"Trip Ticket Creation",".modal-title");
			utils.setModalProperty($("#modal-info"),"Please wait... <img src='../../img/ajax-loader.gif' />",".modal-body");
			$("#modal-info").modal("show");
			utils.removeInputError($("#txt_attendance_date"));
			$.ajax({
				type:"POST",
				data:{
					jo_no 				 : elem_jo_no.val(),
					driver_no 			 : elem_driver_no.val(),
					vehicle_id 			 : elem_vehicle_id.val(),
					nature_of_trip_id 	 : elem_nature_of_trip_id.val(),
					purpose 			 : elem_purpose.val(),
					destination          : elem_destination.val(),
					expected_time_return : moment(elem_expected_time_return.data("date"),["MM/DD/YYYY h:mm A"]).format("YYYY-MM-DD HH:mm"),
					requestor            : elem_requestor.val(),
					passenger_list		 : passenger_list,
					start_date			 : start_date,
					end_date			 : end_date,
					checklist_id 		 : $("#txt_checklist_id").val()
				},
				url:"ajax/add_trip_ticket.php",
				success:function(response){
					isSubmit = true;
					utils.setModalProperty($("#modal-info"),response,".modal-body");
				}
			});
		}

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

	$("#txt_destination").on("change",function(){
		if($("#txt_destination").val()!=""){
			$.ajax({
				type:"POST",
				data:{
					destination : $("#txt_destination").val()
				},
				url:"ajax/search_destination.php",
				success:function(response){

					if(response!="none"){
						var data = JSON.parse(response);
						$("#lbl_destination").text($("#txt_destination").val());
						$("#lbl_zip_code").text(data.zip_code);
						$("#lbl_dest_km").text(data.distance);
						$("#lbl_dest_toll").text(data.toll_fee);
					}
				}
			});
		}
	});

	/*btn_add_passenger
	passenger_list*/
	$("#btn_add_passenger").click(function(){
		if($("#txt_passenger_name").val()!=""){
			$("#passenger_list").append("<li class='list-group-item'>" + $("#txt_passenger_name").val() + "</li>");
			$("#txt_passenger_name").val("").focus();
		}
		else {
			$("#txt_passenger_name").focus();
		}

	});

	$("body").on("dblclick","#passenger_list li",function(){
		$(this).remove();
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

	$("#txt_expected_time_return").on("dp.change",function(){
		var date = $(this).data("date");
		var dt = moment(date, "MM/DD/YYYY HH:mm A").format('YYYY-MM-DD HH:mm');
		var is_reserved = ($("#cb_with_vehicle_reservation").is(":checked") ? "yes" : "no");
		loadVehicles(dt,is_reserved);
	});

	$("#cb_with_vehicle_reservation").on("change",function(){
		var date = $("#txt_expected_time_return").data("date");
		var dt = moment(date, "MM/DD/YYYY HH:mm A").format('YYYY-MM-DD HH:mm');
		var is_reserved = ($("#cb_with_vehicle_reservation").is(":checked") ? "yes" : "no");
		
		if(date!==undefined){
			loadVehicles(dt,is_reserved)
		}

		if($(this).is(":checked")){
			$("#tbl_open_trip_list").show();
		}
		else {
			$("#tbl_open_trip_list").hide();
		}
	});

	function loadVehicles(dt,is_reserved){
		$("#cbo_car_units").select2("val","");	
		$.ajax({
			type:"POST",
			data:{
				etr 		: dt,
				is_reserved : is_reserved
			},
			url:"ajax/load_vehicles.php",
			success:function(response){
				$("#cbo_car_units").html(response);
			}
		});
	}
});