<?php
class Constants {

    private static $trip_ticket_signatory = array(
                                                array(
                                                    "employee_id"   =>  156, // michelle
                                                    "sequence"      =>  1
                                                ), 
                                                array(
                                                    "employee_id"   =>  557, // honra
                                                    "sequence"      =>  1
                                                ),
    											array(
    												"employee_id"	=>	483,
    												"sequence"		=>	2
    											)
                                                /* 
                                                to remove Mam Imelda Bernas from signatory, updated by Paul as 
                                                of May 19, 2017
                                                ,
    											array(
    												"employee_id" 	=>  1,
    												"sequence"	 	=>	2
                                                ) */
                                                
                                                
    										);
    
    private static $approval_link = "http://172.16.1.34/ipc_central/gad-css/sys-vehicle-monitoring/pages/approve_trip_ticket.php?d=";

    public static function getTripTicketSignatory() {
        return self::$trip_ticket_signatory;
    }

    public static function getApprovalLink(){
    	return self::$approval_link;
    }
}
