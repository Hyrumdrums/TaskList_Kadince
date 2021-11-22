<?php
	class User
	{
		const User_IdKey = 'TaskList_User_Id';
		public static function Login($username, $password)
		{
			$user = self::GetUser($username);
			if(is_null($user)) return false;
			if($user['password'] == $password)
			{
				$id = $user['id'];
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
			// Harcode Login
			//
			$userList = array(
							'scott.jackson'=>array('id'=>1, 'password'=>'MANTABLa')
						   ,'eve.joehansen'=>array('id'=>2, 'password'=>'FLOGRowb')
						   ,'dallin.layton'=>array('id'=>3, 'password'=>'password')
			);	
			$user = null;
			if(array_key_exists($username, $userList))
			{
				$user = $userList[$username];
			}
			return $user;
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