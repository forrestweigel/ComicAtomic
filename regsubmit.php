<?php 
session_start();
include 'elements/db.php';

if($_POST['Password'] == $_POST['Password2'])
{
	$mysqli = new mysqli($dbServer,$dbUser,$dbPass,$db);
	
	if (mysqli_connect_errno()) 
	{
		echo "Connect failed: " . mysqli_connect_error();
		exit();
	}
	
	$User = $mysqli->real_escape_string($_POST['User']);
	$sql = "SELECT username FROM people";
	$result = $mysqli->query($sql);
	$row = $result->fetch_row();
	
	while($row)
	{
		if($row[0] == $User)
		{
			$_SESSION['UNAME'] = $_POST['User'];
			header('Location: register.php?msg=%20Username%20is%20unavailable%20');
			exit();
		}
		
		$row = $result->fetch_row();
	}
	
	$result->free();
	$mysqli->close();
	
	$mysqli = new mysqli($dbServer,$dbUser,$dbPass,$db);

	if (mysqli_connect_errno()) {
		echo "Connect failed: " . mysqli_connect_error();
	}
	
	if (!($stmt = $mysqli->prepare("INSERT INTO people (username, phash) VALUES (?, ?)"))) 
	{
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}


	if (!$stmt->bind_param("ss", $un, $pw)) 
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$un = $mysqli->real_escape_string($_POST['User']);
	$pw = $mysqli->real_escape_string(password_hash($_POST['Password'],PASSWORD_DEFAULT));
	if (!$stmt->execute()) 
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();
	$mysqli->close();
	
	$_SESSION["username"]=$User;	
	header('Lobby\NodejsWebApp1\NodejsWebApp1\public\index.html');
}


else
{
	$_SESSION['UNAME'] = $_POST['User'];
	header('Location: register.php?msg=%20Passwords%20do%20not%20match%20');
	exit();
}
?>