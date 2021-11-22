<?php
	//
	// Attempt login and return to index
	//
	require('Autoloader.php');
	$username = GetPost('username');
	$password = GetPost('password');
	if(!$username || !$password) Route::To('index', '', array('Err'=>'Invalid username or password'));
	if(!User::Login($username, $password)) Route::To('index', '', array('Err'=>'Invalid username or password'));
	Route::To('index');
?>