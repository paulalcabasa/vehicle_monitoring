$(document).ready(function(){

	$("body").on("click",".btn_resend",function(e){
		$(this).children("i:first").addClass("fa-spin");
		var employee_id = $(this).data("emp_id");
		var trip_ticket_no = $(this).data("trip_ticket_no");
		var elem = $(this);
		$.ajax({
			type:"POST",
			data:{
				employee_id : employee_id,
				trip_ticket_no : trip_ticket_no
			},
			url:"ajax/resend_mail_to_approver.php",
			success:function(response){
				alert(response);
				elem.children("i:first").removeClass("fa-spin");
			}
		});
		e.preventDefault();

	});

});