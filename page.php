<?php
	require_once("initialize.php");
	if(!isset($_SESSION['user_data'])){
		header("location:../../../index.php");
	}
	// list in barcode printing
	$_SESSION['vehicle_list'] = array();

	require_once("../../classes/class.system.php");
    require_once("../../classes/class.main_conn.php");
    require_once("../../classes/class.employee_masterfile.php");
	$driver = new Driver();
	$system = new System();
    $dbconn = new Main_Conn();
    $employee_masterfile = new Employee_Masterfile();
	$get = (object)$_GET;
	$user_data = (object)$_SESSION['user_data'];
	$emp_details = $driver->getEmployeeDetailsById($user_data->employee_id);
	$user_access = $employee_masterfile->getUserAccess($_SESSION['user_data']['employee_id'],SYSTEM_ID);
 	if(file_exists("../../emp_pics/" . $user_data->employee_id . ".jpg")){
    	$pic = $user_data->employee_id . ".jpg";
    }
    else if(file_exists("../../emp_pics/" . $emp_details->employee_no . ".jpg")){
    	$pic = $emp_details->employee_no . ".jpg";
    }
    else {
    	$pic = "anonymous.png";
    }

?>
<!DOCTYPE html>
<html>
	<head>
	
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<title>Vehicle Monitoring</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="../../libs/admin_lte/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="../../libs/font-awesome-4.5.0/css/font-awesome.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="../../libs/admin_lte/dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="../../libs/admin_lte/dist/css/skins/skin-red.min.css">
		<!-- Date Picker -->
		<!-- <link rel="stylesheet" href="../../libs/admin_lte/plugins/datepicker/datepicker3.css"> -->
		<!-- Daterange picker -->
		<link rel="stylesheet" href="../../libs/admin_lte/plugins/daterangepicker/daterangepicker-bs3.css">
		<!-- Datetimepicker -->
		<link rel="stylesheet" href="plugins/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css">
		<!-- Select 2 -->
		<link rel="stylesheet" href="../../libs/admin_lte/plugins/select2/select2.min.css">
		<!-- custom css -->
		<link rel="stylesheet" href="../../libs/admin_lte/dist/css/custom.css">
		<!-- css for vehicle monitoring -->
		<link rel="stylesheet" href="css/vm-style.css">
		<!-- boostrap data tables -->
			<!-- Font Awesome -->
	<!-- 	<link rel="stylesheet" href="../../libs/DataTables-1.10.10/media/css/dataTables.bootstrap.min.css"> -->
		<link rel="stylesheet" href="../../libs/Datatables-1.16.10/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" href="../../libs/DataTables-1.16.10/Buttons-1.5.0/css/buttons.dataTables.min.css">
		<link rel="stylesheet" href="../../libs/DataTables-1.16.10/Buttons-1.5.0/css/buttons.bootstrap.min.css">
		<!-- css for vehicle monitoring -->
		<link rel="stylesheet" href="../../libs/DataTables-1.16.10/Responsive-2.2.1/css/responsive.bootstrap.min.css">
		<link rel="stylesheet" href="../../libs/fancybox/source/jquery.fancybox.css">
		<!-- favicon -->
		
		<link rel='shortcut icon' type='image/x-icon' href='../../img/favicon.ico' />
		<link rel="stylesheet" href="../../libs/DataTables-1.16.10/Responsive-2.2.1/css/responsive.bootstrap.min.css">
