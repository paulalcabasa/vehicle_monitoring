<?php
include("../initialize.php");
$post = (object)$_POST;
$vehicle = new Vehicle();
$checklist =  $vehicle->getRecentCheckList($post->vehicle_id,10);
foreach($checklist as $c){
	$c = (object)$c;
	echo $c->id;
}