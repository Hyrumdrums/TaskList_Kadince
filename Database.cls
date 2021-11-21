<?php
	class Database
	{
		public static function Connect()
		{
			static $conn = null;
			if(is_null($conn))
			{
				$conn = self::OpenConnection();
			}
			return $conn;
		}
		private static function GetCredentialFile()
		{
			$fileName = "creds.json";
			return $fileName;
		}
		private static function OpenConnection()
		{
			//
			// Open connection to database
			//
			$fileName = self::GetCredentialFile();
			$json = file_get_contents($fileName);
			$credList = json_decode($json);
			var_dump($credList);die();
			$hostname = $credList['host'];
			$username = $credList['user'];
			$password = $credList['password'];
			$database = $credList['db'];
			$conn = new mysqli($hostname, $username, $password, $database);
			if ($conn->connect_error) 
			{
				die("Connection failed: " . $conn->connect_error);
			}
			return $conn;
		}
	}
?>