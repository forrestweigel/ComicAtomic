<?php
	session_start();
?>

<!DOCTYPE.html>
<html>
<head>
<style>
	
	#main
	{
		text-align: center;
		font-family: "Arial";
	}
	
	#header
	{		
		padding-top: 40;
		padding-bottom: 1em;
		height: 80;
		width: 100%;
		display: block;
	}
	
	.logo
	{
		position: absolute;
		top: 20;
		right: 35;
		width: 40;
		height: 40;
	}
	
	form
	{
		text-align: center;
		padding-bottom: 1.5em;
	}
	
	.button1 
	{
		cursor: pointer;
		font-family: "Arial";
		color: #2798D5;
		font-size: 1.5em;
		padding: .5em;
		border: 0px;
		background-color: Transparent;
		outline: none;
	}
		
	.button1:hover
	{
		text-decoration: underline;
	}
	
	input
	{
		border-radius: 5px;
		line-height: 2em;
		border: 2px solid #f1f1f1;
		font-size: 1em; 
		padding: 0.3em;
	}
	
	.error
	{
		padding: .2em;
		background-color: #ffc3bd;
		color: #ffffff;
	}
	
</style>
</head>
<body> 
		<div id = "main">	
		<div id = "header">
		</div>
		
		<?php
		
		$username = '';	
		
		if (isset($_GET['msg'])) {
			?>
			<div class = "error">
			<?php
			echo $_GET['msg'];
			echo "<BR/>";
			
			$username = $_SESSION['UNAME'];
			?>
			
		</div>
				
		<?php
		}
		?>
		
		<image id = "myImage" src = "img/logo.png" width = 400, height = 200 draggable = "false"></image>
		</br>
		<form method = "post" action = "regsubmit.php">
		
			<input type = "text" name = "User" value = "<?php echo $username; ?>" size = 40em placeholder = "Username" required></br></br>
			<input type = "password" name = "Password" size = 40em placeholder = "Password" required></br></br>
			<input type = "password" name = "Password2" size = 40em placeholder = "Retype Password" required></br></br>
		
			<button class = "button1"> Submit </button>
		</form>
	</div>
</body>
</html>