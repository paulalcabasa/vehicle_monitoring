$(document).ready(function(){
	utils.setPageHeader("page_header","Vehicle Usage","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Car Plan Vehicle Usage</li>");
	var start_date = "";
	var end_date = "";
/*
	$("#tbl_report_data").DataTable({
		  colReorder: true,
        stateSave:  true
	});*/

	$('#txt_report_date').daterangepicker({
	    "showDropdowns": true,
	    "showWeekNumbers": true,
	      ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
	});

	$('#txt_report_date').on('apply.daterangepicker', function(ev, picker) {
		start_date = picker.startDate.format('YYYY-MM-DD') + " 00:00:00";
		end_date = picker.endDate.format('YYYY-MM-DD') + " 23:59:59";
	});

	$("#btn_generate").click(function(){
		var vehicle_id = $("#sel_vehicle_units").val();
		var driver_no = $("#sel_drivers").val();
		if( (start_date != "" && end_date != "") || vehicle_id != "" || driver_no != ""){
			$.ajax({
				type:"POST",
				data:{
					start_date : start_date,
					end_date   : end_date,
					vehicle_id : vehicle_id,
					driver_no  : driver_no
				},
				url:"ajax/get_carplan_vehicle_usage.php",
				success:function(response){
					if(response != ""){
						$("#tbl_report_data tbody").html(response);
						$("#btn_export_pdf,#btn_export_excel").removeAttr("disabled");
					}
					else {
						$("#tbl_report_data tbody").html("<tr><td colspan='13' align='center'>No records found.</td></tr>");
					}
					
				}
			});
		}
		else {
			$("#txt_report_date").focus();
		}
	});

	$("#txt_report_date").val('');

	function populatePassengers(element){
		var passenger_list = (element.data("passengers")!="" ? element.data("passengers").split(';') : "");
		var passengers = (passenger_list.length > 0) ? "<ol>" : "";
		for(var i = 0; i < passenger_list.length; i++){
			passengers += "<li>" + passenger_list[i] + "</li>";
		}
		if(passenger_list.length > 0){
			passengers += "</ol>";
		}
		else {
			passengers = "No passenger.";
		}
		utils.setModalProperty($("#modal-info"),"Passengers",".modal-title");
		utils.setModalProperty($("#modal-info"),passengers,".modal-body");
		$("#modal-info").modal("show");
	}

	$("body").on("click",".btn_view_passengers_in",function(){
		populatePassengers($(this));
	});

	$("body").on("click",".btn_view_passengers_out",function(){
		populatePassengers($(this));
	});
	
	$("#btn_export_excel").click(function(){
		$.blockUI({ 
            message: '<h1>Downloading...</h1>' 
        });
        $.ajax({
        	type:"POST",
        	data:{
        		start_date : start_date,
        		end_date   : end_date
        	},
        	url:"ajax/cpvu_export_excel.php",
        	success:function(response){
        		window.location.href = "reports/excel_exports/" + response;
        		$.unblockUI({ fadeOut: 1500 }); 
        	}
        });
		
	});

	$("#btn_export_pdf").click(function(){
		$("#frm_print").submit();
	});

	$("#sel_vehicle_units").select2();
	$("#sel_drivers").select2();

});