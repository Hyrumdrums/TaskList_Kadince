<?php
	//
	// Save task
	//
	require('Autoloader.php');
	$task_Id = GetPost('Task_Id');
	$user_Id = User::GetUser_Id();
	$button = GetPost('submit');
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
			$desc = $task->Get('Description');
			$task->Delete();
			$msg = "Deleted '$desc'";
			break;
	}
	
	Route::To('index', 'TaskList', array('msg'=>$msg));
?>