<!-- 		<link rel="stylesheet" href="../../libs/jquery-modal/jquery.modal.min.css"> -->
		<!-- <link rel="stylesheet" href="../../libs/MDB/css/mdb.min.css"> -->
		<link rel="stylesheet" href="../../libs/sweetalert2/package/dist/sweetalert2.min.css">


		<!-- Load assets for wPaint if New Checklist is loaded -->
		<?php if(in_array($get->p,array("nckl","vckd"))) : ?>
		<link rel="Stylesheet" type="text/css" href="plugins/wPaint/wPaint.min.css" />
		<link rel="Stylesheet" type="text/css" href="plugins/wPaint/lib/wColorPicker.min.css" />
		<?php endif; ?>
		<meta charset="utf-8"/>

	</head>

	<body class="hold-transition skin-red sidebar-mini">

		<div class="wrapper">

			<?php include("includes/main-header.php");?>
			<!-- Left side column. contains the logo and sidebar -->
			<?php include("includes/main-sidebar.php");?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<?php include("includes/main-page-header.php");?>
				<!-- Main content -->
				<section class="content">
					<!-- small boxes on top here -->
					<!-- Main row -->
					<div class="row">
						
						<!-- main content here -->
						<?php

							switch($get->p){

								// home page
								case 'h': 
									include("pages/home.php");
								break;

								// create trip ticket
								case 'ct':
									include("pages/create_trip_ticket.php");
								break;

								// create trip ticket
								case 'ct2':
									include("pages/create_trip_ticket2.php");
								break;

								// view trip tickets
								case 'vt':
									include("pages/view_trip_tickets_2.php");
								break;

							/*	// view trip tickets
								case 'vt2':
									include("pages/view_trip_tickets_2.php");
								break;*/

								// record log
								case 'rtl':
									include("pages/record_time_log.php");
								break;

								// trip ticket report
								case 'tr':
									include("pages/trip_report.php");
								break;

								// trip ticket reports
								case 'ttr':
									include("pages/trip_tricket_report.php");
								break;

								// trip ticket reports 2
								case 'ttr2':
									include("pages/trip_tricket_report2.php");
								break;

								case 'tlr':
									include("pages/trip_log_report.php");
								break;

								// managers attendance
								case 'ma':
									include("pages/managers_attendance.php");
								break;

								// carplan logs
								case 'vcpl':
									include("pages/view_carplan_logs.php");
								break;

								// company car logs
								case 'vccl':
									include("pages/view_company_car_logs.php");
								break;

								// vehicle usage - company car
								case 'ccvu':
									include("pages/company_car_vehicle_usage.php");
								break;

								// vehicle usage - car plan
								case 'cpvu':
									include("pages/carplan_vehicle_usage.php");
								break;

								// company drivers
								case 'drv':
									include("pages/view_drivers.php");
								break;
								
								// view list of epass
								case 'epl':
									include("pages/view_epass_list.php");
								break;

								// Epass monitoring page
								case 'epm':
									include("pages/epass_monitoring.php");
								break;
								
								// view list of fleet card
								case 'fcl':
									include("pages/view_fleet_card_list.php");
								break;
								
								// view list of fleet card
								case 'fcm':
									include("pages/fleet_card_monitoring.php");
								break;	

								// edit trip ticket
								case 'et':
									include("pages/edit_trip_ticket.php");
								break;

								// update time log
								case 'utl':
									include("pages/update_time_log.php");
								break;

								// destination
								case 'dest':
									include("pages/view_destination.php");
								break;

								// recording of managers attendance
								case 'rma':
									include("pages/record_managers_attendance.php");
								break;
								
								// vehicles checklist
								case 'vcl':
									include("pages/vehicle_checklist.php");
								break;
								
								// managers attendance
								case 'ama':
									include("pages/all_managers_attendance.php");
								break;

								// managers attendance
								case 'av':
									include("pages/all_vehicles.php");
								break;

								// view vehicle checklist
								case 'vvc':
									include("pages/view_vehicle_checklist.php");
								break;

								// barcode generator
								case 'bgen':
									include("pages/barcode_generator.php");
								break;

								// errand time log
								case 'rertl':
									include("pages/record_errand_time_log.php");
								break;

								// view errand time log
								case 'vertl':
									include("pages/view_errand_logs.php");
								break;

								// update errand time log
								case 'uetl':
									include('pages/update_errand_log.php');
								break;

								// expats time log
								case 'rextl':
									include('pages/record_expats_time_log.php');
								break;

								// view expats time log
								case 'vextl':
									include('pages/view_expats_logs.php');
								break;

								// update errand time log
								case 'uextl':
									include('pages/update_expats_log.php');
								break;

								// update errand time log
								case 'vct':
									include('pages/closed_trip_tickets_2.php');
								break;

								// update errand time log
								case 'vct2':
									include('pages/closed_trip_tickets2.php');
								break;

								// barcode generator
								case 'ufc':
									include('pages/update_fleet_card.php');
								break;

								// view list of fleet card
								case 'fcm2':
									include("pages/fleet_card_monitoring2.php");
								break;	

                                // view list of available units
                                case 'au':
                                    include("pages/available_units.php");
                                break;

                                // view list of vehicle units, whether IN, OUT, AVAILABLE OR NOT AVAIABLE
                                case 'vus':
                                	include('pages/vehicle_units.php');
                                break;

                                // new form for checklist
                                case 'nckl':
                                	include('pages/new_vehicle_checklist.php');
                                break;

                                // view vehicle checklist 2
                                case 'vcl2':
                                	include('pages/vehicle_checklist_2.php');
                                break;

                                 // view vehicle checklist 2
                                case 'vvc2':
                                	include('pages/view_vehicle_checklist2.php');
                                break;

                                // edit checklist details
                                case 'vckd':
                                	include('pages/view_vehicle_checklist_details.php');
								break;
								
								case 'errand_report':
                                	include('pages/errand_report.php');
                                break;

								// error 404
								default:
									include("pages/error_404.php");
								break;
							}
						?>

					</div>
					<!-- /.row (main row) -->

				</section><!-- /.content -->

			</div><!-- /.content-wrapper -->

		<?php include("includes/main-footer.php");?>
		<?php //include("includes/main-control-sidebar.php");?>

		</div><!-- ./wrapper -->
	<noscript>
	 For full functionality of this site it is necessary to enable JavaScript.
	 Here are the <a href="http://www.enable-javascript.com/" target="_blank">
	 instructions how to enable JavaScript in your web browser</a>.
	 
	</noscript> 
    <!-- jQuery 2.1.4 -->
