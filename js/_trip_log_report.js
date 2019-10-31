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

		$("#start_date").val(start_date);
		$("#end_date").val(end_date);
	});

	$("#sel_vehicle").select2();

});