<?php

	include "connect.php"; // Connect With DataBase

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

	//Include Navbar On All Pages Expect The One With $noNavbar Vairable
	if ( !isset($noNavbar) ) { include $tpl . 'navbar.php'; };

	

?>