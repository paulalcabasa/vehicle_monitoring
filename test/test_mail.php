<?php
	require_once("../initialize.php");


	$testEmail = new Email();
	$data = array(
		"requestor_email" => 'paul-alcabasa@isuzuphil.com'
	);

	$testEmail->send_test_mail($data);
	

