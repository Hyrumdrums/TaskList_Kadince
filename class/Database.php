<?php
	class Database
	{
		//
		// Simple db class used by ORM data object
		//
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
			//
			// creds are not encryped
			//
			$fileName = "../creds.json";
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
			$hostname = $credList->host;
			$username = $credList->user;
			$password = $credList->password;
			$database = $credList->db;
			$conn = new mysqli($hostname, $username, $password, $database);
			if ($conn->connect_error) 
			{
				die("Connection failed: " . $conn->connect_error);
			}
			return $conn;
		}
	}
?>