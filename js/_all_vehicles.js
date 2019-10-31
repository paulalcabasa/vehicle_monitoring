$(document).ready(function(){
		/* sets header of the page */
	utils.setPageHeader("page_header","Vehicle Units","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Checklist</li>");

    var last_query = "";

	// Setup - add a text input to each footer cell
  /*  $('#tbl_checklist tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });*/

     // Initialize data table
    var table = $('#tbl_all_vehicles').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth":false,
        "ajax": 'ajax/get_all_vehicles.php',
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
    });

   

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
});