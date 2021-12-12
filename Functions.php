<?php
	function GetPost($name, $default = '')
	{
		return (isset($_POST[$name]) ? $_POST[$name] : $default);
	}
	function Get($name, $default = '')
	{
		return (isset($_GET[$name]) ? $_GET[$name] : $default);
	}
	//
	// Pre-'database connection' testing tools
	//
	function fake($taskList)
	{
		foreach($taskList as &$task)
		{
			$task = new temp($task);
		}
		return $taskList;
	}
	function GetFakeTaskList()
	{
		$json = <<<JSON
		[
				{"Task_Id":1,"User_Id":1,"Description":"Task1","Status":"","Notes":"NA"}
			   ,{"Task_Id":2,"User_Id":1,"Description":"Task2","Status":"Complete","Notes":"NA"}
			   ,{"Task_Id":3,"User_Id":1,"Description":"Task6","Status":"Pending","Notes":"NA"}
			   ,{"Task_Id":4,"User_Id":1,"Description":"Task4","Status":"Complete","Notes":"NA"}
				
			]
JSON;
		$taskList = json_decode($json);
		$taskList = fake($taskList);
		return $taskList;
	}
	class temp
	{
		private $data;
		public function __construct($data)
		{
			$this->data = $data;
		}
		public function Get($field)
		{
			return $this->data->$field;
		}
	}
?>
