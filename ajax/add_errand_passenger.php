<?php
require_once("../initialize.php");

$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$errand = new Errand();

$errand->addPassenger($post->log_id,$post->passenger_name);
