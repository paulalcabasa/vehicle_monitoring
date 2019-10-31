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
			//if(response == "error"){
			//	utils.markInputError($("#txt_tl_driver_id"),"Please scan a valid Driver's ID");
//				$("#txt_tl_driver_id").focus();
			//}
			//else {
		//		utils.removeInputError($("#txt_tl_driver_id"));
				$("#txt_tl_driver_name").val(response);
				$(".rdo_log_type:first").focus();
		//	}
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

$(document).ready(function(){

	$("#txt_tl_vehicle_id").focus();
	utils.setPageHeader("page_header","New Errand Log","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>New Errand Log</li>");
	
	var isSubmit = false;
	var log_count = 0;
	var km_reading_out_company_car = 0;
	var trip_ticket_id;
	var log_type = "";

	loadVehicleDetails($("#txt_tl_vehicle_id").val());


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
					var passenger_name = "";
					if(response!="error"){
						passenger_name = response;
						$("#list_of_passengers").append("<li class='list-group-item'>" + response + "</li>");
						
					}
					else {
						passenger_name = $("#txt_passenger").val();
						$("#list_of_passengers").append("<li class='list-group-item'>" + $("#txt_passenger").val() + "</li>");
					}

					$.ajax({
						type:"POST",
						data:{
							log_id : $("#txt_log_id").val(),
							passenger_name : passenger_name
						},
						url:"ajax/add_errand_passenger.php",
						success:function(response){
							alert(response);
						}
					});

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
		var vehicle_id = $("#txt_tl_vehicle_id");
		var driver_no = $("#txt_tl_driver_id");		
		var driver_name = $("#txt_tl_driver_name");
		var log_type = 	1;
		var km_reading = $("#txt_km_reading");
		var fuel_status_id = $("#sel_fuel_status");
		var remarks = $("#txt_remarks").val();
		var passenger_list = [];
		var log_classification_id = 3;
		var index = 0;
		var errorCtr = 0;
		
		$(".rdo_log_type").each(function(){
			if($(this).is(":checked")){
				log_type = $(this).val();
			}
		});

		$("#list_of_passengers li").each(function(){
			passenger_list[index] = $(this).text();
			index++;
		});

		errorCtr += utils.validateTextInput(vehicle_id,"Please scan the vehicle's ID");
		errorCtr += utils.validateTextInput(km_reading,"Please enter the vehicle's kilometer reading");
		errorCtr += utils.validateTextInput(fuel_status_id,"Please scan the vehicle's fuel status");
		
		if(log_count >= 1){ // check KM reading if company car is going INSIDE IPC
			if(km_reading.val() <= km_reading_out_company_car){
				utils.markInputError(km_reading,"Incoming KM reading should not be <strong>LESS</strong> or the <strong>SAME</strong> than the Outgoing KM Reading, KM out is <strong>" + km_reading_out_company_car +"</strong>");
				errorCtr++;
			}  
			else {
				utils.removeInputError(km_reading);
			}
		}
	                                                                                       
		if(errorCtr == 0){

			// sets title of modal
			utils.setModalProperty($("#modal-info"),"Time Log",".modal-title");
			utils.setModalProperty($("#modal-info"),"Please wait... <img src='../../img/ajax-loader.gif' />",".modal-body");
			$("#modal-info").modal("show");

			$.ajax({
				type:"POST",
				data:{
					log_id 				  : $("#txt_log_id").val(),
					log_classification_id : log_classification_id,
					log_type	   	 	  : log_type,
					driver_no      	      : driver_no.val(),
					driver_name 		  : driver_name.val(),
					vehicle_id     		  : vehicle_id.val(),
					km_reading     		  : km_reading.val(),
					fuel_status_id 		  : fuel_status_id.val(),
					remarks				  : remarks
				},
				url:"ajax/update_errand_log.php",
				success:function(response){
					//alert(response)
					location.reload();
					//isSubmit = true;
					//utils.setModalProperty($("#modal-info"),response,".modal-body");
				//	utils.trackModalClose($("#modal-info"),isSubmit,location);
				}
			});

		}
	});
	
	$("#sel_fuel_status").change(function(){
		$("#txt_purpose").focus();
	});


	$("body").on("dblclick","#list_of_passengers li",function(){
		var elem = $(this);
		$.ajax({
			type:"POST",
			data:{
				passenger_id : elem.data('id')
			},
			url:"ajax/remove_errand_passenger.php",
			success:function(response){
				alert(response);
				elem.remove();
			}
		});
		
	});

	$.post( "ajax/load_employees.php", function( data ) {
		$("#txt_passenger").typeahead({
			source: JSON.parse(data)
		});
	});

	$("#txt_passenger").focus();
});

