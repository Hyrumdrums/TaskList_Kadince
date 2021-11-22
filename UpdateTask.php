<?php
	//
	// Save task
	//
	require('Autoloader.php');
	$task_Id = GetPost('Task_Id');
	$user_Id = User::GetUser_Id();
	$button = GetPost('submit');
	//
	// piggy back for quick functions, expect action and task_Id from url
	//
	if(!$button)
	{
		$button = Get('Action');
		$task_Id = Get('Task_Id');
	}
	//
	// Load task
	//
	$task = new DataObject('Task', 'Task_Id');
	if($task_Id)
	{
		//
		// Load if exists, verify belongs to user
		//
		$task->Set('Task_Id', $task_Id);
		$task->Load();
		if($task->Get('User_Id') != $user_Id) die('Something went wrong');
	}
	$desc = $task->Get('Description');
	switch($button)
	{
		case 'Save':
			//
			// Get task details and validate
			//
			$desc = GetPost('Description');
			if(!$desc) die('Please enter a description');
			$status = GetPost('Status');
			$notes = GetPost('Notes');
			
			if($task->IsNewItem()) $task->Set('Task_Id', $task_Id);
			$task->Set('User_Id', $user_Id);
			$task->Set('Description', $desc);
			$task->Set('Status', $status);
			$task->Set('Notes', $notes);
			$task->Save();
			break;
		case 'Delete':
			$task->Delete();
			$msg = "Deleted '$desc'";
			break;
		case 'Complete':
			$desc = $task->Set('Status', 'Complete');
			$task->Save();
			$msg = "Competed '$desc'";
			break;
		case 'Cancel': // not used becaue of window.history.back(); on cancel button
			break;
	}
	
	Route::To('index', 'TaskList', array('msg'=>$msg));
?>