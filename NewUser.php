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
<script>
	var password;
	var password2;
	window.addEventListener('load', function()
	{
		password = document.getElementById("password")
		password2 = document.getElementById("password2");
		password.onchange = validatePassword;
		password2.onkeyup = validatePassword;
	});
</script>
<form class="newUser" 
		action="NewUserAction.php"
		method="POST"
		autocomplete="off">
	<input type="text" 
			name="username"
			placeholder="pick a username"
			autofocus></input><br>
	<input type="password" 
			name="password"
			placeholder="password"></input><br>
	<input type="password" 
			name="password2"
			placeholder="confirm password"></input><br>
	<input type="submit"></input>
</form>