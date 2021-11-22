<?php
	//
	// take route param, validate and send to page
	//
	require('Autoloader.php');
	$action = GetPost('Action');
	switch($action)
	{
		case 'EditTask':
			$task_Id = GetPost('Task_Id');
			Route::To('index', $action, array('Task_Id'=>$task_Id));
			break;
		case 'Logout':
			User::Logout();
			Route::To('index');
			break;
		case 'NewUser':
			User::Logout();
			die('new user');
			Route::To('index', 'NewUser');
			break;
		default:
			die('unknown action specified: ' . $action);
			break;
	}
?>