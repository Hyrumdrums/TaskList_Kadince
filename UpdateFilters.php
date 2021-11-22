<?php
	//
	// Update session vars for taskList filters user wants to see
	// Begin by clearing both
	//
	require('Autoloader.php');
	Parameters::Clear('ShowPending');
	Parameters::Clear('ShowComplete');
	$filterList = GetPost('Filters');
	if(is_array($filterList))
	{
		foreach($filterList as $filter)
		{
			switch($filter)
			{
				case 'Pending':
				case 'Complete':
					$name = "Show$filter";
					Parameters::Set($name, true);
					break;
			}
		}
	}
	Route::To('index', 'TaskList');
?>