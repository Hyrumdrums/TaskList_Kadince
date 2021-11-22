<?php
	//
	// autoload classes
	//
	session_start();
	require_once('Functions.php');
	// require('LoadComponents.php');
	function AutoLoader($QualifiedClassName)
	{
		$file = str_replace('\\', '/', $QualifiedClassName);
		$file = "class/$file.php";
		if(file_exists($file))
		{
			require($file);
		}
	}
	spl_autoload_register('AutoLoader');
?>