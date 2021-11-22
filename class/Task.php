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
		if($conditions) $where = "WHERE $conditions";
		$sql = <<<MYSQL
			SELECT *
			FROM Task
			$where
MYSQL;
		$this->stmt = $this->ExecuteSQL($sql, __FUNCTION__, null);
		return $this->GetObjectList($this->stmt);
	}
}
?>