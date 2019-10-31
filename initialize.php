<?php
	session_start();
	require_once("config.php");

	// Function for autoloading classes in php
	spl_autoload_register(function ($class) {
		$class = strtolower($class);
	    include 'classes/class.' . $class . '.inc';
	});
