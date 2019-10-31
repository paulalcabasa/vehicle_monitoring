$(document).ready(function(){
		/* sets header of the page */
	utils.setPageHeader("page_header","Vehicle's Checklist","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Checklist</li>");


	// Setup - add a text input to each footer cell
  /*  $('#tbl_checklist tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });*/

     // Initialize data table
    var table = $('#tbl_checklist').DataTable( {
        "processing": true,
        "serverSide": true,
        "autoWidth":false,
        "ajax": 'ajax/get_vehicle_checklist.php'
    } );

    // Apply the search
/*    table.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });*/

    $("body").on("click",".cb_toggle_status",function(){
    	var status = 0;
    	var vehicle_id = $(this).parent().parent().parent().parent().children("td:first").text();

    	if($(this).is(":checked")){ // if checked then available = 1
    		status = 1;
    	}
    	else {
    		status = 0;
    	}
    	
    	$.ajax({
    		type:"POST",
    		data:{
    			vehicle_id : vehicle_id,
    			status     : status
    		},
    		url:"ajax/update_vehicle_checklist.php",
    		success:function(response){
    		
    			table.draw();
    		}
    	});
    });

    $("body").on("click",".btn_show_remarks",function(){
        $("#txt_vehicle_id").val($(this).data("id"));
        $("#modal-add-remarks").modal("show");
    });

    $("#btn_add_remarks").click(function(){
        var vehicle_id = $("#txt_vehicle_id").val();
        var remarks = $("#txt_remarks").val();
        $.ajax({
            type:"POST",
            data:{
                vehicle_id : vehicle_id,
                remarks : remarks
            },
            url:"ajax/add_vehicle_remarks.php",
            success:function(response){
                table.draw();
                $("#txt_remarks,#txt_vehicle_id").val("");
                $("#modal-add-remarks").modal("hide");
            }
        });

    });


});



   