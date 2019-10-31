<?php

class Format {

	public static function makeUppercase($str){
		return mb_convert_case($str, MB_CASE_TITLE, "utf8");
		//return strtolower($str);
	}

	public static function formatVehicleId($vehicle_id){
		$new_id =  sprintf('%05d', $vehicle_id);
		return "IPCCU" . $new_id;
	}

	public static function formatChecklistId($id){
		$new_id =  sprintf('%05d', $id);
		return "CL" . $new_id;
	}


	public static function formatDriverId($driver_id){
		$new_id =  sprintf('%05d', $driver_id);
		return "DRV" . $new_id;
	}

	public static function reformatDriverId($driver_no){
		if(substr_count($driver_no,'DRV') > 0){
			$new_id = str_replace("DRV",'',$driver_no);
		}
		if(substr_count($driver_no,'drv') > 0){
			$new_id = str_replace("drv",'',$driver_no);
		}
		$new_id = ltrim($new_id, '0');
		return $new_id;
	}

	public static function reformatVehicleId($vehicle_id){
		if(substr_count($vehicle_id,"IPCCU") > 0){
			$new_id = str_replace("IPCCU",'',$vehicle_id);
		}
		if(substr_count($vehicle_id,"ipccu") > 0){
			$new_id = str_replace("ipccu",'',$vehicle_id);
		}
		$new_id = ltrim($new_id, '0');
		return $new_id;
	}

	public static function format_date($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"F j, Y \a\\t g:i a");
		return $formatted_date;
	}

	public static function format_date_only($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"Y-m-d");
		return $formatted_date;
	}

	public static function format_time_only($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"H:i:s");
		return $formatted_date;
	}

	public static function format_12h_time($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"g:i a");
		return $formatted_date;
	}

	public static function format_date_with_day($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"F j, Y \( l \)");
		return $formatted_date;
	}

	public static function prettyPrint($arr){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}

	public static function format_readable_date_only($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"F j, Y");
		return $formatted_date;
	}


	public static function format_date_slash($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"m/d/Y g:i a");
		return $formatted_date;
	}

	public static function format_date_slash2($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"m/d/Y g:i A");
		return $formatted_date;
	}

	public static function format_date_slash3($date){
		$date_create = date_create($date);
		$formatted_date = date_format($date_create,"m/d/Y");
		return $formatted_date;
	}

	public static function formatBlank($value){
		return ($value != "" ? $value : 'N/A');
	}

	public static function utf8ize($mixed) {
	    if (is_array($mixed)) {
	        foreach ($mixed as $key => $value) {
	            $mixed[$key] = self::utf8ize($value);
	        }
	    } else if (is_string ($mixed)) {
	        return utf8_encode($mixed);
	    }
	    return $mixed;
	}

	public static function format_formal_name($first_name,$middle_name,$last_name){
		$name = "";
		$mname = ($middle_name != "" ? substr($middle_name,0,1) . "." : "");
		$name = $last_name . ", " . $first_name . " " . $mname;
		return $name;
	}

	public static function replaceInye($name){
		$name = str_replace("Ñ","N",$name);
	//	$name = str_replace("ñ","n",$name);
		return $name;
	}

	public static function getDateToday(){
		$now = new DateTime();
		$now->setTimezone(new DateTimeZone('Asia/Taipei'));
		$today = $now->format('Y-m-d');
		return $today;
	}

	public static function makeUppercase2($str){
		//return mb_convert_case($str, MB_CASE_TITLE, "utf8");
		return strtolower($str);
	}
}
