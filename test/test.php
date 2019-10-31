<?php

//$dbName = "\\\\ipcemc\\MIS\\_paul\\Test.accdb";
//$dbName = "\\\ipcsvs001\Parts\IFS Distribution Parts_BE.mdb";
//$dbName = "\\\ipcemc\MIS\_paul\\tnsnames.ora";
//$isFolder = is_dir("C:\\");
//var_dump($isFolder); //TRUE
//echo $dbName;
$dbName = "\\\ipcsvs001\database$\HRS\Travel Authorization_BE.mdb";
if (!file_exists($dbName)) {
    die("Could not find database file.");
}

$db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName; Uid=; Pwd=;");

$stmt = $db->query("SELECT * FROM [GSS Job Order]");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($result);