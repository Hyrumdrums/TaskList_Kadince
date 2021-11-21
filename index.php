<?php
require('DataObject.cls');
$task = new DataObject('Task', 'Task_Id');
$list = $task->Select();
var_dump($list);














exit();
//phpinfo();
//die();
$servername = "localhost";
$username = "app";
$password = "supersecretpassword";
$dbname = "Task";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// test connection
//$result = 



// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM User";
$result = $conn->query($sql);

if ($result === false) {
	var_dump(mysqli_error($conn));
	die('err');
}
 
   while($row = $result->fetch_assoc()) {
	  
    echo "username is: " . $row["Name"];
}
//} else {
//  echo "0 results";
//}
$conn->close();
?>
