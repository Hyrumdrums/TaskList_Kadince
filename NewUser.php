	<?php
	//
	// If login err, show message
	//
	$err = Parameters::Pull('Err', '');
	//
	// Set default tasklist filter
	//
	Parameters::Set('ShowPending', true);
?>
<h1>New User</h1>
<h3>Welcome to Task List!<h3>
<div class="error"><?php echo $err;?></div>
<form class="newUser" 
		action="NewUserAction.php"
		method="POST"
		autocomplete="off">
	<input type="text" 
			name="username"
			placeholder="enter new username"
			autofocus></input><br>
	<input type="password" 
			name="password"
			placeholder="password"></input><br>
	<input type="password" 
			name="password"
			placeholder="confirm password"></input><br>
	<input type="submit"></input>
</form>