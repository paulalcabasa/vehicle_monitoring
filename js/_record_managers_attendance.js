function loadEmployeeDetails(employee_no){
	$("#txt_employee_no").val(employee_no);
	$.ajax({
		type:"POST",
		data:{
			employee_no : employee_no
		},
		url:"ajax/save_manager_log_time.php",
		success:function(response){
			if(response!="error"){
				var data = JSON.parse(response);
				$("#txt_name").val(data.emp_details.first_name + " " + data.emp_details.middle_name + " " + data.emp_details.last_name);
				$("#txt_department").val(data.emp_details.department);
				$("#txt_section").val(data.emp_details.section);
				$("#emp_pic").attr("src","../../emp_pics/" + data.emp_pic);
				ion.sound.play("thank_you");
				utils.removeInputError($("#txt_employee_no").parent());
			}
			else {
				$("#txt_name,#txt_department,#txt_section").val("");
				$("#emp_pic").attr("src","../../emp_pics/anonymous.png");
				utils.markInputError($("#txt_employee_no").parent(),"Please enter a valid employee no");
				ion.sound.play("please_try_again");
			}
		}
	});
}

$(document).ready(function(){

	ion.sound({
	    sounds: [
	        {name: "thank_you"},
	        {name: "please_try_again"},
	        {name: "button_click"}
	    ],
	    // main config
	    path: "audio/",
	    preload: true,
	    multiplay: true,
	    volume: 0.9
	});

	$("#txt_employee_no").scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){	
			loadEmployeeDetails(barcode);
		}			
	});

	$("#btn_save").on("click",function(){
		loadEmployeeDetails($("#txt_employee_no").val());
	});
	

});