<?php
	//
	// If login err, show message
	//
	$err = Parameters::Pull('Err', '');
?>
<h1>Login to Task List</h1>
<div class="error"><?php echo $err;?></div>
<form class="login" 
		action="LoginAction.php"
		method="POST"
		autocomplete="off">
	<input type="text" 
			name="username"
			placeholder="UserName"
			autofocus></input><br>
	<input type="password" 
			name="password"
			placeholder="password"></input><br>
	<input type="submit"></input>
</form>