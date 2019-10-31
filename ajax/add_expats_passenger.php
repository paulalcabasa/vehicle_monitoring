<?php
require_once("../initialize.php");

$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$expats = new Expats();

$expats->addPassenger($post->log_id,$post->passenger_name);
