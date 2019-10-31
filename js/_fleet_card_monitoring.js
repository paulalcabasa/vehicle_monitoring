$(document).ready(function(){
	utils.setPageHeader("page_header","Monitoring","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Fleet Card Monitoring</li>");
	var start_date = "";
	var end_date = "";
	var table = $("#tbl_fleetcard_monitoring").DataTable();

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
		
		if(start_date != "" && end_date != ""){
			$("#tbl_epass_monitoring tbody").html("<tr><td colspan='7' align='center'>Please wait <img src='../../img/ajax-loader.gif'/></td></tr>");
			$.ajax({
				type:"POST",
				data:{
					start_date : start_date,
					end_date   : end_date
				},
				url:"ajax/get_fleetcard_monitoring_report.php",
				success:function(response){
					if(response != ""){
						table.destroy();
						$("#tbl_fleetcard_monitoring tbody").html(response);
						table = $("#tbl_fleetcard_monitoring").DataTable();
						table.draw();
						$("#btn_export_pdf").removeAttr("disabled");
					}
					else {
						$("#btn_export_pdf").attr("disabled","disabled");
						$("#tbl_fleetcard_monitoring tbody").html("<tr><td colspan='13' align='center'>No records found.</td></tr>");
					}
				}
			});
		}
		else {
			$("#txt_report_date").focus();
		}
	});

	$("#txt_report_date").val('');





});