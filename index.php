<?php
    session_start();
    require_once("config.php");
    require_once("../../classes/class.system.php");
    require_once("../../classes/class.main_conn.php");
    require_once("../../classes/class.employee_masterfile.php");
    $system = new System();
    $dbconn = new Main_Conn();
    $employee_masterfile = new Employee_Masterfile();
    $user_data = (object)$_SESSION['user_data'];
    $system->incrementSystemHits($_SESSION['user_data']['employee_id'],SYSTEM_ID);

    $user_access = $employee_masterfile->getUserAccess($_SESSION['user_data']['employee_id'],SYSTEM_ID);
    
    if($user_access['user_type'] != "Administrator" && $user_access['user_type'] != "SuperUser" && $user_access['user_type'] != "Regular"){
        header("location:../../main_home.php");
    }
    
    if($user_access['user_type'] == "Administrator" || $user_access['user_type'] == "SuperUser"){
        header("location:page.php?p=vt");
    }
    else if($user_access['user_type'] == "ReadOnly" && $user_data->employee_id == 536){ //SECURITY GUARD
         header("location:page.php?p=vt");
    }
    else if($user_access['user_type'] == "ReadOnly" && $user_data->employee_id == 526){ // ipc lobby id
        header("location:page.php?p=rma");
    }
    else if($user_access['user_type'] == "ReadOnly" && $user_data->employee_id == 391){ // ipc lobby id
        header("location:page.php?p=vt");
    }
    else if($user_access['user_type'] == "Regular" && $user_data->employee_id == 1259){ // corpuz
        header("location:page.php?p=vcl2");
    }
