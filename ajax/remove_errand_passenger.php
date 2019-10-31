<?php
require_once("../initialize.php");

$post = (object)$_POST;
$user_data = (object)$_SESSION['user_data'];
$errand = new Errand();

$errand->removePassenger($post->passenger_id);