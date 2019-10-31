$(document).ready(function(){
utils.setPageHeader("page_header","Report","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Managers Attendance</li>");
	$("#sel_employee_id").select2();
	var dpicker = $('#txt_attendance_date').daterangepicker({
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

/*	$('#txt_attendance_date').on('apply.daterangepicker', function(ev, picker) {
		var start_date = picker.startDate.format('YYYY-MM-DD') + " 00:00:00";
		var end_date = picker.endDate.format('YYYY-MM-DD') + " 23:59:59";
		
	});*/

	$("#txt_attendance_date").val("");

	$("#btn_generate").click(function(ev){
		var start_date = dpicker.data("daterangepicker").startDate.format('YYYY-MM-DD');
		var end_date = dpicker.data("daterangepicker").endDate.format('YYYY-MM-DD');
		$("#txt_start_date").val(start_date);
		$("#txt_end_date").val(end_date);
		var employee_id = $("#sel_employee_id").val();
		if($("#txt_attendance_date").val()!="" && $("#sel_employee_id").val()!=""){
			$("#tbl_attendance tbody").html("<tr><td colspan='3' align='center'>Please wait... <img src='../../img/ajax-loader.gif'/></td></tr>");
			$("#btn_print").attr("disabled","disabled");
			$.ajax({
				type:"POST",
				data:{
					start_date : start_date,
					end_date   : end_date,
					employee_id : employee_id
				},
				url:"ajax/get_managers_attendance.php",
				success:function(response){

					if(response!=""){
						$("#tbl_attendance tbody").html(response);
						$("#btn_print").removeAttr("disabled");
					}
					else {
						$("#tbl_attendance tbody").html("<tr><td colspan='3' align='center'>No records found.</td></tr>");
						$("#btn_print").attr("disabled","disabled");
					}
				}
			});
		}
	});
});