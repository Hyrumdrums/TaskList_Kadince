<?php
	//
	// select tasks based on filters and user, list
	//
	$filterList = array();
	if(Parameters::Get('ShowPending')) $filterList[] = 'Pending';
	if(Parameters::Get('ShowComplete')) $filterList[] = 'Complete';
	$user_Id = User::GetUser_Id();
	$task = new Task();
	$taskList = $task->SelectByFilters($user_Id, $filterList);
<<<<<<< HEAD
	// $taskList = $task->Select();
	

	// $taskList = GetFakeTaskList();
	$msg = Parameters::Pull('msg');
=======
	$msg = Parameters::Pull('msg');
	//
	// Build task line items
	//
>>>>>>> 33c25a94261c69f3dcb16fcd0ada8736dbf0346a
	$items = '';
	foreach($taskList as $task)
	{
		$id = $task->Get('Task_Id');
		$desc = $task->Get('Description');
		$onclick = "EditTask($id);";
		$class = $task->Get('Status');
	
		$items .= <<<HTML
			<li class="$class" onclick="$onclick">
				<button onclick="Delete($id);">x</button>$desc<button onclick="Complete($id);">&#10003;</button>
			</li>
HTML;
	}
	$taskList = <<<HTML
		$msg
		<ul class="TaskList">
			$items
			<li onclick="NewTask();">+</li>
		</ul>
HTML;
	//
	// determine filters
	//
	$pendingChecked = (Parameters::Get('ShowPending') ? 'checked' : '');
	$completeChecked = (Parameters::Get('ShowComplete') ? 'checked' : '');
?>
<h1 class="TaskList">Task List</h1>
<div class="Filters">
	<form action="UpdateFilters.php" method="post">
	<table>
		<tr>
			<td>Show:</td>
			<td><input type="checkbox" 
						name="Filters[]" 
						value="Pending" 
						onchange="this.form.submit();"
						<?php echo $pendingChecked;?>>pending</input></td>
			<td><input type="checkbox" 
						name="Filters[]" 
						value="Complete"
						onchange="this.form.submit();"
						<?php echo $completeChecked;?>>complete</input></td>
		</tr>
	</table>
	</form>
</div>
<?php echo $taskList;?>
<button class="Logout" onclick="Logout();">Logout</button>