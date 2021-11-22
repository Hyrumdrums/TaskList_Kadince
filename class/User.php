<?php
	class User
	{
		//
		// User class to handle all login and current user info
		//
		const User_IdKey = 'TaskList_User_Id';
		public static function Login($username, $password)
		{
			//
			// lookup user by username, compare passwords, save uid in session if successful
			//
			$user = self::GetUser($username);
			if(is_null($user)) return false;
			if($user->Get('password') == $password)
			{
				$id = $user->Get('id');
				$_SESSION[self::User_IdKey] = $id;
				return true;
			}
			return false;
		}
		public static function Logout()
		{
			session_destroy();
		}
		public static function GetUser($username)
		{
			//
			// Find user record by name and return
			//
			$user = new DataObject('User','User_Id');
			$user->Set('Name', $username);
			$user->Find();
			if($user->IsFound()) return $user;
			return null;
		}
		public static function IsLoggedIn()
		{
			$user_Id = self::GetUser_Id();
			return ($user_Id ? true : false);
		}
		public static function GetUser_Id()
		{
			if(isset($_SESSION[self::User_IdKey]))
			{
				return $_SESSION[self::User_IdKey];
			}
			return 0;
		}
	}
?>