<!--     <script src="../../libs/admin_lte/plugins/jQuery/jQuery-2.1.4.min.js"></script> -->
	<?php 
		if(in_array($get->p,array("nckl","vckd"))) { 
	?>
		<script type="text/javascript" src="plugins/wPaint/lib/jquery.1.10.2.min.js"></script>
	
	<?php	
		}
		else {
	?>
		<script src="js/jquery-3.2.1.min.js"></script>
	<?php
		}
	?>
	
    <!-- jQuery UI 1.11.4 -->

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    -->
    <!-- Bootstrap 3.3.5 -->
    <script src="../../libs/admin_lte/bootstrap/js/bootstrap.min.js"></script>
    <!-- daterangepicker -->
    <script src="../../libs/admin_lte/plugins/daterangepicker/moment.min.js"></script>
     <script src="../../libs/admin_lte/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- <script src="../../libs/admin_lte/plugins/datepicker/bootstrap-datepicker.js"></script> -->
    <!-- Slimscroll -->
    <!-- <script src="../../libs/admin_lte/plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
    <!-- AdminLTE App -->
    <script src="../../libs/admin_lte/dist/js/app.min.js"></script>
    <script src="js/jquery.scannerdetection.js"></script>
    <script src="../../libs/admin_lte/plugins/select2/select2.full.min.js"></script>
   <!-- <script src="../../libs/admin_lte/plugins/datatables/jquery.dataTables.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script> -->
    <!-- <script src="../../libs/admin_lte/plugins/datatables/dataTables.bootstrap.min.js"></script>-->
	<script src="../../libs/DataTables-1.16.10/js/jquery.dataTables.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/js/dataTables.bootstrap.min.js"></script>
	<!-- <script src="../../libs/DataTables-1.16.10/Responsive-2.2.1/js/dataTables.responsive.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/Responsive-2.2.1/js/responsive.bootstrap.min.js"></script> -->
	
	<script src="../../libs/DataTables-1.16.10/Buttons-1.5.0/js/dataTables.buttons.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/Buttons-1.5.0/js/buttons.bootstrap.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/Buttons-1.5.0/js/buttons.flash.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/pdfmake-0.1.32/pdfmake.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/pdfmake-0.1.32/vfs_fonts.js"></script>
	<script src="../../libs/DataTables-1.16.10/JSZip-2.5.0/jszip.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/Buttons-1.5.0/js/buttons.html5.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/Buttons-1.5.0/js/buttons.colVis.min.js"></script>
	<script src="../../libs/DataTables-1.16.10/Buttons-1.5.0/js/buttons.print.min.js"></script>

	<script src="../../libs/jquery.blockui/jquery.blockui-2.7.js"></script>
	<script src="../../libs/bootstrap3-typeahead/bootstrap3-typeahead.min.js"></script>
	<script src="../../libs/fancybox/source/jquery.fancybox.pack.js"></script>
	<script src="../../libs/cleave.js-master/dist/cleave.min.js"></script>
	<script src="../../libs/sweetalert2/package/dist/sweetalert2.min.js"></script>
