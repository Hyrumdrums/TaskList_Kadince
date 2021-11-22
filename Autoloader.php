<?php
	//
	// autoload classes, require functions.php
	//
	session_start();
	require_once('Functions.php');
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