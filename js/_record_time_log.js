$(document).ready(function(){

	if($("#txt_log_classification_id").val() == 1){
		utils.setPageHeader("page_header","New Company Car Log","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>New Company Car Log</li>");
		$("#rdo_is_attendance_yes").parent().parent().parent().parent().hide();
	}

	else if($("#txt_log_classification_id").val() == 2){
		utils.setPageHeader("page_header","New Car Plan Log","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>New Car Plan Log</li>");
		$("#lnk_trip_ticket_details").hide();
		$("#btn_create_emergency_trip_ticket").hide();
		$("#txt_tt_no").parent().parent().parent().hide();
		$("#txt_km_reading").parent().parent().hide();
		$("#sel_fuel_status").parent().parent().hide();
		$("#txt_epass").parent().parent().hide();
		$("#txt_fleet_card").parent().parent().hide();
	}

	var isSubmit = false;
	var log_count = 0;
	var km_reading_out_company_car = 0;
	var trip_ticket_id;
	var log_type = "";
	$("#btn_generate_tt_data").click(function(){
		trip_ticket_id = $("#txt_tt_no").val();

		if(trip_ticket_id != ""){
			utils.removeInputError($("#txt_tt_no").parent());
			$("#btn_generate_tt_data").button("loading");
			$.ajax({
				type:"POST",
				data:{
					trip_ticket_id : trip_ticket_id
				},
				async: false,
        		cache: false,
				url:"ajax/get_trip_ticket_details.php",
				success:function(response){	
				
					if(response != "error") {

						var data = JSON.parse(response);
						if(data.approval_status == "approved"){ // if approved 
							if(data.trip_ticket_details.status == "Open Trip"){
								if(data.last_log_details!="none"){
									if(data.last_log_details.log_type_id == 1){ // If In
										// check the OUT
										log_type = "out";
										$(".rdo_log_type:nth-child(1)").attr("checked",true);
									}
									else if(data.last_log_details.log_type_id == 2){ // If Out
										// check the IN
										$(".rdo_log_type:nth-child(0)").attr("checked",true);
										log_type = "in";
									}
								}
								else { // if no logs yet
									// check the OUT
									log_type = "out";
									$(".rdo_log_type:nth-child(1)").attr("checked",true);
								}
								// trip ticket details
								$("#txt_jo_no").val(data.trip_ticket_details.jo_no);
								$("#txt_nature_of_trip").val(data.trip_ticket_details.trip_type);
								$("#txt_purpose").val(data.trip_ticket_details.purpose);
								$("#txt_destination").val(data.trip_ticket_details.destination);
								$("#txt_date_requested").val(data.trip_ticket_details.date_requested);
								$("#txt_expected_time_return").val(data.trip_ticket_details.expected_time_of_return);
								$("#txt_requested_by").val(data.trip_ticket_details.requestor_name);
								$("#ol_passengers_list").html(data.trip_ticket_passengers);
								$("#txt_lbl_trip_ticket_no").val(data.trip_ticket_details.id);
							
								// set default passenger if going out
								if(log_type == "out"){
									
									$("#list_of_passengers").html("");
									$("#ol_passengers_list li").each(function(){
										$("#list_of_passengers").append("<li class='list-group-item'>" + $(this).text() + "</li>");
									});
								}

								// driver details
								$("#txt_driver_id").val(data.trip_ticket_details.driver_no);
								$("#txt_driver_name").val(data.driver_name);
					
								// vehicle details
								$("#txt_vehicle_id").val(data.vehicle_details.unit_id);
								$("#txt_cs_no").val(data.vehicle_details.cs_no);
								$("#txt_plate_no").val(data.vehicle_details.plate_no);
								$("#txt_classification").val(data.vehicle_details.classification);
								$("#txt_model").val(data.vehicle_details.model);
								$("#txt_body_color").val(data.vehicle_details.body_color);
								$("#txt_assignee").val(data.vehicle_details.assignee);
								$("#txt_department").val(data.vehicle_details.department);
								$("#txt_section").val(data.vehicle_details.section);
								
								//log_count = data.trip_ticket_details.log_count;
								if(data.trip_ticket_details.log_count == 0){
									// check the out
									$("#txt_tl_vehicle_id,#txt_tl_driver_id,#txt_tl_section,#txt_tl_cs_no,#txt_tl_plate_no,#txt_tl_classification,#txt_tl_model,#txt_tl_body_color,#txt_tl_assignee,#txt_tl_department,#txt_tl_section,#txt_tl_driver_name").val("");
									
								}
								else {
									// check the in
									
									$("#txt_tl_vehicle_id").val(data.logout_details.vehicle_id);
									loadVehicleDetails(data.logout_details.vehicle_id);
									
									$("#txt_tl_driver_id").val(data.logout_details.driver_no);
								
									loadDriverDetails(data.logout_details.driver_no);
									km_reading_out_company_car = data.logout_details.km_reading;
								}
								// set focus on the next field
								$("#txt_tl_vehicle_id").focus();
							}
							else {
						
								if(data.trip_ticket_details.status == "Closed Trip"){
									utils.markInputError($("#txt_tt_no").parent(),"Sorry but Trip ticket No. <strong>"+$("#txt_tt_no").val()+"</strong> is already closed");
								}
								else if(data.trip_ticket_details.status == "Canceled"){
									utils.markInputError($("#txt_tt_no").parent(),"Sorry but Trip ticket No. <strong>"+$("#txt_tt_no").val()+"</strong> is canceled");
								}
								$("form input[type=text]").val("");
								$("#ol_passengers_list").html("");
								$("textarea").val("");
							}
						}
						else {
							utils.markInputError($("#txt_tt_no").parent(),"Trip ticket No. <strong>"+$("#txt_tt_no").val()+"</strong> is not yet approved");
							$("form input[type=text]").val("");
							$("#ol_passengers_list").html("");
							$("textarea").val("");
						}
					}
					else {
						utils.markInputError($("#txt_tt_no").parent(),"Please enter a valid trip ticket");
						$("form input[type=text]").val("");
						$("textarea").val("");
					}
					$("#btn_generate_tt_data").button("reset");
				}	
			});
		}
		else {

			utils.markInputError($("#txt_tt_no").parent(),"Please enter a trip ticket number");

		}
	});

	
	function loadDriverDetails(barcode){

		$("#txt_tl_driver_id,#txt_tl_driver_name").val("");
		$("#txt_tl_driver_id").val(barcode);
		
		$.ajax({
			type:"POST",
			data:{
				driver_no : $("#txt_tl_driver_id").val()
			},
			url:"ajax/get_driver_data.php",
			success:function(response){
					
				if(response == "error"){
					utils.markInputError($("#txt_tl_driver_id"),"Please scan a valid Driver's ID");
					$("#txt_tl_driver_id").focus();
				}
				else {
					utils.removeInputError($("#txt_tl_driver_id"));
					$("#txt_tl_driver_name").val(response);
					$(".rdo_log_type:first").focus();
				}

			
			}
		});
	}

	function loadVehicleDetails(barcode){
		$("#txt_tl_vehicle_id,#txt_tl_cs_no,#txt_tl_plate_no,#txt_tl_classification,#txt_tl_model,#txt_tl_body_color,#txt_tl_assignee,#txt_tl_department,#txt_tl_section").val("");
		$("#txt_tl_vehicle_id").val(barcode);
		$.ajax({
			type:"POST",
			data:{
				vehicle_id : $("#txt_tl_vehicle_id").val()
			},
			url:"ajax/get_vehicle_details_barcoded.php",
			success:function(response){
				
				if(response!="error"){
					
					utils.removeInputError($("#txt_tl_vehicle_id"));
					var data = JSON.parse(response);
					$("#txt_tl_cs_no").val(data.vehicle.cs_no);
					$("#txt_tl_plate_no").val(data.vehicle.plate_no);
					$("#txt_tl_classification").val(data.vehicle.classification);
					$("#txt_tl_model").val(data.vehicle.model);
					$("#txt_tl_body_color").val(data.vehicle.body_color);
					$("#txt_tl_assignee").val(data.vehicle.assignee);
					$("#txt_tl_department").val(data.vehicle.department);
					$("#txt_tl_section").val(data.vehicle.section);
					$("#txt_tl_driver_id").focus();
					
					$("#txt_lbl_vehicle_id").val($("#txt_tl_vehicle_id").val());

					$("#txt_lbl_cs_no").val(data.vehicle.cs_no);
					$("#txt_lbl_plate_no").val(data.vehicle.plate_no);
					
					if(data.vehicle.employee_no!=null){
						$("#txt_tl_driver_id").val(data.vehicle.employee_no);
						$("#txt_tl_driver_id").blur();
						//$(".rdo_log_type:nth-child(0)").focus();
					}
					
					// if car plan set the IN and OUT based on the last transaction
					if($("#txt_log_classification_id").val() == 2){

						if(data.log_details.log_type_id == 1){ // If last transaction is IN then
							$(".rdo_log_type:nth-child(1)").attr("checked",true); // set the default to OUT
						}
						else if(data.log_details.log_type_id == 2){ // If last transaction is OUT then
							$(".rdo_log_type:nth-child(0)").attr("checked",true); // set the default IN
						}
					}
				}
				else {
					$("#txt_tl_vehicle_id").focus();
					utils.markInputError($("#txt_tl_vehicle_id"),"Please scan a valid Vehicle ID");
				}
			}
		});
	}

	$("#txt_tl_driver_id").scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){
			loadDriverDetails(barcode);
		} // main callback function	
	});

	$("#txt_tl_driver_id").on("blur",function(){
		loadDriverDetails($(this).val());
	});

	$("#txt_tl_vehicle_id").scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){
			loadVehicleDetails(barcode);
		} // main callback function	
	});

	$("#txt_tl_vehicle_id").on("blur",function(){
		loadVehicleDetails($(this).val());
	});

	/*
	sel_epass
sel_fleet_card*/

	$("#txt_passenger").scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){	
			$("#txt_passenger").val(barcode);
				$.ajax({
					type:"POST",
					data:{
						employee_no : barcode
					},
					url:"ajax/get_employee_data.php",
					success:function(response){
						if(response!="error") {
							var ctr = 0;
							$("#list_of_passengers li").each(function(){
								if($(this).data("barcode") == barcode){
									ctr++;
								}
							});

							if(ctr == 0){
								utils.removeInputError($("#txt_passenger").parent());
								$("#list_of_passengers").append("<li class='list-group-item' data-barcode='"+barcode+"'>" + response + "</li>");
								$("#txt_passenger").val("");
							}
							else {
								utils.markInputError($("#txt_passenger").parent(),response + " is already on the list");
								$("#txt_passenger").val("");
								$("#txt_passenger").focus();
							}
						
						}
						else {
							$("#txt_passenger").val("");
							utils.markInputError($("#txt_passenger").parent(),"Please scan a valid Employee No");
						}
					}	
				});
			}
			
			
	});

	$("#btn_add_passenger").click(function(){
		if($("#txt_passenger").val()!=""){
			$.ajax({
				type:"POST",
				data:{
					passenger : $("#txt_passenger").val()
				},
				url:"ajax/search_employee_name.php",
				success:function(response){
					if(response!="error"){
						$("#list_of_passengers").append("<li class='list-group-item'>" + response + "</li>");
					}
					else {
						$("#list_of_passengers").append("<li class='list-group-item'>" + $("#txt_passenger").val() + "</li>");
					}

					$("#txt_passenger").val("");
					$("#txt_passenger").focus();
				}
			});
			
			
		}
		else {
			$("#txt_passenger").focus();
		}
		
	});


	utils.validateChangeInput($("#txt_km_reading"),"Please enter the vehicle's kilometer reading");
	utils.validateChangeSelect($("#sel_fuel_status"),"Please scan the vehicle's fuel status")

	$("#btn_save").click(function(){
		var trip_ticket_no = $("#txt_tt_no");
		var driver_no = $("#txt_tl_driver_id");
		var vehicle_id = $("#txt_tl_vehicle_id");
		var driver_name = $("#txt_tl_driver_name");
		var km_reading = $("#txt_km_reading");
		var fuel_status_id = $("#sel_fuel_status");
		var log_classification_id = $("#txt_log_classification_id").val();
		var log_type = 	1;
		var passenger_list = [];
		var index = 0;
		var errorCtr = 0;
		var epass_no = $("#txt_epass").val();
		var fleet_card_no = $("#txt_fleet_card").val();
		var remarks = $("#txt_remarks").val();
		var record_as_attendance = "";


		if($("#rdo_is_attendance_yes").is(":checked")){
			record_as_attendance = $("#rdo_is_attendance_yes").val();
		}
		else if($("#rdo_is_attendance_no").is(":checked")){
			record_as_attendance = $("#rdo_is_attendance_no").val();
		}

		$(".rdo_log_type").each(function(){
			if($(this).is(":checked")){
				log_type = $(this).val();
			}
		});

		//errorCtr += utils.validateTextInput(driver_no,"Please scan the driver's ID");

		if(log_classification_id == 1){ // only check this inputs if the log is for company car
			if(trip_ticket_no.val() == ""){	
				utils.markInputError(trip_ticket_no.parent(),"Please enter a trip ticket number");
			}
			errorCtr += utils.validateTextInput(vehicle_id,"Please scan the vehicle's ID");
			errorCtr += utils.validateTextInput(km_reading,"Please enter the vehicle's kilometer reading");
			errorCtr += utils.validateTextInput(fuel_status_id,"Please scan the vehicle's fuel status");
			
			if(log_count >= 1){ // check KM reading if company car is going INSIDE IPC
				//if(km_reading.val()!=""){
				if(km_reading.val() <= km_reading_out_company_car){
					utils.markInputError(km_reading,"Incoming KM reading should not be <strong>LESS</strong> or the <strong>SAME</strong> than the Outgoing KM Reading, KM out is <strong>" + km_reading_out_company_car +"</strong>");
					errorCtr++;
				}  
				else {
					utils.removeInputError(km_reading);
				}
				//}
			}
		}
		
		if(errorCtr == 0){

			// sets title of modal
			utils.setModalProperty($("#modal-info"),"Time Log",".modal-title");
			utils.setModalProperty($("#modal-info"),"Please wait... <img src='../../img/ajax-loader.gif' />",".modal-body");
			$("#modal-info").modal("show");

			$("#list_of_passengers li").each(function(){
				passenger_list[index] = $(this).text();
				index++;
			});
			
			$.ajax({
				type:"POST",
				data:{
					log_classification_id : log_classification_id,
					trip_ticket_no 		  : trip_ticket_no.val(),
					log_type	   	 	  : log_type,
					driver_no      	      : driver_no.val(),
					driver_name 		  : driver_name.val(),
					vehicle_id     		  : vehicle_id.val(),
					km_reading     		  : km_reading.val(),
					fuel_status_id 		  : fuel_status_id.val(),
					passenger_list 		  : passenger_list,
					e_pass_no             : epass_no,
					fleet_card_no		  : fleet_card_no,
					remarks				  : remarks,
					record_as_attendance  : record_as_attendance
				},
				url:"ajax/add_time_log.php",
				success:function(response){
					isSubmit = true;
					utils.setModalProperty($("#modal-info"),response,".modal-body");
					utils.trackModalClose($("#modal-info"),isSubmit,location);
				}
			});

		}
	});
	
	$("#sel_fuel_status").change(function(){
		$("#txt_epass").focus();
	});

	$("#txt_epass").scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){	
			$("#txt_fleet_card").focus();	
		}			
	});
	
	$("#txt_fleet_card").scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){	
			$("#txt_remarks").focus();
		}			
	});

	$("body").on("dblclick","#list_of_passengers li",function(){
		$(this).remove();
	});

	$("#btn_create_emergency_trip_ticket").click(function(){
		// sets title of modal

		utils.setModalProperty($("#modal-info"),"Emergency Trip Ticket",".modal-title");
		utils.setModalProperty($("#modal-info"),"Please wait... <img src='../../img/ajax-loader.gif' />",".modal-body");
		$("#modal-info").modal("show");
		$.ajax({
			type:"POST",
			data:{},
			url:"ajax/create_emergency_trip_ticket.php",
			success:function(response){
				isSubmit = true;
				utils.setModalProperty($("#modal-info"),response,".modal-body");
				utils.trackModalClose($("#modal-info"),isSubmit,location);
			}
		});
	});
});