<!-- 	<script src="../../libs/jquery-modal/jquery.modal.min.js"></script>
	<script src="../../libs/MDB/js/mdb.min.js"></script> -->


    <script src="js/utils.js"></script>
    
    <!--<script src="https://cdn.datatables.net/colreorder/1.3.0/js/dataTables.colReorder.min.js"></script>-->
    <!-- my javascript codes -->
    <?php
		switch($get->p){
			
			// home page
			case 'h': 
			break;

			// create trip ticket
			case 'ct':
				echo "<script src='js/_create_trip_ticket.js'></script>";
			break;

			// create trip ticket
			case 'ct2':
				echo "<script src='js/_create_trip_ticket2.js'></script>";
			break;

			// view trip ticket
			case 'vt':
				echo "<script src='js/_view_trip_ticket_2.js'></script>";
			break;

			/*// view trip ticket
			case 'vt2':
				echo "<script src='js/_view_trip_ticket_2.js'></script>";
			break;*/

			// record company car log
			case 'rtl':
				echo "<script src='js/_record_time_log.js'></script>";
			break;
			
			// company car report
			case 'ccr':
				echo "<script src='js/_company_car_report.js'></script>";
			break;

			//trip ticket report
			case 'ttr':

				echo "<script src='js/_trip_ticket_report.js'></script>";
			break;


			//trip ticket report
			case 'ttr2':
			     
				echo "<script src='js/_trip_ticket_report.js'></script>";
			break;

			case 'tlr':
				echo "<script src='js/_trip_log_report.js'></script>";
			break;

			// managers attendance
			case 'ma':
				echo "<script src='js/_managers_attendance.js'></script>";
			break;

			// view carplan logs
			case 'vcpl':
				echo "<script src='js/_view_carplan_logs.js'></script>";
			break;

			// view carplan logs
			case 'vccl':
				echo "<script src='js/_view_company_car_logs.js'></script>";
			break;	

			// vehicle usage - company car
			case 'ccvu':
				echo "<script src='js/_company_car_vehicle_usage.js'></script>";
			break;

				// vehicle usage - company car
			case 'cpvu':
				echo "<script src='js/_carplan_vehicle_usage.js'></script>";
			break;

			// company drivers
			case 'drv':
				echo "<script src='js/_view_drivers.js'></script>";
			break;

			// view list of epass
			case 'epl':
				echo "<script src='js/_view_epass_list.js'></script>";
			break;

			// Epass monitoring page
			case 'epm':
				echo "<script src='js/_epass_monitoring.js'></script>";
			break;

			// view list of fleet card
			case 'fcl':
				echo "<script src='js/_view_fleet_card_list.js'></script>";
			break;

			// view list of fleet card
			case 'fcm':
				echo "<script src='js/_fleet_card_monitoring.js'></script>";
			break;

			// edit trip ticket
			case 'et':
				echo "<script src='js/_edit_trip_ticket.js'></script>";
			break;

			// update time log
			case 'utl':
				echo "<script src='js/_update_time_log.js'></script>";
			break;

			// destination
			case 'dest':
				echo "<script src='js/_view_destination.js'></script>";
			break;

			// recording of managers attendance
			case 'rma':
				echo "<script src='js/_record_managers_attendance.js'></script>";
				echo "<script src='js/ion.sound.min.js'></script>";
			break;

			// vehicles checklist
			case 'vcl':
				echo "<script src='js/_vehicle_checklist.js'></script>";
			break;

			// managers attendance
			case 'ama':
				echo "<script src='js/_all_managers_attendance.js'></script>";
			break;

			// trip report
			case 'tr':
				echo "<script src='js/_trip_report.js'></script>";
			break;

			// trip report
			case 'av':
				echo "<script src='js/_all_vehicles.js'></script>";
			break;

			// trip report
			case 'vvc':
				echo "<script src='js/_view_vehicle_checklist.js'></script>";
			break;

			// barcode generator
			case 'bgen':
				echo "<script src='js/_barcode_generator.js'></script>";
			break;

			// record errand time log
			case 'rertl':
				echo "<script src='js/_record_errand_time_log.js'></script>";
			break;

			// view errand time log
			case 'vertl':
				echo "<script src='js/_view_errand_logs.js'></script>";
			break;

			// update errand time log
			case 'uetl':
				echo "<script src='js/_update_errand_time_log.js'></script>";
			break;

			// record expats time log
			case 'rextl':
				echo "<script src='js/_record_expats_time_log.js'></script>";
			break;

			// view expats time log
			case 'vextl':
				echo "<script src='js/_view_expats_logs.js'></script>";
			break;

			// update expats time log
			case 'uextl':
				echo "<script src='js/_update_expats_time_log.js'></script>";
			break;

			// update errand time log
			case 'vct':
				echo "<script src='js/_closed_trip_tickets_2.js'></script>";
			break;

			// update errand time log
			case 'vct2':
				echo "<script src='js/_closed_trip_tickets2.js'></script>";
			break;

			// view list of fleet card
			case 'ufc':
				echo "<script src='js/_fleet_card_update.js'></script>";
			break;

			// view list of fleet card
			case 'fcm2':
				echo "<script src='js/_fleet_card_monitoring2.js'></script>";
			break;

            // view list of available units
            case 'au':
                echo "<script src='js/_available_units.js'></script>";
            break;

            // view list vehicle units
            case 'vus':
            	echo "<script src='js/_vehicle_units.js'></script>";
            break;

           // new form for checklist
            case 'nckl':
				echo "<script src='js/_new_vehicle_checklist.js'></script>";

				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.core.1.10.3.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.widget.1.10.3.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.mouse.1.10.3.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.draggable.1.10.3.min.js"></script>';
		
				echo '<script type="text/javascript" src="plugins/wPaint/lib/wColorPicker.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/wPaint.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/main/wPaint.menu.main.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/text/wPaint.menu.text.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/shapes/wPaint.menu.main.shapes.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/file/wPaint.menu.main.file.min.js"></script>';
            break;

            // vehicles checklist
			case 'vcl2':
				echo "<script src='js/_vehicle_checklist2.js'></script>";
			break;

			case 'vvc2':	
				echo "<script src='js/_view_vehicle_checklist2.js'></script>";
			break;

			case 'vckd':
				
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.core.1.10.3.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.widget.1.10.3.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.mouse.1.10.3.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/lib/jquery.ui.draggable.1.10.3.min.js"></script>';
		
				echo '<script type="text/javascript" src="plugins/wPaint/lib/wColorPicker.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/wPaint.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/main/wPaint.menu.main.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/text/wPaint.menu.text.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/shapes/wPaint.menu.main.shapes.min.js"></script>';
				echo '<script type="text/javascript" src="plugins/wPaint/plugins/file/wPaint.menu.main.file.min.js"></script>';
				echo "<script src='js/_view_vehicle_checklist_details.js'></script>";

			break;

			case 'errand_report':
				echo "<script src='js/errand_report.js'></script>";
			break;

		}
	?>

  </body>
</html>
