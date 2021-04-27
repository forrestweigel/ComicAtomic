<?php
	 session_start();
		
	if(!isset($_SESSION["loggedIn"]))
	{
			Header('Location:login.php?msg=Plese Sign In');
			exit();
	}
?>

<html>
<head>
	<link rel="stylesheet" href="elements/bootstrap.min.css">  
<title>JobSearch</title>
<script>	
	function HamAnnimation(changer)
	{
		changer.classList.toggle("change");
		
		if(document.getElementById("MySideBar").style.width < "15em")
		{
			document.getElementById("main").style.marginLeft = "15em";
			document.getElementById("MySideBar").style.width = "15em";
		}
		
		else 
		{
			document.getElementById("main").style.marginLeft = "0";
			document.getElementById("MySideBar").style.width = "0";
		}
		
	}
</script>
<style>
	#main
	{
		transition: margin-left .5s;
		overflow-x: hidden;
	}
	
	#header
	{
		padding-top: 20;
		padding-bottom: 4em;
		height: 80;
		width: 100%;
		display: block;
	}
	
	.footer 
	{
		color: #666;
		padding-bottom: 15px;
	}
	
	.logo
	{
		position: absolute;
		cursor: pointer;
		top: 20;
		right: 35;
		width: 40;
		height: 40;
	}
	
	.logout
	{
		text-decoration: underline;
		cursor: pointer;
		white-space: nowrap;
		font-family: "Arial";
		color: #818181;
		font-size: 1em;
		border: 0px;
		background-color: Transparent;
		outline: none;
		position: absolute;
		bottom: .5em;
		left: .5em;
	}

	.sideBar
	{
		height: 100%;
		width: 0; 
		position: fixed; 
		z-index: 0; 
		top: 0;
		left: 0;
		overflow-x: hidden;
		padding-top: 6em;
		transition: 0.5s;
		background-image: url("https://images.wallpapersden.com/image/download/fluid-shades-dark_66210_1080x1920.jpg");
		background-repeat: no-repeat;
		background-size: cover;
	}
	
	.sideBar menu
	{
		padding-bottom: 2em;
	}
	
	.menu
	{
		white-space: nowrap;
		cursor: pointer;
		font-size: 1.2em;
		font-family: "Arial";
		color: #818181;
		transition: 0.3s;
	}
	
	.menu:hover
	{
		color: #f1f1f1;
		text-decoration: none;
	}
	
	.hamburger
	{
		z-index: 1;
		display: inline-block;
		cursor: pointer;
		padding-top: 20;
		padding-left: 1.5em;
		padding-right: 1.5em;
		position: relative;
		transition 0.3s;
	}
	
	.Top, .Mid, .Bot
	{
		width: 35px;
		height: 5px;
		z-index: 1;
		background-color: #818181;
		margin: 6px 0;
		transition: 0.3s;
	}
	
	.hamBackground:hover ~ .Bot, .hamBackground:hover ~ .Mid, .hamBackground:hover ~ .Top
	{
		background-color: #f1f1f1;
	}
	
	.hamBackground
	{
		z-index: 1;
		position: absolute;
		top: 20;
		left: 20;
		height: 40px;
		width: 35px;
	}

	
	.logout:hover
	{
		color: #f1f1f1;
	}
	
	.change .Top
	{
		transform: rotate(-45deg) translate(-8px, 8px);
	}
	
	.change .Mid
	{
		opacity: 0;
	}
	
	.change .Bot
	{
		transform: rotate(45deg) translate(-8px, -8px);
	}
</style>
</head>
<body>

	<?php
	
	function pageheader(){

	if ($_SESSION["UserType"] == 'jobseeker') 
	{
	?>
		
		<div id = "MySideBar" class = "sideBar">
			<menu><a href = "userpro.php" class = 'menu'>Edit Profile</a></menu>
			<menu><a href = "applicationHistory.php" class = 'menu'>Application History</a></menu>
			<menu><a href = "jobSearch" class = 'menu'> Search Jobs </a></menu>
			<menu><a href = "" class = 'menu'> Jobs by Category </a></menu>
			<menu><a href = "" class = 'menu'> Jobs by Company </a></menu>
			<button class = "logout" onclick = "location.href = 'login.php'">
			Not <?php echo $_SESSION["user"]; ?>? Log Out
		</div>
	
	<?php
	}

	if ($_SESSION["UserType"] == 'employer') 
	{
	?>
		
		<div id = "MySideBar" class = "sideBar">
			<menu><a href = "userpro.php" class = 'menu'>Edit Profile</a></menu>
			<menu><a href = "comppro.php" class = 'menu'> Edit Company </a></menu>
			<menu><a href = "postJob.php" class = 'menu'> Post a Position </a></menu>
			<menu><a href = "" class = 'menu'> Edit a Position </a></menu>
			<menu><a href = "" class = 'menu'> View Applications </a></menu>
			<menu><a href = "" class = 'menu'> Search Applicants </a></menu>
			<button class = "logout" onclick = "location.href = 'login.php'">
			Not <?php echo $_SESSION["user"]; ?>? Log Out
		</div>
	
	<?php
	}

	if ($_SESSION["UserType"] == 'admin') 
	{
	?>
		
		<div id = "MySideBar" class = "sideBar">
			<menu><a href = "userpro" class = 'menu'>Edit Profile</a></menu>
			<menu><a href = "regcomp" class = 'menu'> Register Company </a></menu>
			<menu><a href = "regemp" class = 'menu'> Register Employer </a></menu>
			<button class = "logout" onclick = "location.href = 'login.php'">
			Not <?php echo $_SESSION["user"]; ?>? Log Out
		</div>
	
	<?php
	}
	?>
	
	<div id = ham class = "hamburger" onclick = "HamAnnimation(this)">
	<div class = "hamBackground"></div>
	<div class = "Top"></div>
	<div class = "Mid"></div>
	<div class = "Bot"></div>
	</div>

	<div id = "main">			
		<div id = "header">
			<div class = "logo" onclick = "location.href = 'home.php'">
				<image src = "img/logo.jpg" width = 60, height = 60></image>	
			</div>
		<hr width = 98%>
	</div>
		
	<?php
	}
	function pagefooter(){
	?>
	
		<footer class="footer">
		<p class="text-xl-center">Copyright &copy; 2020 Cr!tical Media</p>
		</footer>
	
	<?php } ?>
</body>
</html>
