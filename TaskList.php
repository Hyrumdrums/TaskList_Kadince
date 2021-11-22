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
	// $taskList = $task->Select();
	

	// $taskList = GetFakeTaskList();
	$msg = Parameters::Pull('msg');
	$items = '';
	foreach($taskList as $task)
	{
		$id = $task->Get('Task_Id');
		$desc = $task->Get('Description');
		$onclick = "EditTask($id);";
		$class = $task->Get('Status');
		$items .= <<<HTML
			<li class="$class" onclick="$onclick">$desc<button onclick="alert('got it');">x</button></li>
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