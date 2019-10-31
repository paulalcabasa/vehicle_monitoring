<?php 
class Vehicle {

    public function getVehicles(){
        $conn = Connection::getInstance();
/*      $sql = "SELECT a.id,
                       a.cs_no,
                       a.plate_no,
                       b.trip_status_id 
                FROM sys_insurance_and_registration.iar_company_car_units a 
                    LEFT JOIN
                     sys_vehicle_monitoring.trip_ticket b 
                     ON a.id = b.vehicle_id
                WHERE a.is_available = 1 AND b.id IS NULL";*/
        $sql = "SELECT a.id,
                       a.cs_no,
                       a.plate_no,
                       a.classification,
                       b.model,
                       b.body_color
                FROM sys_insurance_and_registration.iar_company_car_units a
                    LEFT JOIN sys_insurance_and_registration.iar_vehicle_master b
                        ON a.cs_no = b.cs_no";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function getAllAvailableUnits($id)
    {
        $where = '';
        if(!empty($id))
        {
            $where = "WHERE classification_id = " . $id;
        }
        $conn = Connection::getInstance();
        $sql = "SELECT 
                    id,
                    assignee,
                    cs_no,
                    plate_no,
                    classification,
                    model
                FROM v_available_units
                ".$where."";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function getAllAvailableUnitsbyclassification($id)
    {
        $where = '';
        if(!empty($id))
        {
            $where = "WHERE classification_id = " . $id;
        }
        $conn = Connection::getInstance();
        $sql = "SELECT 
                    id,
                    assignee,
                    cs_no,
                    plate_no,
                    classification,
                    model
                FROM v_available_units
                ".$where."";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function getClassification()
    {
        $conn = Connection::getInstance();
        $sql = "SELECT *
                FROM log_classification
                WHERE id IN (1,3)";
        $result = $conn->query($sql,false);
        return $result;
    }
    
    public function getCarPlanDetails($employee_no){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id orig_id,a.id unit_id,a.cs_no,a.plate_no,c.classification,b.model,b.body_color,a.assignee,a.department,a.section  
                    FROM sys_insurance_and_registration.iar_company_car_units a 
                    INNER JOIN sys_insurance_and_registration.iar_vehicle_master b ON a.cs_no = b.cs_no
                    INNER JOIN sys_insurance_and_registration.iar_company_car_classification c  ON a.classification = c.id
                WHERE a.employee_no = :employee_no LIMIT 1";
        $result = $conn->query($sql,array(":employee_no" => $employee_no));
        if(!empty($result)){
            $result[0]["unit_id"] = Format::formatVehicleId($result[0]["unit_id"]);
        }
        return $result;
    }

    /*public function getVehicleDetails($vehicle_id){
        $conn = Connection::getInstance();

        $sql = "SELECT a.id orig_id,a.id unit_id,a.cs_no,a.plate_no,c.classification,b.model,b.body_color,a.assignee,a.department,a.section  
                    FROM sys_insurance_and_registration.iar_company_car_units a 
                    INNER JOIN sys_insurance_and_registration.iar_vehicle_master b ON a.cs_no = b.cs_no
                    INNER JOIN sys_insurance_and_registration.iar_company_car_classification c  ON a.classification = c.id
                WHERE a.id = :vehicle_id LIMIT 1";
        $result = $conn->query($sql,array(":vehicle_id" => $vehicle_id));
        if(!empty($result)){
            $result[0]["unit_id"] = Format::formatVehicleId($result[0]["unit_id"]);
        }
        return (object)$result[0];
    }*/

    public function getVehicleDetails($vehicle_id){
        $conn = Connection::getInstance();

        $sql = "SELECT a.id orig_id,
                       a.id unit_id,
                       a.cs_no,
                       a.plate_no,
                       d.classification,
                       c.model,
                       c.body_color,
                       a.employee_id,
                       a.assignee,
                       a.employee_no,
                       a.department,
                       a.section,
                       a.classification vehicle_class_id,
                       FormatName(e.first_name,e.middle_name,e.last_name) assignee_name
                FROM sys_insurance_and_registration.iar_company_car_units a
                    LEFT JOIN ipc_central.employee_masterfile_tab b 
                        ON a.employee_id = b.id
                    LEFT JOIN sys_insurance_and_registration.iar_vehicle_master c
                        ON c.cs_no = a.cs_no
                    LEFT JOIN sys_insurance_and_registration.iar_company_car_classification d
                        ON d.id = a.classification
                    LEFT JOIN ipc_central.personal_information_tab e 
                       ON e.employee_id = a.employee_id 
                WHERE a.id = :vehicle_id LIMIT 1";
        $result = $conn->query($sql,array(":vehicle_id" => $vehicle_id));
        if(!empty($result)){
            $result[0]["unit_id"] = Format::formatVehicleId($result[0]["unit_id"]);
        }
        return !empty($result) ? (object)$result[0] : $result;
    }
    
    public function getFuelStatusList(){
        $conn = Connection::getInstance();
        $sql = "SELECT * FROM fuel_status";
        $results = $conn->query($sql,false);
        return $results;
    }

    public function getCarPlanList(){
        $conn = Connection::getInstance();
        // 2 is the classification of car plan in the insurance and registration
        $sql = "SELECT * FROM sys_insurance_and_registration.iar_company_car_units WHERE classification = 2 ORDER BY assignee ASC";
        $results = $conn->query($sql,false);
        return $results;
    }

    public function getManagersAttendanceByDate($start_date,$end_date,$employee_id){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id,
                       a.employee_id,
                       a.employee_no,
                       a.remarks,
                       DATE(a.log_time) log_date,
                       MIN(a.log_time) time_in,
                       CASE WHEN COUNT(a.id) <= 1 THEN NULL ELSE MAX(a.log_time) END AS time_out
                FROM managers_attendance a
                WHERE a.employee_id = :employee_id AND (DATE(a.log_time) BETWEEN :start_date AND :end_date)
                GROUP BY DATE(a.log_time)";
        $results = $conn->query($sql,array(
                                    ":employee_id" => $employee_id,
                                    ":start_date"  => $start_date,
                                    ":end_date"    => $end_date
                               )
                    );
        return $results;    
    }

    public function getVehicleUsageReport($start_date,$end_date,$vehicle_id,$driver_no){
        $conn = Connection::getInstance();
        $sql = "";
        $params = array();
        //CheckTripStatus(d.expected_time_of_return,MAX(CASE a.log_type_id WHEN 1 THEN a.log_time ELSE NULL END)) trip_status
        $select_clause = "SELECT a.trip_ticket_no,
                            c.cs_no,
                            c.plate_no,
                            d.expected_time_of_return,
                            GetDriverName(a.driver_no) driver,
                            MAX(CASE a.log_type_id WHEN 1 THEN a.log_time ELSE NULL END) time_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN a.log_time ELSE NULL END) time_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN e.status ELSE NULL END) fuel_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN e.status ELSE NULL END) fuel_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN a.km_reading ELSE NULL END) km_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN a.km_reading ELSE NULL END) km_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN a.remarks ELSE NULL END) remarks_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN a.remarks ELSE NULL END) remarks_out,
                            COUNT(CASE a.log_type_id WHEN 1 THEN b.id ELSE NULL END) passengers_in_count,
                            COUNT(CASE a.log_type_id WHEN 2 THEN b.id ELSE NULL END) passengers_out_count,
                            GROUP_CONCAT(CASE a.log_type_id WHEN 1 THEN b.passenger_name ELSE NULL END SEPARATOR ';') passengers_in,
                            GROUP_CONCAT(CASE a.log_type_id WHEN 2 THEN b.passenger_name ELSE NULL END SEPARATOR ';') passengers_out,
                            CONCAT(
                                f.status,
                                CheckOverdue(
                                    MIN(CASE a.log_type_id WHEN 1 THEN a.log_time ELSE NULL END),
                                    MAX(CASE a.log_type_id WHEN 2 THEN a.log_time ELSE NULL END),
                                    d.expected_time_of_return
                                )
                            ) trip_status
                        FROM time_log a LEFT JOIN time_log_passenger b
                                ON a.id = b.time_log_id
                        LEFT JOIN sys_insurance_and_registration.iar_company_car_units c
                            ON a.vehicle_id = c.id 
                        LEFT JOIN trip_ticket d
                            ON d.id = a.trip_ticket_no
                        LEFT JOIN fuel_status e 
                            ON e.id = a.fuel_status_id
                        LEFT JOIN trip_status f
                            ON f.id = d.trip_status_id ";
        if($start_date != "" AND $end_date != "" AND $vehicle_id == "" AND $driver_no == ""){ // if date only
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date
                      );
        }
        else if($start_date == "" AND $end_date == "" AND $vehicle_id != "" AND $driver_no == ""){ // if vehicle only
            $sql = "WHERE a.vehicle_id = :vehicle_id AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":vehicle_id"=>$vehicle_id
                      );
        }
        else if($start_date == "" AND $end_date == "" AND $vehicle_id == "" AND $driver_no != ""){ // if driver only
            $sql = "WHERE a.driver_no = :driver_no AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":driver_no"=>$driver_no
                      );
        }
        else if($start_date != "" AND $end_date != "" AND $vehicle_id != "" AND $driver_no == ""){ // if date and vehicle 
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) AND a.vehicle_id = :vehicle_id AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date,
                        ":vehicle_id"=>$vehicle_id
                      );
        }
        else if($start_date != "" AND $end_date != "" AND $vehicle_id == "" AND $driver_no != ""){ // if date and driver 
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) AND a.driver_no = :driver_no AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date,
                        ":driver_no"=>$driver_no
                     );
        }
        else if($start_date != "" AND $end_date != "" AND $vehicle_id != "" AND $driver_no != ""){ // if date, vehicle and driver 
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) 
                    AND a.driver_no = :driver_no 
                    AND a.vehicle_id = :vehicle_id 
                    AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date,
                        ":vehicle_id"=>$vehicle_id,
                        ":driver_no"=>$driver_no
                      );

        }
        else if($start_date == "" AND $end_date == "" AND $vehicle_id != "" AND $driver_no != ""){ // if Vehicle and driver 
            $sql = "WHERE a.driver_no = :driver_no AND a.vehicle_id = :vehicle_id 
                    AND a.log_classification_id = 1
                    GROUP BY a.trip_ticket_no";
            $params = array(
                        ":vehicle_id"=>$vehicle_id,
                        ":driver_no"=>$driver_no
                      );

        }

        $result = $conn->query($select_clause . $sql,$params);

        return $result;
    }

    public function getVehicleUsageReportCarplan($start_date,$end_date,$vehicle_id,$driver_no){
        $conn = Connection::getInstance();
        $sql = "";
        $params = array();

        $select_clause = "SELECT  a.id,
                            b.cs_no,
                            b.plate_no,
                            a.driver_no,
                            GetDriverName(a.driver_no) driver,
                            COUNT(CASE a.log_type_id WHEN 1 THEN c.id ELSE NULL END) passengers_in_count,
                            COUNT(CASE a.log_type_id WHEN 2 THEN c.id ELSE NULL END) passengers_out_count,
                            GROUP_CONCAT(CASE a.log_type_id WHEN 1 THEN c.passenger_name ELSE NULL END SEPARATOR ';') passengers_in,
                            GROUP_CONCAT(CASE a.log_type_id WHEN 2 THEN c.passenger_name ELSE NULL END SEPARATOR ';') passengers_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN a.log_time ELSE NULL END) time_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN a.log_time ELSE NULL END) time_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN a.km_reading ELSE NULL END) km_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN a.km_reading ELSE NULL END) km_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN d.status ELSE NULL END) fuel_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN d.status ELSE NULL END) fuel_out,
                            MAX(CASE a.log_type_id WHEN 1 THEN a.remarks ELSE NULL END) remarks_in,
                            MAX(CASE a.log_type_id WHEN 2 THEN a.remarks ELSE NULL END) remarks_out
                        FROM time_log a LEFT JOIN sys_insurance_and_registration.iar_company_car_units b
                        ON a.vehicle_id = b.id
                        LEFT JOIN time_log_passenger c ON c.time_log_id = a.id
                        LEFT JOIN fuel_status d ON d.id = a.fuel_status_id ";
        if($start_date != "" AND $end_date != "" AND $vehicle_id == "" AND $driver_no == ""){ // if date only
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date
                      );
        }
        else if($start_date == "" AND $end_date == "" AND $vehicle_id != "" AND $driver_no == ""){ // if vehicle only
            $sql = "WHERE a.vehicle_id = :vehicle_id AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":vehicle_id"=>$vehicle_id
                      );
        }
        else if($start_date == "" AND $end_date == "" AND $vehicle_id == "" AND $driver_no != ""){ // if driver only
            $sql = "WHERE a.driver_no = :driver_no AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":driver_no"=>$driver_no
                      );
        }
        else if($start_date != "" AND $end_date != "" AND $vehicle_id != "" AND $driver_no == ""){ // if date and vehicle 
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) AND a.vehicle_id = :vehicle_id AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date,
                        ":vehicle_id"=>$vehicle_id
                      );
        }
        else if($start_date != "" AND $end_date != "" AND $vehicle_id == "" AND $driver_no != ""){ // if date and driver 
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) AND a.driver_no = :driver_no AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date,
                        ":driver_no"=>$driver_no
                     );
        }
        else if($start_date != "" AND $end_date != "" AND $vehicle_id != "" AND $driver_no != ""){ // if date, vehicle and driver 
            $sql = "WHERE (a.log_time BETWEEN :start_date AND :end_date) 
                    AND a.driver_no = :driver_no 
                    AND a.vehicle_id = :vehicle_id 
                    AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":start_date"=>$start_date,
                        ":end_date"=>$end_date,
                        ":vehicle_id"=>$vehicle_id,
                        ":driver_no"=>$driver_no
                      );

        }
        else if($start_date == "" AND $end_date == "" AND $vehicle_id != "" AND $driver_no != ""){ // if Vehicle and driver 
            $sql = "WHERE a.driver_no = :driver_no AND a.vehicle_id = :vehicle_id 
                    AND a.log_classification_id = 2
                    GROUP BY DATE(a.log_time)";
            $params = array(
                        ":vehicle_id"=>$vehicle_id,
                        ":driver_no"=>$driver_no
                      );

        }

        $result = $conn->query($select_clause . $sql,$params);

        return $result;
    }

    public function getLastVehicleLog($vehicle_id){
        $conn = Connection::getInstance();
        $sql = "SELECT id,
                       log_type_id 
                FROM time_log
                WHERE vehicle_id = :id
                ORDER BY log_time DESC
                LIMIT 1 ";
        $result = $conn->query($sql,array(":id"=>$vehicle_id));
        return (!empty($result) ? (object)$result[0] : $result);
    }

    public function getVehicleChecklist(){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id,
                       a.cs_no,
                       a.plate_no,
                       b.model,
                       a.assignee,
                       a.is_available
                FROM sys_insurance_and_registration.iar_company_car_units a LEFT JOIN 
                     sys_insurance_and_registration.iar_vehicle_master b
                       ON a.cs_no = b.cs_no
                WHERE classification <> 2";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function updateVehicleStatus($vehicle_id,$status){
        $conn = Connection::getInstance();
        $sql = "UPDATE sys_insurance_and_registration.iar_company_car_units SET is_available = :is_available
                WHERE id = :vehicle_id";
        $result = $conn->query($sql,array(":is_available"=>$status,":vehicle_id"=>$vehicle_id));
        return $result;
    }

    public function countOnGoingTripBasedOnEtr($vehicle_id,$etr){
        $conn = Connection::getInstance();
        $sql = "SELECT COUNT(id) trip_count 
                FROM trip_ticket 
                WHERE vehicle_id = :vehicle_id AND expected_time_of_return >= :etr
                      and trip_status_id = 1";
        $result = $conn->query($sql,array(
                                        ":vehicle_id" => $vehicle_id,
                                        ":etr"        => $etr
                                    )
                                );
        $data = (object)$result[0];
        return $data->trip_count;
    }

    public function countOpenTrips($vehicle_id){
        $conn = Connection::getInstance();
        $sql = "SELECT COUNT(id) trip_count 
                FROM trip_ticket 
                WHERE vehicle_id = :vehicle_id AND (trip_status_id = 1 OR trip_status_id IS NULL)";
        $result = $conn->query($sql,array(
                                        ":vehicle_id" => $vehicle_id
                                        
                                    )
                                );
        $data = (object)$result[0];
        return $data->trip_count;
    }

    public function getAllVehicles(){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id,
                       a.cs_no,
                       a.plate_no,
                       a.assignee,
                       c.classification,
                       b.model,
                       sys_vehicle_monitoring.FormatName(d.first_name,d.middle_name,d.last_name) emp_name
                FROM sys_insurance_and_registration.iar_company_car_units a
                LEFT JOIN sys_insurance_and_registration.iar_vehicle_master b ON a.cs_no = b.cs_no
                LEFT JOIN sys_insurance_and_registration.iar_company_car_classification c ON c.id = a.classification
                LEFT JOIN ipc_central.personal_information_tab d 
                    ON d.employee_id = a.employee_id
                    AND a.employee_id <> 0
                WHERE a.status IN (1,2) AND a.cs_no IS NOT NULL AND a.cs_no <> ''
                ORDER BY a.id";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function addRemarks($vehicle_id,$remarks,$user){
        $conn = Connection::getInstance();
        $sql = "INSERT INTO vehicle_checklist_remarks(vehicle_id,remarks,create_user,date_created)
                VALUES(:vehicle_id,:remarks,:create_user,NOW())";
        $result = $conn->query($sql,array(
                                        ":vehicle_id" => $vehicle_id,
                                        ":remarks" => $remarks,
                                        ":create_user" => $user
                                    )
                              );
    }

    public function getOpenTripsBasedOnEtr($vehicle_id){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id trip_ticket_no,
                       a.destination,
                       a.expected_time_of_return etr,
                       a.purpose
                FROM trip_ticket a 
                WHERE a.vehicle_id = :vehicle_id AND (a.trip_status_id = 1 OR a.trip_status_id IS NULL)";
        $result = $conn->query($sql,array(
                                        ":vehicle_id" => $vehicle_id
                                        
                                    )
                                );
        return $result;
    }

    public function getVehicleCondition(){
        $conn = Connection::getInstance();
        $sql = "SELECT id,
                       description 
                FROM vehicle_condition
                ORDER BY sequence ASC";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function addVehicleCheckList($vehicle_id,$vehicle_condition,$user){
        $conn = Connection::getInstance();
        $sql = "INSERT INTO vehicle_checklist(vehicle_id,vehicle_condition_id,create_user,date_created)
                VALUES(:vehicle_id,:vehicle_condition_id,:create_user,NOW())";
        $result = $conn->query($sql,array(
                                        ":vehicle_id" => $vehicle_id,
                                        ":vehicle_condition_id" => $vehicle_condition,
                                        ":create_user" => $user
                                    )
                               );
        return $result;
    }

    public function addVehicleChecklistAttachment($checklist_id,$attachment){
        $conn = Connection::getInstance();
        $sql = "INSERT INTO vehicle_checklist_attachment(vehicle_checklist_id,attachment)
                VALUES(:vehicle_checklist_id,:attachment)";
        $result = $conn->query($sql,array(
                                        ":vehicle_checklist_id" => $checklist_id,
                                        ":attachment" => $attachment
                                    )
                              );
    }

    public function getChecklistAttachments($checklist_id){
        $conn = Connection::getInstance();
        $sql = "SELECT id,attachment FROM vehicle_checklist_attachment WHERE vehicle_checklist_id = :id";
        $result = $conn->query($sql,array(":id"=>$checklist_id));
        return $result;
    }

    public function getRecentCheckList($vehicle_id,$limit){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id,
                       a.vehicle_id,
                       a.vehicle_condition_id,
                       b.description v_cond,
                       c.status checklist_status,
                       a.vehicle_checklist_status_id,
                       a.date_created,
                       FormatName(d.first_name,d.middle_name,d.last_name) created_by
                FROM vehicle_checklist a 
                     LEFT JOIN vehicle_condition b
                        ON a.vehicle_condition_id = b.id
                     LEFT JOIN vehicle_checklist_status c
                        ON c.id = a.vehicle_checklist_status_id
                     LEFT JOIN ipc_central.personal_information_tab d
                        ON d.employee_id = a.create_user 
                WHERE a.vehicle_id = :vehicle_id AND a.vehicle_checklist_status_id = 1 AND a.vehicle_condition_id = 1
                ORDER BY a.date_created DESC
                LIMIT $limit";
        $result = $conn->query($sql,array(":vehicle_id"=>$vehicle_id));
        return $result;
    }

    public function getAllRecentChecklist($vehicle_id,$limit){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id,
                       a.vehicle_id,
                       a.vehicle_condition_id,
                       b.description v_cond,
                       c.status checklist_status,
                       a.vehicle_checklist_status_id,
                       a.date_created,
                       FormatName(d.first_name,d.middle_name,d.last_name) created_by
                FROM vehicle_checklist a 
                     LEFT JOIN vehicle_condition b
                        ON a.vehicle_condition_id = b.id
                     LEFT JOIN vehicle_checklist_status c
                        ON c.id = a.vehicle_checklist_status_id
                     LEFT JOIN ipc_central.personal_information_tab d
                        ON d.employee_id = a.create_user 
                WHERE a.vehicle_id = :vehicle_id 
                ORDER BY a.date_created DESC
                LIMIT $limit";
        $result = $conn->query($sql,array(":vehicle_id"=>$vehicle_id));
        return $result;
    }

    public function getChecklistDetails($checklist_id){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id,
                       a.vehicle_condition_id,
                       a.vehicle_checklist_status_id,
                       a.date_created,
                       b.description,
                       c.status
                FROM vehicle_checklist a 
                     LEFT JOIN vehicle_condition b 
                        ON a.vehicle_condition_id = b.id
                     LEFT JOIN vehicle_checklist_status c 
                        ON c.id = a.vehicle_checklist_status_id
                WHERE a.id = :id";
        $result = $conn->query($sql,array(":id"=>$checklist_id));

        return empty($result[0]) ? null  : (object)$result[0];
    }

    public function getChecklistDetails2($checklist_id){
        $conn = Connection::getInstance();
        $sql = "SELECT vch.id,
                       vch.vehicle_condition_id,
                       vch.date_created,
                       vc.description
                FROM vehicle_checklist_header vch 
                     LEFT JOIN vehicle_condition vc 
                        ON vch.vehicle_condition_id = vc.id
                WHERE vch.id = :id";
        $result = $conn->query($sql,array(":id"=>$checklist_id));

        return empty($result[0]) ? null  : (object)$result[0];
    }


    public function getVehicleUnits($inside_ipc_flag,$outside_ipc_flag,$available_only_flag,$classification){
        $condition = "";

        $class_filter = "";
        if($classification != ""){
            $class_filter = "AND class.id = " . $classification;
        }

        if(($inside_ipc_flag && !$outside_ipc_flag)){
            $condition .= "AND LOWER(lt.type) IN ('in')";
        }
        else if(($outside_ipc_flag && !$inside_ipc_flag)){
            $condition .= "AND LOWER(lt.type) IN ('out')";
        }
        else if ($inside_ipc_flag && $outside_ipc_flag){
            $condition .= "AND LOWER(lt.type) IN ('in','out')";
        }
        else if(($available_only_flag)){
            $condition .= "AND LOWER(lt.type) = 'in' 
                          AND tt.id IS NULL";
        }

        $conn = Connection::getInstance();
        $sql = "SELECT vehicle_list.*,
                       tl.driver_name,
                       lt.type,
                       DATE_FORMAT(tl.log_time,'%m/%d/%Y %h:%i %p') log_time,
                       tt.id trip_ticket_no,
                       ts.status,
                       DATE_FORMAT(tt.expected_time_of_return,'%m/%d/%Y %h:%i %p') etr
                FROM (
                     SELECT    
                        car_units.id,
                        car_units.cs_no,
                        car_units.plate_no,
                        car_units.assignee,
                        vehicle.model,
                        class.classification,
                        st.name STATUS,
                        (SELECT tl.id
                            FROM sys_vehicle_monitoring.time_log tl
                            WHERE tl.vehicle_id = car_units.id  
                        ORDER BY tl.id DESC
                        LIMIT 1
                        ) last_log_id
                     FROM sys_insurance_and_registration.iar_company_car_units car_units
                        INNER JOIN sys_insurance_and_registration.iar_vehicle_master vehicle
                            ON car_units.cs_no = vehicle.cs_no
                        LEFT JOIN sys_insurance_and_registration.iar_company_car_classification class
                            ON class.id = car_units.classification
                        LEFT JOIN sys_insurance_and_registration.status st
                            ON st.id = car_units.status
                    WHERE 1 = 1
                          AND st.name IN ('Pending','Active')
                          {$class_filter}
                          AND LOWER(class.classification) NOT IN ('carplan','executive service unit')
                    ) vehicle_list 
                INNER JOIN sys_vehicle_monitoring.time_log tl
                    ON tl.id = vehicle_list.last_log_id
                INNER JOIN sys_vehicle_monitoring.log_classification lc
                    ON lc.id = tl.log_classification_id
                INNER JOIN sys_vehicle_monitoring.log_type lt
                    ON lt.id = tl.log_type_id
                LEFT JOIN sys_vehicle_monitoring.trip_ticket tt
                    ON tt.id = tl.trip_ticket_no
                    AND tt.trip_status_id = 1
                LEFT JOIN sys_vehicle_monitoring.trip_status ts
                    ON ts.id = tt.trip_status_id
                WHERE 1 = 1
                      {$condition}
                ORDER BY tl.log_time DESC";

        $result = $conn->query($sql);
        return $result;
    }

    public function getVehicleClassifications(){
        $conn = Connection::getInstance();
        $sql = "SELECT id,
                       classification
                FROM sys_insurance_and_registration.iar_company_car_classification
                WHERE classification NOT IN ('Carplan','Executive Service Unit','Executive Family Unit')
                ORDER BY classification ASC";
        $result = $conn->query($sql);
        return $result;
    }

    public function getChecklistCategories(){
        $conn = Connection::getInstance();
        $sql = "SELECT id,
                       category
                FROM sys_vehicle_monitoring.checklist_category
                ORDER BY sequence ASC";
        $result = $conn->query($sql);
        return $result;
    }

    public function getChecklistItems($category_id){
        $conn = Connection::getInstance();
        $sql = "SELECT id,
                       description
                FROM checklist_items 
                WHERE checklist_category_id = :category_id
                ORDER BY sequence";
        $result = $conn->query($sql,
                               array(":category_id" => $category_id));
        return $result;
    }

    public function getCompanyVehicles(){
        $conn = Connection::getInstance();
        $sql = "SELECT
                        a.id,
                        a.cs_no,
                        a.plate_no,
                        b.model,
                        a.assignee 
                FROM sys_insurance_and_registration.iar_company_car_units a
                    LEFT JOIN sys_insurance_and_registration.iar_vehicle_master b
                        ON a.cs_no = b.cs_no
                WHERE a.classification <> 2";
        $result = $conn->query($sql);
        return $result;
    }

    public function insert_vehicle_checklist_header($params){
        $conn = Connection::getInstance();
        $sql = "INSERT INTO vehicle_checklist_header (
                    vehicle_id,
                    km_reading_out,
                    km_reading_in,
                    body_defects_image,
                    remarks,
                    vehicle_condition_id,
                    create_user,
                    date_created
                )
                VALUES (
                    :vehicle_id,
                    :km_reading_out,
                    :km_reading_in,
                    :body_defects_image,
                    :remarks,
                    :vehicle_condition_id,
                    :create_user,
                    NOW()
                )";
        $vehicle_checklist_header_id = $conn->query($sql,$params);
        return $vehicle_checklist_header_id;
    }

    public function insert_vehicle_checklist_lines($params){
        $conn = Connection::getInstance();
        $sql = "INSERT INTO vehicle_checklist_lines (
                    vehicle_checklist_header_id,
                    category_id,
                    item_id,
                    item_status_id,
                    remarks,
                    date_repaired,
                    create_user,
                    date_created
                ) 
                VALUES (
                    :vehicle_checklist_header_id,
                    :category_id,
                    :item_id,
                    :item_status_id,
                    :remarks,
                    :date_repaired,
                    :create_user,
                    NOW()
                )";
        $conn->query($sql,$params);
    }

    public function get_checklist_per_vehicle($vehicle_id){
        $conn = Connection::getInstance();
        $sql = "SELECT vch.id checklist_id,
                       vch.km_reading_out,
                       vch.trip_ticket_no,
                       vch.km_reading_in,
                       vch.remarks,
                       vc.description vehicle_condition,
                       vch.date_created,
                       sys_vehicle_monitoring.FormatName(pit.first_name,pit.middle_name,pit.last_name) created_by
                FROM sys_vehicle_monitoring.vehicle_checklist_header vch
                     INNER JOIN sys_vehicle_monitoring.vehicle_condition vc
                    ON vch.vehicle_condition_id = vc.id
                     INNER JOIN ipc_central.personal_information_tab pit
                    ON pit.employee_id = vch.create_user
                WHERE vch.vehicle_id = :vehicle_id";
        $result = $conn->query($sql,array(":vehicle_id"=>$vehicle_id));
        return $result;
    }

    public function get_checklist_header_details($checklist_id){
        $conn = Connection::getInstance();
        $sql = "SELECT vch.id checklist_id,
                       vch.body_defects_image,
                       vch.km_reading_out,
                       vch.trip_ticket_no,
                       vch.km_reading_in,
                       vch.remarks,
                       vc.description vehicle_condition,
                       vc.id vehicle_condition_id,
                       DATE_FORMAT(vch.date_created,'%m/%d/%Y') date_created,
                       vch.vehicle_id,
                       sys_vehicle_monitoring.FormatName(pit.first_name,pit.middle_name,pit.last_name) created_by
                FROM sys_vehicle_monitoring.vehicle_checklist_header vch
                     INNER JOIN sys_vehicle_monitoring.vehicle_condition vc
                    ON vch.vehicle_condition_id = vc.id
                     INNER JOIN ipc_central.personal_information_tab pit
                    ON pit.employee_id = vch.create_user
                WHERE vch.id = :checklist_id";
        $result = $conn->query($sql,array(":checklist_id"=>$checklist_id));
        return (object)$result[0];
    }

    public function get_checklist_lines_per_category($params){
        $conn = Connection::getInstance();
        $sql = "SELECT vcl.id line_id,
                       vcl.vehicle_checklist_header_id,
                       ct.category,
                       ci.description,
                       vcl.item_status_id,
                       vcl.remarks,
                       DATE_FORMAT(vcl.date_repaired,'%m/%d/%Y %h:%i %p') date_repaired,
                       cs.name
                FROM sys_vehicle_monitoring.vehicle_checklist_lines vcl 
                     INNER JOIN sys_vehicle_monitoring.checklist_category ct
                        ON vcl.category_id = ct.id
                     INNER JOIN sys_vehicle_monitoring.checklist_items ci
                        ON ci.id = vcl.item_id
                     INNER JOIN sys_vehicle_monitoring.checklist_statuses cs
                        ON cs.id = vcl.item_status_id
                WHERE vcl.vehicle_checklist_header_id = :checklist_header_id 
                      AND vcl.category_id = :category_id
                ORDER BY ci.sequence ASC";
        $result = $conn->query($sql,$params);
        return $result;
    }

    public function update_checklist_header($km_reading_out,$km_reading_in,$overall_remarks,$overall_vehicle_condition,$employee_id,$checklist_id){
        $conn = Connection::getInstance();
        $sql = "UPDATE sys_vehicle_monitoring.vehicle_checklist_header
                SET km_reading_out = :km_reading_out,
                    km_reading_in = :km_reading_in,
                    remarks = :remarks,
                    vehicle_condition_id = :vehicle_condition_id,
                    update_user = :update_user
                WHERE id = :checklist_id";
        $conn->query($sql,array(
                            ":km_reading_out" => $km_reading_out,
                            ":km_reading_in" => $km_reading_in,
                            ":remarks" => $overall_remarks,
                            ":vehicle_condition_id" => $overall_vehicle_condition,
                            ":update_user" => $employee_id,
                            ":checklist_id" => $checklist_id
                          )
        );
    }

    public function update_checklist_lines($line_id,$status_id,$remarks,$date_repaired,$update_user){
        $conn = Connection::getInstance();
        $sql = "UPDATE sys_vehicle_monitoring.vehicle_checklist_lines
                SET item_status_id = :item_status_id,
                    remarks = :remarks,
                    date_repaired = :date_repaired,
                    update_user = :update_user,
                    date_updated = NOW()
                WHERE id = :line_id";
        $conn->query($sql,array(
                            ":item_status_id" => $status_id,
                            ":remarks" => $remarks,
                            ":date_repaired" => $date_repaired,
                            ":update_user" => $update_user,
                            ":line_id" => $line_id
                          )
                    );
    }

    public function update_checklist_image($checklist_id,$image,$update_user){
        $conn = Connection::getInstance();
        $sql = "UPDATE sys_vehicle_monitoring.vehicle_checklist_header vch
                SET body_defects_image = :image,
                    update_user = :update_user,
                    date_updated = NOW()
                WHERE id = :checklist_id";
        $conn->query($sql,array(
                            ":image" => $image,
                            ":update_user" => $update_user,
                            ":checklist_id" => $checklist_id
                          )
                    );
    }

   public function getOpenTripsBasedOnEtr2($vehicle_id){
        $conn = Connection::getInstance();
        $sql = "SELECT a.id trip_ticket_no,
                       a.destination,
                       a.expected_time_of_return etr,
                       a.purpose
                FROM trip_ticket a 
                WHERE a.vehicle_id = :vehicle_id AND (a.trip_status_id = 1 OR a.trip_status_id IS NULL)";
        $result = $conn->query($sql,array(
                                        ":vehicle_id" => $vehicle_id
                                        
                                    )
                                );
        return $result;
    }

     public function getVehicles2($expected_time_of_return,$is_reserved){
        $condition = "";
        if($is_reserved == "yes"){
            $condition = "AND tt.expected_time_of_return >= '" .$expected_time_of_return."'"; 
        }
        $conn = Connection::getInstance();
        $sql = "SELECT  vehicle.vehicle_id,
                        vehicle.cs_no,
                        vehicle.plate_no,
                        vehicle.classification,
                        vehicle.classification_name,
                        vehicle.remarks ccu_remarks,
                        vch.id checklist_id,
                        vch.remarks,
                        vc.description vehicle_condition,
                        DATE_FORMAT(vch.date_created,'%m/%d/%Y %h:%i %p') date_created
                FROM 
                (
                    SELECT ccu.id vehicle_id,
                           ccu.cs_no,
                           ccu.plate_no,
                           ccu.classification,
                           ccu.remarks,
                           car_class.classification classification_name,
                           ( SELECT id checklist_id
                             FROM sys_vehicle_monitoring.vehicle_checklist_header vch
                             WHERE vch.trip_ticket_no IS NULL
                                   AND vch.vehicle_id = ccu.id
                             ORDER BY id DESC
                             LIMIT 1
                            ) checklist_id,
                            ( SELECT COUNT(id) trip_count
                              FROM sys_vehicle_monitoring.trip_ticket tt
                              WHERE tt.vehicle_id = ccu.id 
                              AND (tt.trip_status_id = 1 OR tt.trip_status_id IS NULL)
                              {$condition}
                            ) trip_count
                    FROM sys_insurance_and_registration.iar_company_car_units ccu 
                         LEFT JOIN sys_insurance_and_registration.iar_company_car_classification car_class
                            ON car_class.id = ccu.classification  
                    WHERE ccu.status IN (1,2,3,4)
                ) vehicle 
                LEFT JOIN sys_vehicle_monitoring.vehicle_checklist_header vch
                    ON vch.id = vehicle.checklist_id
                LEFT JOIN sys_vehicle_monitoring.vehicle_condition vc
                    ON vc.id = vch.vehicle_condition_id";
        $result = $conn->query($sql,false);
        return $result;
    }

    public function get_errand_logs($start_date,$end_date,$vehicle_id){
        $conn = Connection::getInstance();
        $vehicle_id_cond = "";
        if($vehicle_id != ""){
            $vehicle_id_cond = "AND vehicle_id = " . $vehicle_id;
        }       
        $sql = "SELECT *
                FROM v_errand_time_logs 
                WHERE log_time BETWEEN :start_date AND :end_date
                      {$vehicle_id_cond}";
        $result = $conn->query($sql,array(
                                        ":start_date" => $start_date,
                                        ":end_date" => $end_date
                                        
                                    )
                                );
        return $result;
    }

    public function get_use_rfid_by_id($vehicle_id, $trip_ticket) {
        $conn = Connection::getInstance();
        $sql = "SELECT sys_vehicle_monitoring.vehicle_use_rfid.id AS id, vehicle_id, tag_number, transaction_date, entry_plaza, exit_plaza, sys_vehicle_monitoring.vehicle_use_rfid.amount AS amount, requested_date, update_date, update_user, 
CONCAT(last_name, ' ', first_name, ' ' , middle_name,'.') AS empname, 
COALESCE(NULLIF(plate_no,''), cs_no) AS plateno
            FROM
            sys_vehicle_monitoring.vehicle_use_rfid 
            LEFT JOIN
            sys_insurance_and_registration.iar_company_car_units
            ON 
            sys_vehicle_monitoring.vehicle_use_rfid.vehicle_id = sys_insurance_and_registration.iar_company_car_units.id
              LEFT JOIN
            ipc_central.personal_information_tab
            ON
            sys_vehicle_monitoring.vehicle_use_rfid.update_user = ipc_central.personal_information_tab.employee_id
                WHERE 
                vehicle_id = :vehicle_id
                and sys_vehicle_monitoring.vehicle_use_rfid.trip_ticket_no = :trip_ticket ";
        $result = $conn->query($sql, array(
                                        ":vehicle_id" => $vehicle_id,
                                        ":trip_ticket" => $trip_ticket
                                    )
                                );
        return $result;
    }

    public function add_vehicle_use_rfid($mdl_plate_no, $txt_tag_no, $transaction_date, $txt_entry_plaza, $txt_exit_plaza, $txt_amount, $trip_ticket, $user) {
        $conn = Connection::getInstance();
        $sql = "INSERT INTO vehicle_use_rfid
                (
                vehicle_id,
                tag_number,
                transaction_date,
                entry_plaza,
                exit_plaza,
                amount,
                requested_date,
                update_user,
                update_date,
                trip_ticket_no
                )
                VALUES
                (
                :mdl_plate_no,
                :txt_tag_no,
                :txt_transaction_date,
                :txt_entry_plaza,
                :txt_exit_plaza,
                :txt_amount,
                NOW(),
                :user,
                NOW(),
                :trip_ticket
                )
                ";
       $result = $conn->query($sql,array(
                            ":mdl_plate_no"        =>       $mdl_plate_no,
                            ":txt_tag_no"          =>       $txt_tag_no,
                            ":txt_transaction_date" =>      $transaction_date,
                            ":txt_entry_plaza"     =>       $txt_entry_plaza,
                            ":txt_exit_plaza"      =>       $txt_exit_plaza,
                            ":txt_amount"          =>       $txt_amount,
                            ":user"                =>       $user,
                            ":trip_ticket"         =>       $trip_ticket
                                )
                        );
        return $result;

    }


    public function update_vehicle_use_rfid($rfid, $tagno, $transactiondate, $entryplaza, $exitplaza, $amount, $user) {
        $conn = Connection::getInstance();
        $sql = "UPDATE vehicle_use_rfid set
                tag_number = :tagno,
                transaction_date = :transactiondate,
                entry_plaza = :entryplaza,
                exit_plaza = :exitplaza,
                amount = :amount,
                update_user = :user,
                update_date = NOW()
                WHERE
                id = :rfid
                ";
       $result = $conn->query($sql,array(
                            ":tagno"                =>       $tagno,
                            ":transactiondate"      =>       $transactiondate,
                            ":entryplaza"           =>       $entryplaza,
                            ":exitplaza"            =>       $exitplaza,
                            ":amount"               =>       $amount,
                            ":user"                 =>       $user,
                            ":rfid"                 =>       $rfid
                                )
                        );
        return $result;

    }


    public function delete_vehicle_use_rfid($rfid) {
        $conn = Connection::getInstance();
        $sql = "DELETE FROM vehicle_use_rfid  
                WHERE
                id = :rfid
                ";
       $result = $conn->query($sql,array(
                                ":rfid"                 =>       $rfid
                                )
                        );
        return $result;

    }















}
