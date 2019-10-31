$(document).ready(function(){
		/* sets header of the page */
	utils.setPageHeader("page_header","Vehicle's Checklist","<li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li><li class='active'>Checklist</li>");


	// Setup - add a text input to each footer cell
  /*  $('#tbl_checklist tfoot td').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" class="form-control input-sm" placeholder="Search '+title+'" />' );
    });*/

     // Initialize data table
    var table = $('#tbl_checklist').DataTable();

     

});



   