<?php

	// Error Reporting
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);

	include "admin/connect.php"; // Connect With DataBase

	$sessionUser = '';

	if(isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

	// Routes
	$tpl 	= 'includes/templates/'; // Template Directory
	$lang 	= 'includes/languages/'; // Language Directory
	$func 	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory

	// Include The Important Files
	include $func . 'functions.php';
	include $lang . 'en.php';
	include $tpl . 'header.php';

?>