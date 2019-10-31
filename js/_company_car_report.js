$(document).ready(function(){

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
		var start_date = picker.startDate.format('YYYY-MM-DD');
		var end_date = picker.endDate.format('YYYY-MM-DD');
		
	});
});