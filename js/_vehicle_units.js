$(document).ready(function(){
	$("#tbl_vehicle_units").DataTable({
		dom: 'Bfrtip',
		buttons: [
			'copy', 'excel'/*,
			{
                text: 'PDF',
                action: function ( e, dt, node, config ) {
                    window.open('pages/print_vehicle_status_report.php');
                }
            }*/
		]
	});


	$("#cb_avail").click(function(){
		if($(this).is(":checked")){
			$("#cb_in,#cb_out").prop('checked',false);
		}

		$("form").submit();
	});

	$("#cb_out,#cb_in").click(function(){
		if($(this).is(":checked")){
			$("#cb_avail").prop('checked',false);
		}
		$("form").submit();
	});

	$("#sel_classification").select2();
	$("#sel_classification").on("change",function(){
		$("form").submit();
	});

	$("body").on("mouseover",".btn_view_trip_ticket_details",function(){
		var trip_ticket_no = $(this).data('trip_ticket_no');
		utils.setModalProperty($("#modal-info"),"Trip Ticket Details",".modal-title");
		utils.setModalProperty($("#modal-info"),"Please wait... <img src='images/ajax-loader.gif' /> ",".modal-body");
		$("#modal-info").modal('show');
		$.ajax({
			type:"POST",
			data:{
				trip_ticket_id : trip_ticket_no
			},
			url:"ajax/get_trip_ticket_details_modal.php",
			success:function(response){
				utils.setModalProperty($("#modal-info"),response,".modal-body");
			}
		});
		
	});
});