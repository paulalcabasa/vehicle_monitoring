      <header class="main-header">
        <!-- Logo -->
        <a href="../../main_home.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>IPC</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src='../../img/logo_2.png'/></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
             
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../../emp_pics/<?php echo $pic; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo Format::makeUppercase($emp_details->first_name. ' ' . $emp_details->last_name);?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../../emp_pics/<?php echo $pic; ?>" class="img-circle" alt="User Image">
                    <p style="line-height:10px;font-weight:bold;"><?php echo Format::makeUppercase($emp_details->first_name. ' ' . $emp_details->last_name);?></p>
                    <p style="font-size:14px;line-height:10px;"><?php echo Format::makeUppercase($emp_details->position);?></p>
                    <p style="font-size:14px;line-height:10px;"><?php echo Format::makeUppercase($emp_details->section);?></p>
                  </li>
                
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="../../main_profile.php" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="../../php_processors/proc_logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <!-- <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li> -->
            </ul>
          </div>
        </nav>
      </header>