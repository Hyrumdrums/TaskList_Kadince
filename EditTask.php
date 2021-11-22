<?php
	//
	// Load or create new task for edit
	//
	// $task_Id = Parameters::Get('Task_Id');
	// $task = new DataObject('Task', 'Task_Id');
	// if($task_Id)
	// {
		// $task->Set('Task_Id', $task_Id);
		// $task->Load();
	// }
	$task_Id = 1;
	$list = GetFakeTaskList();
	$task = $list[0];
	$desc = $task->Get('Description');
	$notes = $task->Get('Notes');
	//
	// Build options
	//
	$status = $task->Get('Status');
	$checkNoStatus = ($status == '' ? 'checked' : '');
	$checkPending = ($status == 'Pending' ? 'checked' : '');
	$checkComplete = ($status == 'Complete' ? 'checked' : '');
?>
<h1>Edit Task</h1>
<form class="EditTask" method="post" action="UpdateTask.php">
	<input type="hidden" name="Task_Id" value="<?php echo $task_Id;?>"></input>
	<table>
		<tr>
			<td>
				<input name="Description" 
						type="text"
						value="<?php echo $desc;?>"
						placeholder="Description"
						autofocus
						onfocus="this.select();"
						autocomplete="off"></input>
			</td>
		</tr>
		<tr>
			<td>
				<textarea placeholder="Notes"
						  rows="3"
						  cols="30"
						  onfocus="this.select();"><?php echo $notes;?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>Status</legend>
					<table class="StatusOptions">
						<?php
							echo <<<HTML
								<tr>
									<td><input type="radio" 
												name="status" 
												value="" $checkNoStatus></input>
									</td>
									<td>New</td>
								</tr>
								<tr>
									<td><input type="radio" 
												name="status" 
												value="" $checkPending></input>
									</td>
									<td>Pending</td>
								</tr>
								<tr>
									<td><input type="radio" 
												name="status" 
												value="" $checkComplete></input>
									</td>
									<td>Complete</td>
								</tr>
HTML;
					?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
				<input type="submit" 
						name="submit"
						onclick="return ConfirmDelete(<?php echo $task_Id;?>);" 
						value="Delete"></input>
				<input type="submit" 
						name="submit"
						value="Save"></input>
</form>