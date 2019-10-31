$(document).ready(function(){
	
	var images = [
    'plugins/wPaint/img/crosswind.png',
    'plugins/wPaint/img/mux.png',
    'plugins/wPaint/img/Picture1.png',
    'plugins/wPaint/img/Picture2.png',
    'plugins/wPaint/img/Picture3.png',
    'plugins/wPaint/img/Picture4.png',
    'plugins/wPaint/img/Picture5.png',
		'plugins/wPaint/img/Picture6.png'
	];

    function saveImg(image) {
      var _this = this;

      $.ajax({
        type: 'POST',
        url: 'test/upload.php',
        data: {image: image},
        success: function (resp) {
          alert(resp);
          // internal function for displaying status messages in the canvas
          _this._displayStatus('Image saved successfully');

          // doesn't have to be json, can be anything
          // returned from server after upload as long
          // as it contains the path to the image url
          // or a base64 encoded png, either will work
          resp = $.parseJSON(resp);

          // update images array / object or whatever
          // is being used to keep track of the images
          // can store path or base64 here (but path is better since it's much smaller)
          images.push(resp.img);

          // do something with the image
          $('#wPaint-img').attr('src', image);
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
      // alert("hey!");
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
        strokeStyle: '#000000',
        menuOrientation : 'vertical',
        'image' : 'plugins/wPaint/img/crosswind.png'
    });


    $("#btn_save").click(function(){
    	var vehicle_id = $("#txt_vehicle_id").val();
    	var km_reading_in = $("#txt_km_reading_in").val();
    	var km_reading_out = $("#txt_km_reading_out").val();
    	var overall_remarks = $("#txt_remarks").val();
    	var overall_vehicle_condition = $("input[name='rdo_vehicle_condition']:checked").val();
    	var imageData = $("#wPaint").wPaint("image");
    	var checklist_items = [];
    	$("#tbl_checklist_items tbody tr").each(function(){
    		category_id = $(this).data('category_id');
    		item_id = $(this).find('td:nth-child(1)').data('item_id');
    		remarks = $(this).find('td:nth-child(6)').find('input:first-child').val();
    		date_repaired = $(this).find('td:nth-child(7)').find('input:first-child');
    		radio_group_name = $(this).find('td:nth-child(2)').find('input:first-child').attr('name');
    		status = ($("input[name='"+radio_group_name+"']:checked").val());
    	
    		checklist_items.push({
    			category_id : category_id,
    			item_id : item_id,
    			status : status,
    			remarks : remarks,
    			date_repaired : moment(date_repaired.data("date"),["MM/DD/YYYY"]).format("YYYY-MM-DD")
    		});
    	});
     $.blockUI({ message: '<h1><img src="images/ajax-loader.gif" /> Just a moment...</h1>' });
     $("html, body").animate({ scrollTop: 0 }, "slow");
    	$.ajax({
    		type:"POST",
    		data:{
    			vehicle_id : vehicle_id,
    			km_reading_in : km_reading_in,
    			km_reading_out : km_reading_out,
    			overall_remarks : overall_remarks,
    			overall_vehicle_condition : overall_vehicle_condition,
    			body_defects_image : imageData,
    			checklist_items : checklist_items
    		},
    		url:"ajax/add_new_checklist.php",
    		success:function(response){
          window.location.reload();
         // $("#notif span").html(response);
    			//$("#notif").removeClass("hidden");
    		}
    	});

    	//console.log(JSON.stringify(checklist_items));
    });

    $(".txt_date_repaired").datetimepicker({
      format: 'MM/DD/YYYY'
    });
});