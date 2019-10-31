
$(document).ready(function() {
    utils.setPageHeader("page_header","Destination","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Destination</li>");
    var destination_id;
    // Setup - add a text input to each footer cell
    $('#tbl_destination tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });

    // Initialize data table
    var table = $('#tbl_destination').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": 'ajax/get_destination_list.php',
        "deferRender" : true
        // pages: 5 // number of pages to cache
    } );

    // Apply the search
    table.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );


    $("body").on("click",".btn_delete",function(){
        driver_id = $(this).data("id");
        utils.setModalProperty($("#modal-confirm"),"Confirmation",".modal-title");
        utils.setModalProperty($("#modal-confirm"),"Are you sure?",".modal-body");
        $("#modal-confirm").modal("show");
        //table.draw();
    });

    $("#modal-confirm #modal-btn-confirm").click(function(){
        utils.setModalProperty($("#modal-confirm"),"Please wait... <img src='../../img/ajax-loader.gif'/>",".modal-body");
        $.ajax({
            type:"POST",
            data:{
                driver_id : driver_id
            },
            url:"ajax/delete_driver.php",
            success:function(response){
                table.draw();
                $("#modal-confirm").modal("hide");
            }
        });
    });

    $("#btn_add_destination").click(function(){
        $.ajax({
            type:"POST",
            data:{
                destination : $("#txt_destination").val(),
                toll_fee    : $("#txt_toll_fee").val(),
                distance    : $("#txt_distance").val(),
                zip_code    : $("#txt_zip_code").val(),
                area_code   : $("#txt_area_code").val()
            },
            url:"ajax/add_destination.php",
            success:function(response){
                $("#notif").html(response).show("slow");
                $("#txt_destination,#txt_toll_fee,#txt_distance,#txt_zip_code,#txt_area_code").val("");
                table.draw();
            }
        });
    });

    $("#modal-add-new").on('hidden.bs.modal',function(){
        $("#notif").hide();
    });

    $("body").on("click",".btn_edit",function(){
        destination_id = $(this).data("id");
        $.ajax({
            type:"POST",
            data:{
                id : destination_id
            },
            url:"ajax/get_destination_details.php",
            success:function(response){
                var data = JSON.parse(response);
                $("#u_txt_destination").val(data.destination),
                $("#u_txt_toll_fee").val(data.toll_fee),
                $("#u_txt_distance").val(data.distance),
                $("#u_txt_zip_code").val(data.zip_code),
                $("#u_txt_area_code").val(data.area_code),                
                $("#modal-edit-destination").modal("show");
            }
        });
    });

    $("#btn_update_destination").click(function(){   
        $.ajax({
            type:"POST",
            data:{
                destination_id : destination_id,
                destination    : $("#u_txt_destination").val(),
                toll_fee       : $("#u_txt_toll_fee").val(),
                distance       : $("#u_txt_distance").val(),
                zip_code       : $("#u_txt_zip_code").val(),
                area_code      : $("#u_txt_area_code").val()
            },
            url:"ajax/update_destination.php",
            success:function(response){
                $("#modal-edit-destination").modal("hide");
                table.draw();
            }
        });
    });
} );