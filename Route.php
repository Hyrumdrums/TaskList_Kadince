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
		case 'DeleteTask':
			$task_Id = GetPost('Task_Id');
			header("Location: UpdateTask.php?Action=Delete&Task_Id=$task_Id");
			break;
		case 'CompleteTask':
			$task_Id = GetPost('Task_Id');
			header("Location: UpdateTask.php?Action=Complete&Task_Id=$task_Id");
			break;
		case 'Logout':
			User::Logout();
			Route::To('index');
			break;
		case 'NewUser':
			Route::To('index', 'NewUser');
			break;
		default:
			die('unknown action specified: ' . $action);
			break;
	}
?>