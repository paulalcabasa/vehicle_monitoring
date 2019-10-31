$(document).ready(function(){
	$("#btn_trigger_upload").click(function(){
		$("#file_fleet_card").click();
	});

	$("#file_fleet_card").change(function(){

		$("#frm_fleet_card").submit();
		/*$.blockUI({ 
            message: '<h1>Processing... <img src="../../img/ajax-loader.gif" height="30"/></h1>' 
        }); 
        var formData = new FormData($("#frm_barcode")[0]);
		$.ajax({
            type:"POST",
            data: formData,
            url:"ajax/update_fleet_card.php",
            cache:false,
            contentType:false,
            processData:false,
            success:function(response){
            	alert(response);
               // window.location.href = "pages/print_generated_barcode.php";
            }
        });*/
	});
});