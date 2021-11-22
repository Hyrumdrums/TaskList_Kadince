<?php
	class Route
	{
		public static function To($file, $action = '', $paramList =  null)
		{
			//
			// set parameters and go to index with action param set
			//
			if($action) Parameters::Set('Action', $action);
			die('Set action');
			if(!is_null($paramList))
			{
				foreach($paramList as $name => $value)
				{
					Parameters::Set($name, $value);
				}
			}
			$prot = $_SERVER['SERVER_PROTOCOL'];
			$prot = explode('/', $prot)[0]; 		// HTTP/1.1 to HTTP(s)
			$prot = strtolower($prot);
			$host = $_SERVER['HTTP_HOST'];
			$url = "$prot://$host/";
			switch($file)
			{
				case 'index':
					header("Location: $url");
					break;
			}
		}
	}
?>