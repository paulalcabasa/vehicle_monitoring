$(document).ready(function(){

	utils.setPageHeader("page_header","Report","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Trip Ticket Report</li>");


	var start_date,end_date,search_by;
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

	$("#txt_report_date").val("");

	$('#txt_report_date').on('apply.daterangepicker', function(ev, picker) {
		start_date = picker.startDate.format('YYYY-MM-DD') + " 00:00:00";
		end_date = picker.endDate.format('YYYY-MM-DD') + " 23:59:59";
	});

/*	$(".btn_search").click(function(){
		search_by = $(this).data("search");	
		var total = $("#lbl_total");
		if($("#txt_report_date").val()!=""){
			$("#txt_search_by").val(search_by);
		
			$("#tbl_data tbody").html("<tr><td colspan='12' align='center'>Please wait... <img src='../../img/ajax-loader.gif'/></td></tr>");
			$.ajax({
				type:"POST",
				data:{
					start_date : start_date,
					end_date   : end_date,
					search_by  : search_by
				},
				url:"ajax/get_trip_ticket_report.php",
				success:function(response){
					$("#tbl_data tbody").html(response);
					total.html('90');
				}
			});
		}
		else {
			$("#txt_report_date").focus();
		}
	});
*/


	// btn_pdf btn_excel
	$("#btn_pdf").click(function(){
		alert(search_by + " " + start_date + " " + end_date);
	});

	$("#btn_excel").click(function(){
		var search_by = $("#sel_report_type").val();
		var vehicle_id = $("#sel_vehicle").val();
		$.blockUI({ 
            message: '<h1>Downloading...</h1>' 
        });
        $.ajax({
        	type:"POST",
        	data:{
				start_date : start_date,
				end_date   : end_date,
				search_by  : search_by,
				vehicle_id : vehicle_id
			},
        	url:"ajax/ttr_export_excel.php",
        	success:function(response){
        		//alert(response);
        		window.location.href = "reports/excel_exports/" + response;
        		$.unblockUI({ fadeOut: 1500 }); 
        	}
        });
		

	});

	$("#sel_vehicle").select2();

	$("#btn_search").click(function(){
		var search_by = $("#sel_report_type").val();
		var vehicle_id = $("#sel_vehicle").val();
		//alert(search_by + " " + cs_no + " " + start_date + " " + end_date);
		//return false;
		if($("#txt_report_date").val()!=""){
			$("#txt_search_by").val(search_by);
		
			$("#tbl_data tbody").html("<tr><td colspan='6' align='center'>Please wait... <img src='../../img/ajax-loader.gif'/></td></tr>");
			$.ajax({
				type:"POST",
				data:{
					start_date : start_date,
					end_date   : end_date,
					search_by  : search_by,
					vehicle_id : vehicle_id
				},
				url:"ajax/get_trip_ticket_report.php",
				success:function(response){
					$("#tbl_data tbody").html(response);
					$("#lbl_total").text($("#tbl_data tbody tr").length);
				}
			});
		}
		else {
			$("#txt_report_date").focus();
		}
	});

});