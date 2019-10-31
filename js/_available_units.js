$(document).ready(function(){
        /* sets header of the page */
    utils.setPageHeader("page_header","Available Units","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Available Units</li>");

    var last_query = "";

    // Setup - add a text input to each footer cell
  /*  $('#tbl_checklist tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });*/

     // Initialize data table
    
    $(document).on('change', '#classification', function() {
        //alert($("#classification").val());
        $('#frm_tt').submit();
        //alert($("#classification").val());
    });

    //$.ajax({
        //         type:"POST",
        //         data:{
        //              classification : $('#classification').val()
        //         },
        //         url:"ajax/get_all_available_units.php",
        //         success:function(response){
        //             $.unblockUI({ fadeOut: 1500 }); 
        //             table.draw();
        //         }
        //     });
    $("#classification").select2();
    $('#tbl_all_available_units').DataTable({});

    // var table = $('#tbl_all_available_units').DataTable({
    //     "processing": true,
    //     "serverSide": true,
    //     "autoWidth":false,
    //     "ajax": 'ajax/get_all_available_units.php',
    //     "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
    // });

});