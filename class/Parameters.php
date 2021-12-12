<?php
	class Parameters
	{
		//
		// for passing params in session
		//
		public static function Get($name, $default = '')
		{
			//
			// Return session var
			//
			$value = $default;
			if(isset($_SESSION[$name]))
			{
				$value = $_SESSION[$name];
			}
			return $value;
		}
		public static function Pull($name, $default = '')
		{
			//
			// use get, then unset session var
			//
			$value = self::Get($name, $default);
			self::Clear($name);
			return $value;
		}
		public static function Clear($name)
		{
			if(isset($_SESSION[$name]))
			{
				unset($_SESSION[$name]);
			}
		}
		public static function Set($name, $value)
		{
			//
			// set session var
			//
			$_SESSION[$name] = $value;
		}
	}
?>
