
$(document).ready(function() {
    utils.setPageHeader("page_header","Drivers","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Drivers</li>");
    var driver_id;
    // Setup - add a text input to each footer cell
    $('#tbl_drivers_list tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });

    // Initialize data table
    var table = $('#tbl_drivers_list').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": 'ajax/get_drivers_list.php',
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

    $("#btn_add_driver").click(function(){
        var formData = new FormData($("#frm_driver_data")[0]);
        $(this).button("loading");
        $.ajax({
            type:"POST",
            data: formData,
            url:"ajax/add_driver.php",
            cache:false,
            contentType:false,
            processData:false,
            success:function(response){
                location.reload();
                $("#notif").html(response).show("slow");
               // $("#txt_last_name,#txt_middle_name,#txt_first_name").val(""); 
                table.draw();
            }
        });
    });

    $("#modal-add-new").on('hidden.bs.modal',function(){
        $("#notif").hide();
    });

    $("body").on("click",".btn_edit",function(){
        driver_id = $(this).data("id");
        $.ajax({
            type:"POST",
            data:{
                driver_id : driver_id
            },
            url:"ajax/get_driver_info.php",
            success:function(response){
                var data = JSON.parse(response);
                $("#u_txt_first_name").val(data.first_name);
                $("#u_txt_middle_name").val(data.middle_name);
                $("#u_txt_last_name").val(data.last_name);
                $("#u_txt_company").val(data.company);
                $("#u_sel_car_units").val(data.assigned_vehicle_id);
                if(data.picture!=null){
                    $("#u_img_prev").attr("src","images/driver_pics/" + data.picture);
                }
                $("#u_txt_contact_no").val(data.contact_no);
                //$("#u_txt_attachment").val(data.attachment);
                $("#modal-edit-driver").modal("show");
            }
        });
    });

    $("#btn_update_driver").click(function(){
        var formData = new FormData($("#u_frm_driver_data")[0]);
        formData.append("driver_id",driver_id);
        $(this).button("loading");
        $.ajax({
            type:"POST",
            data: formData,
            url:"ajax/update_driver.php",
            cache:false,
            contentType:false,
            processData:false,
            success:function(response){
              
                location.reload();
            }
        });
    });


     $('.driver_pic').fancybox();
} );