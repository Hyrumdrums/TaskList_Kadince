<?php
class Task extends DataObject
{
	public function __construct()
	{
		parent::__construct('Task','Task_Id');
	}
	public function SelectByFilters($user_Id, $filterList)
	{
		//
		// accept filters from task list
		// exclude complete and pending unless passed in filter list
		//
		$conditionList = array();
		if(!in_array('Pending',$filterList)) $conditionList[] = "Status <> 'Pending'";
		if(!in_array('Complete',$filterList)) $conditionList[] = "Status <> 'Complete'";
		$conditions = implode(' AND ', $conditionList);
		$where = '';
		if($conditions) $where = "AND $conditions";
		$sql = <<<MYSQL
			SELECT *
			FROM Task
			WHERE User_Id = $user_Id
			$where
MYSQL;
		$this->stmt = $this->ExecuteSQL($sql, __FUNCTION__, null);
		return $this->GetObjectList($this->stmt);
	}
}
?>