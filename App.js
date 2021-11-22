function Route(action, paramList = null)
{
	//
	// post action to routing page w/ params
	//
	let form = document.createElement('form');
	form.action = 'Route.php';
	form.method = 'post';
	document.body.appendChild(form);
	let input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'Action';
	input.value = action;
	form.appendChild(input);
	//
	// append params if exist
	//
	if(paramList !== null)
	{
		for(let name in paramList)
		{
			let value = paramList[name];
			let input = document.createElement('input');
			input.type = 'hidden';
			input.name = name;
			input.value = value;
			form.appendChild(input);
		}
	}
	
	form.submit();
}
function EditTask(id)
{
	let params = {'Task_Id':id};
	Route('EditTask', params);
}
function Delete(id)
{
	event.stopImmediatePropagation(); // prevent edit task
	if(!ConfirmDelete(id)) return;
	let params = {'Task_Id':id};
	Route('DeleteTask', params);
}
function Logout()
{
	Route('Logout');
}
function ConfirmDelete(task_Id)
{
	//
	// confirm delete if task already existed
	//
	if(!task_Id) return true;
	let msg = 'Are you sure you want to delete this task?'
	let confirmed = confirm(msg);
	return confirmed;
}
function NewTask()
{
	EditTask(0);
}


function validatePassword(){
  if(password.value != password2.value) {
    password2.setCustomValidity("Passwords Don't Match");
  } else {
    password2.setCustomValidity('');
  }
}