$(document).ready(function(){
	$("#btn_trigger_upload").click(function(){
		$("#file_barcode_list").click();
	});

	$("#file_barcode_list").change(function(){
		$.blockUI({ 
            message: '<h1>Processing... <img src="../../img/ajax-loader.gif" height="30"/></h1>' 
        }); 
        var formData = new FormData($("#frm_barcode")[0]);
		$.ajax({
            type:"POST",
            data: formData,
            url:"ajax/add_temp_barcode.php",
            cache:false,
            contentType:false,
            processData:false,
            success:function(response){
                window.location.href = "pages/print_generated_barcode.php";
            }
        });
	});
});