<?php
	//
	// Login then load app
	//
	require('Autoloader.php');
	$file = 'error';
	$title = '';
	if(!User::IsLoggedIn())
	{
		$file = 'Login.php';
		$title = 'Login';
	}
	else
	{
		$action = Parameters::Pull('Action');
		switch($action)
		{
			case 'EditTask': $file = 'EditTask.php'; break;
			//
			// Default
			//
			case 'TaskList':
			default:         $file = 'TaskList.php'; break;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task List</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
	<script src="App.js"></script>
<?php 
	// echo LoadComponents();
	require($file);
?>
  </body>
</html>
