 <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../../emp_pics/<?php echo $pic;?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo Format::makeUppercase($emp_details->first_name. ' ' . $emp_details->last_name);?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Active</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
        <?php
            if($user_access['user_type'] == "Administrator" || $user_access['user_type'] == "SuperUser"){
        ?>
            <li class="header">TRIP TICKET</li>
            <!-- <li><a href="page.php?p=ct"><i class="fa fa-ticket"></i> <span>New Trip Ticket</span></a></li>
            --> <li><a href="page.php?p=ct2"><i class="fa fa-ticket"></i> <span>New Trip Ticket</span></a></li>
            
            <li><a href="page.php?p=vt"><i class="fa fa-book"></i> <span>Active Trip Tickets</span></a></li> 
            <li><a href="page.php?p=vct"><i class="fa fa-book"></i> <span>Closed Trip Tickets</span></a></li> 
        <!--     <li><a href="page.php?p=vcl"><i class="fa fa-list-alt"></i> <span>Checklist</span></a></li> 
         -->    <li><a href="page.php?p=vcl2"><i class="fa fa-list-alt"></i> <span>Checklist</span></a></li> 
            <li><a href="page.php?p=ttr2"><i class="fa fa-pie-chart"></i> <span>Reports</span></a></li>  
            <li><a href="page.php?p=tlr"><i class="fa fa-pie-chart"></i> <span>Trip Logs</span></a></li>  
            <li class="header">LOGS AND REPORTS</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-truck"></i> <span>Company Car</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=rtl&c=1"><i class="fa fa-plus-circle"></i> New Log</a></li>
                    <li><a href="page.php?p=vccl"><i class="fa fa-clock-o"></i> View Logs</a></li>
                    <li><a href="page.php?p=ccvu"><i class="fa fa-tachometer"></i> Vehicle Usage</a></li>  
                   <!--
                    <li class="treeview active">
                        <a href="#">
                            <i class="fa fa-tachometer"></i> <span>Vehicle Usage</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu menu-open" style="display:block;">
                            <li><a href="page.php?p=ccvu"><i class="fa fa-clock-o"></i> All </a></li>
                            <li><a href="page.php?p=ccvupv"><i class="fa fa-car"></i> Per Vehicle </a></li>
                            <li><a href="page.php?p=ccvupd"><i class="fa fa-user"></i> Per Driver </a></li>
                        </ul>
                    </li>
                    -->
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-car"></i> <span>Car Plan</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=rtl&c=2"><i class="fa fa-plus-circle"></i> New Log</a></li>
                    <li><a href="page.php?p=vcpl"><i class="fa fa-clock-o"></i> View Logs</a></li>
                    <li><a href="page.php?p=cpvu"><i class="fa fa-bar-chart-o"></i> Vehicle Usage</a></li>
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-car"></i> <span>Errand</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=rertl&c=3"><i class="fa fa-plus-circle"></i> New Log</a></li>
                    <li><a href="page.php?p=vertl"><i class="fa fa-clock-o"></i> View Logs</a></li>
                    <li><a href="page.php?p=errand_report"><i class="fa fa-clock-o"></i> Report</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-car"></i> <span>Expats</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=rextl&c=4"><i class="fa fa-plus-circle"></i> New Log</a></li>
                    <li><a href="page.php?p=vextl"><i class="fa fa-clock-o"></i> View Logs</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Manager's Attendance</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=rma"><i class="fa fa-plus-circle"></i> New Log</a></li>
                    <!--<li><a href="page.php?p=vma"><i class="fa fa-clock-o"></i> View Logs</a></li>-->
                    <li><a href="page.php?p=ama"><i class="fa fa-users"></i> All managers</a></li> 
                    <li><a href="page.php?p=ma"><i class="fa fa-user"></i> Per manager</a></li> 
                   
                </ul>
            </li>
            
            <li><a href="page.php?p=epm"><i class="fa fa-credit-card"></i> <span>E-Pass</span></a></li>
           <!--  <li><a href="page.php?p=fcm"><i class="fa fa-credit-card-alt"></i> <span>Fleet Card</span></a></li> -->
            
           <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-credit-card"></i> <span>Fleet Card</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                     <li><a href="page.php?p=fcl"><i class="fa fa-list-alt"></i>List of Fleet Card</a></li> 
                    <li><a href="page.php?p=fcm"><i class="fa fa-area-chart"></i>Monitoring</a></li>
                    <li><a href="page.php?p=fcm2"><i class="fa fa-area-chart"></i>Monitoring 2</a></li>
                </ul>
            </li> -->
           <li><a href="page.php?p=fcm2"><i class="fa fa-credit-card"></i> <span>Fleet Card</span></a></li>
         

         <!--    <li><a href="page.php?p=au"><i class="fa fa-car"></i> <span>Available Units</span></a></li>
          -->   <li><a href="page.php?p=vus"><i class="fa fa-car"></i> <span>Vehicle Units</span></a></li>


            <li class="header">MAINTENANCE</li>
            <li><a href="page.php?p=av"><i class="fa fa-car"></i> <span>Vehicles</span></a></li>
            <li><a href="page.php?p=drv"><i class="fa fa-user"></i> <span>Drivers</span></a></li>
            <li><a href="page.php?p=dest"><i class="fa fa-map-marker"></i> <span>Destination</span></a></li>
            <li><a href="page.php?p=ufc"><i class="fa fa-map-marker"></i> <span>Update Fleet Card</span></a></li>
              
            
            <li class="header">TOOLS</li>
            <li><a href="page.php?p=bgen"><i class="fa fa-barcode"></i> <span>Barcode Generator</span></a></li>
            <li><a href="pages/print_managers_list.php"><i class="fa fa-users"></i> <span>Manager's List</span></a></li>
            <!-- <li><a href="page.php?p=ep"><i class="fa fa-credit-card"></i> <span>E-Pass</span></a></li> -->
        <!--     <li class="treeview">
                <a href="#">
                    <i class="fa fa-credit-card"></i> <span>E-pass</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=epl"><i class="fa fa-list"></i>List of E-pass</a></li>
                    <li><a href="page.php?p=epm"><i class="fa fa-line-chart"></i>Monitoring</a></li>
                </ul>
            </li>  -->
            <!-- <li><a href="page.php?p=fc"><i class="fa fa-credit-card-alt"></i> <span>Fleet Card</span></a></li> -->
           <!--  <li class="treeview">
                <a href="#">
                    <i class="fa fa-credit-card"></i> <span>Fleet Card</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="page.php?p=fcl"><i class="fa fa-list-alt"></i>List of Fleet Card</a></li>
                    <li><a href="page.php?p=fcm"><i class="fa fa-area-chart"></i>Monitoring</a></li>
                </ul>
            </li>  -->
        <?php
            }
            else if($user_access['user_type'] == "ReadOnly" && $user_data->employee_id == 536) {
        ?>    
            <li class="header">TRIP TICKET</li>
            <li><a href="page.php?p=vt"><i class="fa fa-book"></i> <span>All Trip Tickets</span></a></li> 
            <li><a href="page.php?p=vcl2"><i class="fa fa-list-alt"></i> <span>Checklist</span></a></li>
            <li><a href="page.php?p=rtl&c=1"><i class="fa fa-plus-circle"></i> <span>New Log - With Trip Ticket</span></a></li>
            <li><a href="page.php?p=vccl"><i class="fa fa-clock-o"></i> View Logs</a></li>
       <!--      <li><a href="page.php?p=vccl"><i class="fa fa-clock-o"></i> <span>View Logs - With Trip Ticket</span></a></li> -->
            <li class="header">CAR PLAN</li>
            <li><a href="page.php?p=rtl&c=2"><i class="fa fa-plus-circle"></i> <span>New Log - Car Plan</span></a></li>
            <li><a href="page.php?p=vcpl"><i class="fa fa-clock-o"></i> View Logs</a></li>
             <li><a href="page.php?p=rma"><i class="fa fa-plus-circle"></i> <span>Manager's Attendance</span></a></li>
             
            <li class="header">ERRAND</li>
            <li><a href="page.php?p=rertl&c=3"><i class="fa fa-plus-circle"></i> New Log</a></li>
            <li><a href="page.php?p=vertl"><i class="fa fa-clock-o"></i> View Logs</a></li>
                
            <li class="header">EXPATS</li>
            <li><a href="page.php?p=rextl&c=4"><i class="fa fa-plus-circle"></i> New Log</a></li>
            <li><a href="page.php?p=vextl"><i class="fa fa-clock-o"></i> View Logs</a></li>
                

          <!--   <li><a href="page.php?p=vcpl"><i class="fa fa-clock-o"></i> <span>View Logs - Car Plan</span></a></li> -->
           
            <li class="header">MAINTENANCE</li>
            <li><a href="page.php?p=av"><i class="fa fa-car"></i> <span>Vehicles</span></a></li>
            
            <li class="header">TOOLS</li>
            <li><a href="pages/print_managers_list.php"><i class="fa fa-users"></i> <span>Manager's List</span></a></li>
        <?php
            }
            else if($user_access['user_type'] == "ReadOnly" && $user_data->employee_id == 391) {
        ?>
            <li><a href="page.php?p=vt"><i class="fa fa-book"></i> <span>All Trip Tickets</span></a></li> 
        <?php 
            }
            else if($user_access['user_type'] == "Regular" && $user_data->employee_id == 1259) {
        ?>
                <li><a href="page.php?p=vcl2"><i class="fa fa-list-alt"></i> <span>Checklist</span></a></li>
        <?php
            }
        ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>