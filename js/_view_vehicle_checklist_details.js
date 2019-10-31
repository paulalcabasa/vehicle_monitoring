$(document).ready(function(){
   $(".txt_date_repaired").datetimepicker({
   	  format: 'MM/DD/YYYY'
   });

   // body_defects_image
   var images = [
		'plugins/wPaint/img/ivan.png',
		'plugins/wPaint/img/pickup.png',
		'plugins/wPaint/img/auv.png',
		'plugins/wPaint/img/truck.png',
	];

    function saveImg(image) {
      var _this = this;
      var checklist_id = $("#txt_checklist_id").val();
      $.ajax({
        type: 'POST',
        url: 'ajax/update_checklist_image.php',
        data: {
        	checklist_id : checklist_id,
        	image: image
        },
        success: function (resp) {
        	alert("Image has been updated.");
        }
      });
    }

    function loadImgBg () {
     
      // internal function for displaying background images modal
      // where images is an array of images (base64 or url path)
      // NOTE: that if you can't see the bg image changing it's probably
      // becasue the foregroud image is not transparent.
      this._showFileModal('bg', images);
    }

    function loadImgFg () {

      // internal function for displaying foreground images modal
      // where images is an array of images (base64 or url path)
      this._showFileModal('fg', images);
    }

    // init wPaint
    $('#wPaint').wPaint({
        saveImg: saveImg,
        loadImgBg: loadImgBg,
        loadImgFg: loadImgFg,
        path: 'plugins/wPaint/',
        menuOrientation : 'vertical',
        'image' : 'images/vehicle_pics/' + $("#txt_defect_image").val()
    });

    // update button
     $("#btn_save").click(function(){
     	var checklist_id = $("#txt_checklist_id").val();
    	var vehicle_id = $("#txt_vehicle_id").val();
    	var km_reading_in = $("#txt_km_reading_in").val();
    	var km_reading_out = $("#txt_km_reading_out").val();
    	var overall_remarks = $("#txt_remarks").val();
    	var overall_vehicle_condition = $("input[name='rdo_vehicle_condition']:checked").val();
    	// var imageData = $("#wPaint").wPaint("image");
    	var checklist_items = [];
    	$("#tbl_checklist_items tbody tr").each(function(){
    		category_id = $(this).data('category_id');
    		line_id = $(this).find('td:nth-child(1)').data('line_id');
    		remarks = $(this).find('td:nth-child(6)').find('input:first-child').val();
    		date_repaired = $(this).find('td:nth-child(7)').find('input:first-child');
    		radio_group_name = $(this).find('td:nth-child(2)').find('input:first-child').attr('name');
    		status = ($("input[name='"+radio_group_name+"']:checked").val());
    		
    		date_repaired_value = "";
    		if(date_repaired.val() != ""){
    			date_repaired_value = moment(date_repaired.data("date"),["MM/DD/YYYY"]).format("YYYY-MM-DD");
    		}

    		checklist_items.push({
    			line_id       : line_id,
    			status        : status,
    			remarks       : remarks,
    			date_repaired : date_repaired_value
    		});

    	});
     	
     	$.blockUI({ message: '<h1><img src="images/ajax-loader.gif" /> Just a moment...</h1>' });
     	$("html, body").animate({ scrollTop: 0 }, "slow");
    
    	$.ajax({
    		type:"POST",
    		data:{
    			checklist_id : checklist_id,
    			vehicle_id : vehicle_id,
    			km_reading_in : km_reading_in,
    			km_reading_out : km_reading_out,
    			overall_remarks : overall_remarks,
    			overall_vehicle_condition : overall_vehicle_condition,
    			checklist_items : checklist_items
    		},
    		url:"ajax/update_checklist.php",
    		success:function(response){	
				window.location.reload();
				// $("#notif span").html(response);
				// $("#notif").removeClass("hidden");
    		}
    	});

    	//console.log(JSON.stringify(checklist_items));
    });


});