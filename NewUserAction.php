<?php
	//
	// Attempt create user, login, and return to index
	//
	require('Autoloader.php');
	$username = GetPost('username');
	$password = GetPost('password');
	$password2 = GetPost('password2');
	if($password1 <> $password2) Route::To('index', 'NewUser', array('Err'=>'passwords must match'));
	if(!$username || !$password) Route::To('index', 'NewUser', array('Err'=>'please enter username and password'));
	if(!User::Create($username, $password)) Route::To('NewUser', '', array('Err'=>'try a different username'));
	Route::To('index');
?>