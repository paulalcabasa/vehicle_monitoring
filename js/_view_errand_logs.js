
$(document).ready(function() {

    utils.setPageHeader("page_header","Logs - Errand","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Errand Logs</li>");
   
    $("#tbl_logs").DataTable();
  //  var start_date, end_date;
    $("#txt_date").daterangepicker({
        locale: {
            format: 'MM/DD/YYYY'
        },
        "showDropdowns": true,
        "showWeekNumbers": true,
        "timePicker": false,
        "autoApply": true,
        useCurrent: false
    });

    $('#txt_date').on('apply.daterangepicker', function(ev, picker) {
        $("input[name=start_date]").val(picker.startDate.format('YYYY-MM-DD'));
        $("input[name=to_date]").val(picker.endDate.format('YYYY-MM-DD'));
    });

    $("#sel_vehicle").select2();
    
} );