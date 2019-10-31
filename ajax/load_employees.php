<?php
require_once("../initialize.php");
$driver = new Driver();
echo json_encode(array_column($driver->getEmployeesList(),"emp_name"));
