$(document).ready(function() {

    var trip_ticket_no;

    utils.setPageHeader("page_header","Closed Trip Tickets","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>All Trip Tickets</li>");

    // Setup - add a text input to each footer cell
    $('#tbl_trip_ticket tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });

    // Initialize data table
/*    var table = $('#tbl_trip_ticket').DataTable( {
        "processing": true,
        "serverSide": true,
        "autoWidth":false,
        "ajax": $.fn.dataTable.pipeline({
            url: 'ajax/get_trip_tickets.php',
            pages: 5 // numSber of pages to cache
        })
    } );*/

     // Initialize data table
    var table = $('#tbl_trip_ticket').DataTable( {
     //   "processing": true,
      //  "serverSide": true,
       // "autoWidth":false,
       // "ajax": 'ajax/get_closed_trip_tickets.php',
        "deferRender" : true,
        "aaSorting": []
    });

    // Apply the search
    table.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'input', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });


    $("body").on("click",".btn_cancel_trip",function(ev){
        ev.preventDefault();
        trip_ticket_no = $(this).data("id");
        utils.setModalProperty($("#modal-confirm"),"Trip Ticket Cancellation",".modal-title");
        utils.setModalProperty($("#modal-confirm"),"Are you sure to cancel Trip Ticket No. <strong>"+trip_ticket_no+"</strong>?",".modal-body");
        $("#modal-confirm").modal("show");
    });

    $("#modal-btn-confirm").click(function(){
        $.ajax({
            type:"POST",
            data:{
                trip_ticket_no : trip_ticket_no
            },
            url:"ajax/cancel_trip.php",
            success:function(response){
                table.draw(); 
                $("#modal-confirm").modal("hide");
            }
        });
    });
    
    $("body").on("click",".btn_update_trip_status",function(){ 
        var trip_ticket_no = $(this).data("id"); 
        var trip_status_id = $(this).data("trip_status_id");
        $.blockUI({ 
            message: '<h1>Processing... <img src="../../img/ajax-loader.gif" height="30"/></h1>' 
        }); 
        $.ajax({
            type:"POST",
            data:{
                trip_ticket_no : trip_ticket_no,
                trip_status_id : trip_status_id
            },
            url:"ajax/update_trip_ticket_status.php",
            success:function(response){
              
                $.unblockUI({ fadeOut: 1500 }); 
                table.draw();
            }
        });
    });

    $("body").on("click",".btn_approval",function(){
        var trip_ticket_no = $(this).data("trip_ticket_no");
        var approver_id = $(this).data("approver_id");
        $.blockUI({ 
            message: '<h1>Processing... <img src="../../img/ajax-loader.gif" height="30"/></h1>' 
        }); 
      
        $.ajax({
            type:"POST",
            data:{
                trip_ticket_no : trip_ticket_no,
                approver_id : approver_id
            },
            url:"ajax/approve_on_behalf_of.php",
            success:function(response){
                $.unblockUI({ fadeOut: 1500 }); 
                table.draw();
            }
        });
    });
    
    $("body").on("click",".btn_approve_selected_on_behalf,#btn_approve_selected",function(){
        var approver_id = $(this).data("id");
        var list_of_trip_ticket = [];
        var index = 0;
        $(".cb_trip_ticket_no").each(function(){
            if($(this).is(":checked")){
                list_of_trip_ticket[index] = $(this).val();
                index++;
            }            
        });

        if(index == 0){
            utils.setModalProperty($("#modal-info"),"Please select <strong>Trip Ticket/s</strong> to approve",".modal-body");
            utils.setModalProperty($("#modal-info"),"Trip Ticket Approval",".modal-title");
            $("#modal-info").modal("show");    
        }
        else {
            $.blockUI({ 
                message: '<h1>Processing... <img src="../../img/ajax-loader.gif" height="30"/></h1>' 
            });
            $.ajax({
                type:"POST",
                data:{
                     approver_id : approver_id,
                     list_of_trip_ticket : list_of_trip_ticket
                },
                url:"ajax/approve_selected_on_behalf.php",
                success:function(response){
                    $.unblockUI({ fadeOut: 1500 }); 
                    table.draw();
                    $(".selected_label").text(0);
                }
            });
        }
    });

    $("body").on("click",".cb_trip_ticket_no",function(){
        countSelected();
    });

    $("#btn_select_all").click(function(){
        $(".cb_trip_ticket_no").prop("checked","true");
        countSelected();
    });

     $("#btn_unselect_all").click(function(){
        $(".cb_trip_ticket_no").removeAttr("checked");
        countSelected();
    });
    
    function countSelected(){
        var selected_count = 0;
        $(".cb_trip_ticket_no").each(function(){
            if($(this).is(":checked")){
                selected_count++;
            }
        });
        $(".selected_label").text(selected_count);
    }

    $("#to_date,#from_date").datetimepicker({
        format:"YYYY-MM-DD",
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    });


});