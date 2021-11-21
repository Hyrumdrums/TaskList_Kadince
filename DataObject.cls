<?php
require_once('Database.cls');
class DataObject
{
	//
	// An instantiation of a data object is a new item until LoadRecord is called.
	//  This design requires that a respective table has a primary key column.
	// 	This is verified in __construct
	//
	private $dbName;
	private $conn;
	private $tableName;
	protected $columnList = [];		// Values keyed by columnName
	protected $primaryColumn = '';
	protected $modifiedColumnList = [];
	protected $selectLimit = 0;
	protected $orderByList = [];
	protected $stmt;
	protected $specialConditionList = [];
	protected $verbose = false;
	protected $isNewItem = true;
	
	public function __construct($tableName, $primaryColumn)
	{
		$this->dbName = 'Task';
		$this->conn = DATABASE::Connect();
		$this->tableName = $tableName;
		$this->primaryColumn = $primaryColumn;
		//
		// if "verbose" URL variable, then set Verbose true
		//
		if (isset($_POST['verbose']))
		{
			$this->Verbose=true;
		}
	}
	public function IsNewItem()
	{
		return $this->isNewItem;
	}
	public function IsFound()
	{
		//
		// Inverse of IsNewItem
		//
		return !$this->isNewItem;
	}
	public function Save()
	{
		if($this->isNewItem)
		{
			$this->InsertNew();
		}
		else
		{
			$this->Update();
		}
		
	}
	public function Delete()
	{
		if(!$this->isNewItem)
		{
			$sql = <<<SQL

DELETE FROM {$this->tableName}
WHERE  {$this->primaryColumn} = ?

SQL;
			$id = $this->GetId();
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param('i', $id);
			$this->BeVerbose($sql,__FUNCTION__, array($id));
			$stmt->execute();
			// $this->HandleSQLErrors($result, __FUNCTION__);
			$this->Clear();
		}
	}
	public function Find()
	{
		//
		// Load and find have the same function
		//
		$this->Load();
	}
	public function Clear()
	{
		//
		// Reset to new instantiation with no respective db record
		//
		$this->columnList = [];
		$this->modifiedColumnList = [];
		$this->selectLimit = 0;
		$this->orderByList = [];
		$this->specialConditionList = [];
		$this->isNewItem = true;
	}
	public function Set($columnName, $value)
	{
		if(!($this->isNewItem))
		{
			if($columnName == $this->GetPrimaryColumn()) die('You cannot modify the identity column ' . $columnName . ' on an existing record');
		}
		
		$this->modifiedColumnList[$columnName] = $value;
	}
	public function Get($columnName)
	{
		//
		// If column value has been set with 'Set' method, use that value,
		//  else, use db value
		//
		// var_dump($this->columnList);
		// die();
		$returnValue = $this->columnList[$columnName];
		if(array_key_exists($columnName, $this->modifiedColumnList))
		{
			$returnValue = $this->modifiedColumnList[$columnName];
		}
		return $returnValue;
	}
	public function ToArray()
	{
		return $this->columnList;
	}
	public function ToHTMLHeader()
	{
		$headerRow = '';
		foreach($this->columnList as $name => $value)
		{
			$headerRow .= "\n<th>$name</th>";
		}
		return $headerRow;
	}
	public function ToHTMLRow()
	{
		$row = '';
		foreach($this->columnList as $name => $value)
		{
			$displayValue = $value;
			if(method_exists($value, 'format'))
			{
				$displayValue = $value->format('m/d/Y');
			}
			$row .= "<td>$displayValue</td>";
		}
		// $cells = implode('</td><td>', $this->columnList);
		$row .= <<<HTML
			<tr>
				$row
			</tr>
HTML;
		return $row;
	}
	public function GetPrimaryColumn()
	{
		return $this->primaryColumn;
	}
	public static function LoadById($dbName, $tableName, $id)
	{
		$obj = new DataObject($dbName, $tableName);
		$idColumn = $obj->GetPrimaryColumn();
		$obj->Set($idColumn, $id);
		$obj->Load();
		return $obj;
	}
	public function Load()
	{
		//
		// Return first result from select
		//
		$this->Limit(1);
		$list = $this->Select();
		if(count($list))
		{
			$item = $list[0];
			$properties = $item->ToArray();
			$this->LoadRecord($properties, $this->primaryColumn, $this->stmt);
		}
		else
		{
			$this->Clear();
		}
	}
	public function Select()
	{
		$conditionColumnList = [];
		$parameterList = [];
		foreach($this->modifiedColumnList as $column => $value)
		{
			$conditionColumnList[] = "$column = ?";
			$parameterList[] = $value;
		}
		$conditionColumnString = '';
		if(count($conditionColumnList)) 
		{
			$conditionColumnString = implode("\nAND ", $conditionColumnList); // with newline char
			$conditionColumnString = 'WHERE ' . $conditionColumnString;
		}
		$this->AppendSpecialConditions($conditionColumnList, $conditionColumnString, $parameterList);
		$limit = $this->BuildLimit();
		$orderBy = $this->BuildOrderBy();
		$sql = <<<SQL
SELECT *
FROM `{$this->tableName}`
$conditionColumnString
$orderBy
$limit
SQL;
		$this->stmt = $this->ExecuteSQL($sql, __FUNCTION__, $parameterList);
		return $this->GetObjectList($this->stmt);
	}
	protected function ExecuteSQL($sql, $function, $parameterList)
	{
		//
		// return stmt - mysql mod less adept
		//
		$this->BeVerbose($sql, $function, $parameterList);
		$stmt = $this->conn->prepare($sql);
		// var_dump($this->conn); die();
		// echo $sql;
		// var_dump($parameterList);
		$this->HandleSTMTErrors($stmt, $function);
		//var_dump($this->conn);die();
		$indicatorString = '';
		foreach($parameterList as $param)
		{
			//
			// Second option -> $stmt->bind_param('ss', ...['DEU', 'POL']);
			//
			$type = gettype($param);
			$indicator = '';
			switch($type)
			{
				case 'integer': $indicator = 'i'; break;
				case 'double': $indicator = 'd'; break;
				case 'string': $indicator = 's'; break;
				default: die('Cannot handle type: ' . $type); break;
			}
			$indicatorString .= $indicator;
			// $stmt->bind_param($indicator, $param);
		}
		// echo $indicatorString . '--';
		if(count($parameterList)) $stmt->bind_param($indicatorString, ...$parameterList);
		// var_dump($stmt);echo '<br><br>';
		// var_dump($this->conn); die();
		$this->HandleSTMTErrors($stmt, $function);
		$this->BeVerbose($sql,$function, $parameterList);
		$stmt->execute();
		
		return $stmt;
	}
	protected function GetObjectList($stmt)
	{
		$list = array();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc())
		{
			$object = $this->InstantiateNewSelf();
			$object->LoadRecord($row, $this->primaryColumn, $stmt);
			array_push($list, $object);
		}
		return $list;
	}
	private function InstantiateNewSelf()
	{
		//
		// This instantiates either DataObject, or an extended class
		// 2 parameters are not necessary for extened classes, and thus ignored
		//
		$class = get_class($this);
		$object = new $class($this->tableName, $this->primaryColumn);
		return $object;
	}
	protected function GetRows($stmt)
	{
		//
		// return array of sql result rows
		//
		$list = array();
		while($row = $stmt->fetch_row())
		{
			array_push($list, $row);
		}
		return $list;
	}
	protected function AssertIsLoaded($functionName)
	{
		//
		// die if this instance does not reflect a db record
		//
		if($this->IsNewItem()) die($this->dbName . '>' 
								. $this->tableName . '>' 
								. $functionName . '(): No record loaded');
	}
	public function Limit($int)
	{
		if(!is_int($int)) die('Invalid limit specified');
		$this->selectLimit = $int;
	}
	public function AddOrderBy($columnName, $direction = '')
	{
		switch($direction)
		{
			case '':
			case 'ASC':
			case 'DESC':
				break;
			default:
				die($direction . ' is not a valid "Order By" direction');
				break;
		}
		$this->orderByList[] = $columnName . ' ' . $direction;
	}
	public function MakeVerbose()
	{
		$this->verbose = true;
	}
	public function AddSelectCondition($FieldName, $Type, $Param1 = '', $Param2 = '')
	{
		//
		// Add a special SQL conditions
		//
		$Condition = Array();
		switch($Type)
		{
			case 'InDateRange':
				$Condition['Type'] = $Type;
				$Condition['FieldName'] = $FieldName;
				$Condition['StartDate'] = $Param1;
				$Condition['EndDate'] = $Param2;
				Break;
			case 'InArray':
				$Condition['Type'] = $Type;
				$Condition['FieldName'] = $FieldName;
				$Condition['Array'] = $Param1;
				if(!count($Condition['Array'])) die('No values in Array');
				Break;
			case 'Like':
				$Condition['Type'] = $Type;
				$Condition['FieldName'] = $FieldName;
				$Condition['Keyphrase'] = $Param1;
				$Condition['MultipleLikeFieldMode'] = 'AND';	
				if($Param2 == 'OR')									//If param 2(mode) passed as "OR"
				{
					$Condition['MultipleLikeFieldMode'] = $Param2;
				}
				Break;
			case '<>':
			case '<':
			case '>':
			case '<=':
			case '>=':
				$Condition['Type'] = $Type;
				$Condition['FieldName'] = $FieldName;
				$Condition['Value'] = $Param1;
				break;
			default:
				die('Undefined Select Condition: ' . $Type);
				break;
		}
		if(count($Condition))
			array_push($this->specialConditionList,$Condition);
	}
	public function dump()
	{
		echo '<pre>';
		var_dump($this);
	}
	public function dumpValues()
	{
		echo '<pre>';
		var_dump($this->columnList);
		var_dump($this->modifiedColumnList);
	}
	private function AppendSpecialConditions($conditionColumnList,&$conditionColumnString,&$parameterList)
	{
		//
		// Append special SQL conditions to sql statement
		//	if count($conditionColumnList), use AND instead of WHERE
		//
		$sql = '';
		$connector = 'WHERE';
		if(count($conditionColumnList)) $connector = 'AND';
		if(count($this->specialConditionList))
		{
			//
			//Loop to append all conditions except 'Like'
			//
			foreach($this->specialConditionList as $Condition)
			{
				$Type = $Condition['Type'];
				$FieldName = $Condition['FieldName'];
				switch($Type)
				{
					case 'InDateRange':
						$StartDate = $Condition['StartDate'];
						$EndDate = $Condition['EndDate'];
						$sql .= <<<SQL
$connector [$FieldName] BETWEEN ? AND ?
SQL;
						$parameterList[] = $StartDate;
						$parameterList[] = $EndDate;
						$connector = 'AND';
						break;
					case 'InArray':
						$list = $Condition['Array'];
						$placeholderList = [];
						foreach($list as $item)
						{
							$placeholderList[] = '?';
							$parameterList[] = $item;
						}
						$placeholderString = implode(',',$placeholderList);
						$sql .= <<<SQL
$connector [$FieldName] IN ($placeholderString)
SQL;
						$connector = 'AND';
						break;
					case 'Like':
						//Handled below
						break;
					case '<>':
					case '<':
					case '>':
					case '<=':
					case '>=':
						$value = $Condition['Value'];
						$operator = $Type;
						$sql .= <<<SQL
$connector [$FieldName] $operator ?
SQL;
						$parameterList[] = $value;
						$connector = 'AND';
						break;
				}
				$sql .= "\n";
			}
			//
			//Loop to append all 'Like' conditions
			//
			$likeConnector = "$connector (";							//Connector begins by opening sql "...AND (this LIKE %that%..."
			$likeConditionExists = false;
			foreach($this->specialConditionList as $Condition)
			{
				$Type = $Condition['Type'];
				$FieldName = $Condition['FieldName'];
				if($Type == 'Like')
				{				
					$Mode = $Condition['MultipleLikeFieldMode'];		//OR or AND
					$Keyphrase = $Condition['Keyphrase'];
					$Keyphrase = "%$Keyphrase%";
					$sql .= <<<SQL
						$likeConnector [$FieldName] LIKE ?
SQL;
						// $likeConnector [$FieldName] LIKE '%' + ? + '%'
					$parameterList[] = $Keyphrase;
					$likeConnector = " $Mode ";							//OR or AND
					$likeConditionExists = true;						
				}
			}
			if($likeConditionExists)		//At least one like condition existed, you need to close parenthesis and modify sql connector
			{
				$sql .= ")";
				$connector = 'AND';
			}
		}
		$conditionColumnString .= "\n" . $sql;
	}
	private function Update()
	{
		$updateColumnList = [];
		$parameterList = [];
		$placeholderList = [];
		foreach($this->modifiedColumnList as $column => $value)
		{
			$updateColumnList[] = "$column = ?";
			$parameterList[] = $value;
			$placeholderList[] = '?';
		}
		$updateColumnString = implode("\n,", $updateColumnList); // with newline char
		$placeholderString = implode(',', $placeholderList);
		$primaryColumn = $this->GetPrimaryColumn();
		$sql = <<<SQL

UPDATE {$this->tableName}
SET $updateColumnString
WHERE {$this->primaryColumn} = ?

SQL;
		$parameterList[] = $this->GetId();							// Append last of paramters
		$this->BeVerbose($sql,__FUNCTION__, $parameterList);
		$this->ExecuteSQL($sql, __FUNCTION__, $parameterList);
	}
	private function GetId()
	{
		return $this->Get($this->primaryColumn);
	}
	private function InsertNew()
	{
		$columnNameList = [];
		$placeholderList = [];
		foreach($this->modifiedColumnList as $columnName => $value)
		{
			$columnNameList[] = $columnName;
			$placeholderList[] = '?';
		}
		$placeholderString = implode(',', $placeholderList);
		$columnNameString = implode('`,`', $columnNameList);
		$sql = <<<SQL
			INSERT INTO {$this->tableName}
			(`$columnNameString`)
			VALUES
			($placeholderString)
SQL;
		$parameters = $this->Parameterize($this->modifiedColumnList);
		$stmt = $this->ExecuteSQL($sql, __FUNCTION__, $parameters);
		$id = $this->GetLastID();
		$this->Clear();
		if($id)
		{
			$this->Set($this->primaryColumn, $id);
			$this->Load();
		}
	}
	private function Parameterize($list)
	{
		//
		// String keys are not allowed in parameters arrays. make numerical index
		//
		$newList = [];
		foreach($list as $key => $value)
		{
			$newList[] = $value;
		}
		return $newList;
	}
	private function GetLastID()
	{
		$sql = 'SELECT LAST_INSERT_ID() as Id;';
		$stmt = $this->ExecuteSQL($sql, __FUNCTION__, array());
		$result = $stmt->get_result();
		if($row = $result->fetch_assoc())
		{
			return $row['Id'];
		}
		return 0;
	}
	private function BuildLimit()
	{
		if($this->selectLimit)
		{
			return 'LIMIT 0, ' . $this->selectLimit;
		}
		return '';
	}
	private function BuildOrderBy()
	{
		$sql = '';
		
		if(count($this->orderByList))
		{
			$string = implode(',', $this->orderByList);
			$sql = "ORDER BY $string";
		}
		return $sql;
	}
	private function LoadRecord($list, $primaryColumn, $stmt)
	{
		//
		// use passed array to 'Load' this data object, making it reflect a row in the db.
		// stmt is passed to make it appear as if this object was loaded individually, so it should function that way
		//
		$this->Clear();
		$this->stmt = $stmt;
		$this->primaryColumn = $primaryColumn;
		// var_dump($list);
		// die();
		$this->columnList = $list;
		$this->isNewItem = false;
	}
	private function BeVerbose($sql, $method, $parameters = array())
	{
		if ($this->verbose)
		{
			echo "[$method]<br>$sql<br>";
			if(count($parameters))
			{
				echo 'parameter ';
				var_dump($parameters);
				echo '<br>';
			}
		}
		
	}
	// private function HandleConnErrors($conn, $method)
	// {
		// //
		// //If executed sql statement had errors, output and kill code execution
		// //
		// if ($stmt === false) 
		// {
			// echo mysqli_stmt_error($stmt);
			// throw new \ErrorException("$method MYSQL Error:");
		// }
	// }
	private function HandleSTMTErrors($stmt, $method)
	{
		//
		//If executed sql statement had errors, output and kill code execution
		//
		if ($stmt === false) 
		{
			// echo mysqli_stmt_error($stmt);
			throw new \ErrorException("$method MYSQL Error:");
		}
	}
	//
	// Helper functions
	// This functions tie closely to working with data objects
	//
	public static function IndexList($list, $property)
	{
		//
		// return list keyed by specified property
		//
		$keyedList = array();
		foreach($list as $item)
		{
			$value = $item->Get($property);
			$keyedList[$value] = $item;
		}
		return $keyedList;
	}
	public static function GroupAndKeyList($list, $byColumn)
	{
		//
		// return list keyed by field specified
		//
		/*
		$after = function($before, 'prop1')
		
		Starting array:
		[
			obj1 {prop1:"aa"; prop2:"bb"}
		   ,obj2 {prop1:"aa"; prop2:"bb"}
		   ,obj3 {prop1:"zz"; prop2:"bb"}
		]
		Resulting associative array of arrays:
		[
			"aa"=>[obj1, obj2]
		   ,"zz"=>[obj3      ]
		]
		*/
		$result = array();
		foreach($list as $item)
		{
			$key = $value = $item->Get($byColumn);
			if(!is_string($value) && !is_int($value))
			{
				$key = 'invalid key';
			}
			if(array_key_exists($key, $result))
			{
				//
				// Add to existing list, return to array under key
				//
				$existing = $result[$key];
				$existing[] = $item;
				$result[$key] = $existing;
			}
			else
			{
				//
				// First item with key, insert as new list
				//
				$newList = array($item);
				$result[$key] = $newList;
			}
		}
		return $result;
	}
	public static function BuildFieldValueList($list, $field)
	{
		//
		// return list of values of each object in list
		//
		$result = array();
		foreach($list as $item)
		{
			$value = $item->Get($field);
			$result[] = $value;
		}
		return $result;
	}
	public static function ListToJSON($list)
	{
		//
		// Simplify data objects in new list, retaining associative key if exists
		//
		$newList = [];
		foreach($list as $key => $value)
		{
			$simpleObject = $value->ToArray();
			$newList[$key] = $simpleObject;
		}
		return json_encode($newList);
	}
	public static function ListToHTMLTable($list)
	{
		//
		// Accept list of this type of data object - return html table
		// Build Header
		//
		$headerRow = '';
		if(array_key_exists(0,$list))
		{
			$first = $list[0];
			$headerRow = $first->ToHTMLHeader();
		}
		//
		// build rows
		//
		$rows = '';
		foreach($list as $obj)
		{
			$rows .= $obj->ToHTMLRow();
		}
		return <<<HTML
			<table>
				<tr>
					$headerRow
				</tr>
				$rows
			</table>
HTML;
	}
	public static function Libratize($list, $property, $lookupObject, $lookupObjectKey, $newPropName)
	{
		//
		// use $property of $list items to load $lookupObject by $lookupObjectKey.
		// add lookupObject as value for $newPropName of original list
		//
		$keyList = self::BuildFieldValueList($list, $property);
		$keyList = array_unique($keyList);
		$lookupObject->AddSelectCondition($lookupObjectKey, 'InArray', $keyList);
		$lookupObjectList = $lookupObject->Select();
		$keyedLookupObjectList = self::IndexList($lookupObjectList, $lookupObjectKey);
		foreach($list as &$object)
		{
			$key = $object->Get($property);
			$fieldValue = '';
			$lookupObject = null;
			if(array_key_exists($key, $keyedLookupObjectList))
			{
				$lookupObject = $keyedLookupObjectList[$key];
				// $fieldValue = $lookupObject->$lookupObjectTargetField;
			}
			$object->$newPropName = $lookupObject;
		}
		return $list;
	}
}
?>
