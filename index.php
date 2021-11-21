<?php
require('DataObject.cls');
// select
// $user = new DataObject('User', 'User_Id');
// $list = $user->Select();
// $user = $list[0];
// echo 'Loaded ' . $user->Get('Name') . ', his pwd is ' . $user->Get('Password');
// insert
// $user = new DataObject('User', 'User_Id');
// $user->Set('Name', 'Carol');
// $user->Set('Password', 'newPassword');
// $user->MakeVerbose();
// $user->Save();
//load
// $user = new DataObject('User', 'User_Id');
// $user->Set('User_Id', 7);
// $user->Load();
// $user->MakeVerbose();
// $user->Dump();
$user = new DataObject('User', 'User_Id');
$user->Set('User_Id', 7);
$user->Load();
$user->Delete();












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
