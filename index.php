<?php 
session_start();
if(isset($_SESSION['authenticated']))
	header('Location: main.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Team 254</title>
		<link rel="stylesheet" type="text/css" href="team254.css" />
	</head>
	<body>
		<div class="main">
			<a href="index.php"><div class="headerimage"></div></a>
			<div class="skylineimage">
				<div class="title">Team 254 Inventory System</div>
			</div>
			<div class="content"><br/>
			<div align="center">
			<?php 
			if(isset($_SESSION['failure']))
				echo "<div class=\"error\">Incorrect username and/or password</div><br />\n";
			if(isset($_SESSION['denied']))
				echo "<div class=\"error\">ERROR: You have tried to access a restricted page. Please login below.</div><br />\n";
			?>
			<form action="authenticate.php" method="post">
			<table>
			<tr>
			<td><input type="text" class="loginbox" id="username" name="username" placeholder="Username"/></td>
			</tr>
			<tr>
			<td><input type="password" class="loginbox" id="password" name="password" placeholder="Password"/></td>
			</tr>
			</table>
			<br />
			<input type="submit" class="loginbutton" value="Login" name="login" />
			</form>
			</div>
			<br />
			</div>
		</div>
		<div class="footer">
			<div style="text-decoration:underline;font-size:24px;font-family:FuturaLT;color:#DDD">View my Team's Checked Out Parts</div>
                <form action="viewTeam.php" method="get">
				<select name="team" onChange="this.form.submit()">
					<option value="none">Pick a Team...</option>
					<option value="a">Team A</option>
					<option value="b">Team B</option>
					<option value="c">Team C</option>
					<option value="d">Team D</option>
					<option value="e">Team E</option>
					<option value="f">Team F</option>
					<option value="g">Team G</option>
					<option value="bad">Bad</option>
				</select>
				</form>
			</div>

	</body>
</html>