<?php 
session_start();
include 'elements/db.php';

if($_SESSION["login"])
{
	unset($_SESSION["login"]);
}

else
{
	Header('Location:index.php?msg=Plese Sign Up');
	exit();
}	

if(isset($_POST['User']) && isset($_POST['Password']))
{
$mysqli = new mysqli($dbServer,$dbUser,$dbPass,$db);
	
	if (mysqli_connect_errno()) 
	{
		echo "Connect failed: " . mysqli_connect_error();
		exit();
	}
	
	$User = $mysqli->real_escape_string($_POST['User']);
	$sql = "SELECT password FROM user WHERE user.username = '$User'";
	$result = $mysqli->query($sql);
	
	if($result->num_rows == 0)
	{
		header('Location: index.php?msg=Username%20not%20found');
		exit();
	}
	
	$row = $result->fetch_row();
	
	if (password_verify($_POST['Password'],$row[0])) 
	{
		$_SESSION["username"] = $User;
		Header('Location: lobby\index.php');
	}
	
	else 
	{
		Header('Location: index.php?msg=%20Password%20is%20Incorrect');
		exit();
	}
	
	$result->free();
	$mysqli->close();
}

else
{
	Header('Location:index.php?msg=Enter%20Login%20Details');
}
?>
