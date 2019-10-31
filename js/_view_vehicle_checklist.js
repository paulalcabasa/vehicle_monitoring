$(document).ready(function(){

	$("#upload_notif").hide();
	var total_size = 0;

	$("#btn_add_checklist").click(function(){
		var cond = $("#rdo_cond_ok").is(":checked") ? 1 : 2;
		
		if($("#file_checklist_attachment").val()==""){
			$("#file_checklist_attachment").click();
		}
		else if($("#file_checklist_attachment")[0].files.length > 2) {
			alert("You can only upload two attachments");
		}
		else{
			if(total_size < 4){
				var formData = new FormData($("#frm_checklist")[0]);
				formData.append("vehicle_condition",cond);
				$.ajax({
					type:"POST",
					data: formData,
					processData : false,
					contentType : false,
					cache : false,
					url:"ajax/add_checklist.php",
					success:function(response){
						location.reload();
					}
				});
			}
			else {
				$("#upload_notif").html("Maximum file upload size should be less than 4mb.").show();
			}
		}
	});


	// file attachment handler
	$("#file_checklist_attachment").on("change",function(){
		$("#attachment_list").html("");
		var files = $(this)[0].files; 
		
		$("#attachment_list").parent().show();
		for (var i = 0, f; f = files[i]; i++) {
		    if( (f.size/1024/1024) < 2) {
		    	$("#attachment_list").append("<li>" + f.name + " - " + ((f.size/1024/1024).toFixed(2)) + " MB </li>");
				total_size += (f.size/1024/1024);
			}
			else {
				$("#file_checklist_attachment").val("");
				$("#attachment_list").html("");
				total_size = 0;
				alert(f.name + " exceeds the accepted file size, maximum file attachment should only be less than 2 MB.");
			}
			
		}
		
		if(total_size > 4) { 
			alert("Total file size should be less than 4 MB. The size of your attachment is " + total_size.toFixed(2) + " MB.");
			$("#inp_attachment").val("");
			$("#attachment_list").parent().hide();
		}
		else {
			$("#attachment_list").parent().show();
			$("#attachment_list").parent().children().remove("strong[class=total]");
			$("#attachment_list").parent().append("<strong class='total'>Total : " + total_size.toFixed(2) + " MB</strong>");
		}

	});

});